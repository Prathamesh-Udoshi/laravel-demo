<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\WeeklyContent;
use App\Support\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class VivaController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Show the main Viva Voce exam workspace.
     */
    public function show($courseId)
    {
        $course = Course::findOrFail($courseId);
        return view('courses.viva', compact('course'));
    }

    /**
     * Start the Viva Voce session, clear old data, and ask the first question.
     */
    public function start(Request $request, $courseId)
    {
        $validated = $request->validate([
            'scope' => 'required|in:midterm,final'
        ]);

        $scope = $validated['scope'];
        $course = Course::findOrFail($courseId);
        $totalWeeks = (int)$course->duration_weeks;
        $halfWeeks = (int)ceil($totalWeeks / 2);

        // Clear previous viva data
        $this->clearVivaSession();

        // Get target weeks based on scope
        if ($scope === 'midterm') {
            $weeks = WeeklyContent::where('course_id', $courseId)
                ->whereBetween('week_number', [1, $halfWeeks])
                ->get();
        } else {
            $weeks = WeeklyContent::where('course_id', $courseId)
                ->whereBetween('week_number', [$halfWeeks + 1, $totalWeeks])
                ->get();
        }

        // Aggregate cached summaries
        $aggregatedContent = "";
        foreach ($weeks as $week) {
            $aggregatedContent .= "Week {$week->week_number} Lecture: {$week->video_title}\n";
            $aggregatedContent .= "Concepts taught: " . ($week->summary ?? 'N/A') . "\n\n";
        }

        try {
            // Ask first question
            $firstQuestion = $this->aiService->generateVivaQuestion($course->title, $aggregatedContent, "");

            if (empty($firstQuestion)) {
                throw new \Exception("Failed to generate the first question.");
            }

            // Save state in session
            Session::put('viva_scope', $scope);
            Session::put('viva_aggregated_content', $aggregatedContent);
            Session::put('viva_turns', 0);
            Session::put('viva_history', [
                ['role' => 'examiner', 'content' => $firstQuestion]
            ]);

            return response()->json([
                'success' => true,
                'question' => $firstQuestion
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error starting Viva session: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit user's voice/text response, proceed to next turn or generate final scorecard.
     */
    public function submit(Request $request, $courseId)
    {
        $validated = $request->validate([
            'answer' => 'required|string'
        ]);

        $studentAnswer = $validated['answer'];
        $course = Course::findOrFail($courseId);

        // Fetch session states
        $scope = Session::get('viva_scope');
        $aggregatedContent = Session::get('viva_aggregated_content');
        $turns = (int)Session::get('viva_turns', 0);
        $history = Session::get('viva_history', []);

        if (empty($aggregatedContent) || empty($history)) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired or not initialized.'
            ], 400);
        }

        // Append student answer to history
        $history[] = ['role' => 'student', 'content' => $studentAnswer];
        $turns++;

        // Formulate formatted chat transcript for the AI
        $historyText = "";
        foreach ($history as $chat) {
            $speaker = $chat['role'] === 'examiner' ? 'Examiner' : 'Student';
            $historyText .= "{$speaker}: {$chat['content']}\n";
        }

        try {
            if ($turns < 3) {
                // Generate next question
                $nextQuestion = $this->aiService->generateVivaQuestion($course->title, $aggregatedContent, $historyText);
                
                // Append next question to history
                $history[] = ['role' => 'examiner', 'content' => $nextQuestion];

                // Save updated session state
                Session::put('viva_turns', $turns);
                Session::put('viva_history', $history);

                return response()->json([
                    'success' => true,
                    'finished' => false,
                    'question' => $nextQuestion
                ]);
            } else {
                // Generate final performance evaluation
                $scorecard = $this->aiService->evaluateViva($course->title, $aggregatedContent, $historyText);

                if (!$scorecard) {
                    throw new \Exception("Evaluation failed to compile.");
                }

                // Clear session
                $this->clearVivaSession();

                return response()->json([
                    'success' => true,
                    'finished' => true,
                    'scorecard' => $scorecard
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'AI generation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset/Clear the session.
     */
    public function reset($courseId)
    {
        $this->clearVivaSession();
        return response()->json(['success' => true]);
    }

    /**
     * Transcribe uploaded audio file using Groq Whisper.
     */
    public function transcribe(Request $request, $courseId)
    {
        if (!$request->hasFile('audio')) {
            return response()->json([
                'success' => false,
                'message' => 'No audio file uploaded.'
            ], 400);
        }

        $file = $request->file('audio');
        if (!$file->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid audio file uploaded.'
            ], 400);
        }

        // Store temporarily
        $tempPath = $file->getRealPath();
        $extension = $file->getClientOriginalExtension() ?: 'webm';
        
        // Groq requires files to have a valid audio extension (like webm, wav, mp3, m4a, ogg, etc.)
        // $file->getRealPath() on Windows usually creates a file without extension (e.g. C:\Windows\Temp\phpA1B2.tmp)
        // Groq API may reject files that don't end with a supported audio extension.
        // Therefore, we must copy it to a path with a valid extension before sending it!
        $tempDir = sys_get_temp_dir();
        $customTempPath = $tempDir . DIRECTORY_SEPARATOR . 'viva_' . uniqid() . '.' . $extension;
        copy($tempPath, $customTempPath);

        try {
            $transcript = $this->aiService->transcribeAudio($customTempPath);
            
            // Clean up
            if (file_exists($customTempPath)) {
                unlink($customTempPath);
            }

            return response()->json([
                'success' => true,
                'transcript' => $transcript
            ]);
        } catch (\Exception $e) {
            // Clean up
            if (file_exists($customTempPath)) {
                unlink($customTempPath);
            }

            return response()->json([
                'success' => false,
                'message' => 'Transcription failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear helper.
     */
    private function clearVivaSession()
    {
        Session::forget('viva_scope');
        Session::forget('viva_aggregated_content');
        Session::forget('viva_turns');
        Session::forget('viva_history');
    }
}
