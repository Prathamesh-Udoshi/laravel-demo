<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\WeeklyContent;
use App\Models\QuizQuestion;
use App\Models\Assignment;
use App\Support\YouTubeService;
use App\Support\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    protected $youtubeService;
    protected $aiService;

    public function __construct(YouTubeService $youtubeService, AIService $aiService)
    {
        $this->youtubeService = $youtubeService;
        $this->aiService = $aiService;
    }

    /**
     * Display a listing of courses.
     */
    public function index()
    {
        $courses = Course::withCount('weeklyContents')->get();
        return view('courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        return view('courses.create');
    }

    /**
     * Store a newly created course in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_weeks' => 'required|in:4,8,12',
            'youtube_playlist_url' => 'nullable|url'
        ]);

        try {
            DB::beginTransaction();

            $course = Course::create($validated);

            $playlistVideos = [];
            if (!empty($validated['youtube_playlist_url'])) {
                $playlistVideos = $this->youtubeService->fetchPlaylistVideos($validated['youtube_playlist_url']);
            }

            $weeksCount = (int)$validated['duration_weeks'];

            if (!empty($playlistVideos)) {
                $totalVideos = count($playlistVideos);
                $videosPerWeek = (int)ceil($totalVideos / $weeksCount);

                foreach ($playlistVideos as $index => $videoData) {
                    $weekNumber = (int)floor($index / $videosPerWeek) + 1;
                    $weekNumber = min($weekNumber, $weeksCount);

                    WeeklyContent::create([
                        'course_id' => $course->id,
                        'week_number' => $weekNumber,
                        'video_title' => $videoData['title'],
                        'youtube_url' => $videoData['url'],
                        'summary' => null,
                    ]);
                }
            } else {
                for ($w = 1; $w <= $weeksCount; $w++) {
                    WeeklyContent::create([
                        'course_id' => $course->id,
                        'week_number' => $w,
                        'video_title' => "Lecture {$w} Placeholder",
                        'youtube_url' => null,
                        'summary' => null,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('courses.show', $course->id)
                ->with('success', 'Course successfully created and week contents auto-scaffolded!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to create course: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error creating course: ' . $e->getMessage());
        }
    }

    /**
     * Display the specific course syllabus and evaluations.
     */
    public function show($id)
    {
        $course = Course::with('weeklyContents')->findOrFail($id);

        $midtermQuiz = QuizQuestion::where('course_id', $id)->where('evaluation_type', 'midterm')->get();
        $finalQuiz = QuizQuestion::where('course_id', $id)->where('evaluation_type', 'final')->get();

        $midtermAssignment = Assignment::where('course_id', $id)->where('evaluation_type', 'midterm')->first();
        $finalAssignment = Assignment::where('course_id', $id)->where('evaluation_type', 'final')->first();

        return view('courses.show', compact('course', 'midtermQuiz', 'finalQuiz', 'midtermAssignment', 'finalAssignment'));
    }

    /**
     * AJAX Endpoint: Summarize a single week's lecture transcript.
     */
    public function processWeek(Request $request, $courseId, $weekNumber)
    {
        @set_time_limit(0);
        try {
            $lectures = WeeklyContent::where('course_id', $courseId)->where('week_number', $weekNumber)->orderBy('id', 'asc')->get();

            if ($lectures->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => "No lectures found for Week {$weekNumber}."
                ], 404);
            }

            foreach ($lectures as $lecture) {
                // Skip already summarized lectures
                if (!empty($lecture->summary)) {
                    continue;
                }

                $courseTitle = $lecture->course->title ?? null;
                $summary = $this->aiService->summarizeWeek($weekNumber, $lecture->video_title, $courseTitle);

                $lecture->update([
                    'summary' => $summary,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => "Week {$weekNumber} successfully processed."
            ]);

        } catch (\Exception $e) {
            Log::error("AJAX Error summarizing week {$weekNumber}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Failed to summarize Week {$weekNumber}: " . $e->getMessage()
            ], 500);
        }
    }

    /**
     * AJAX Endpoint: Generate Quiz (10 MCQs) and Assignment based on cached weekly summaries.
     */
    public function generateEvaluation(Request $request, $courseId)
    {
        $validated = $request->validate([
            'evaluation_type' => 'required|in:midterm,final'
        ]);

        $type = $validated['evaluation_type'];
        $course = Course::findOrFail($courseId);
        $totalWeeks = (int)$course->duration_weeks;
        $halfWeeks = (int)ceil($totalWeeks / 2);

        // Fetch targets weeks depending on midterm vs final
        if ($type === 'midterm') {
            $weeks = WeeklyContent::where('course_id', $courseId)
                ->whereBetween('week_number', [1, $halfWeeks])
                ->orderBy('week_number', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        } else {
            $weeks = WeeklyContent::where('course_id', $courseId)
                ->whereBetween('week_number', [$halfWeeks + 1, $totalWeeks])
                ->orderBy('week_number', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        }

        // Aggregate summaries
        $aggregatedContent = "";
        foreach ($weeks as $week) {
            $aggregatedContent .= "Week {$week->week_number} Lecture: {$week->video_title}\n";
            $aggregatedContent .= "Concepts taught: " . ($week->summary ?? 'N/A') . "\n\n";
        }

        try {
            DB::beginTransaction();

            // 1. Generate and Save Quizzes (10 MCQs)
            $questions = $this->aiService->generateQuiz($course->title, $type, $aggregatedContent);
            if (!$questions || count($questions) < 1) {
                throw new \Exception("AI Quiz Generator failed to return formatted questions.");
            }

            // Remove existing quizzes of this type
            QuizQuestion::where('course_id', $courseId)->where('evaluation_type', $type)->delete();

            foreach ($questions as $q) {
                QuizQuestion::create([
                    'course_id' => $courseId,
                    'evaluation_type' => $type,
                    'question_text' => $q['question'],
                    'option_a' => $q['A'],
                    'option_b' => $q['B'],
                    'option_c' => $q['C'],
                    'option_d' => $q['D'],
                    'correct_option' => $q['correct'],
                    'explanation' => $q['explanation'] ?? null
                ]);
            }

            // 2. Generate and Save Assignment
            $assignmentData = $this->aiService->generateAssignment($course->title, $type, $aggregatedContent);
            if (!$assignmentData) {
                throw new \Exception("AI Assignment Generator failed.");
            }

            // Remove existing assignment of this type
            Assignment::where('course_id', $courseId)->where('evaluation_type', $type)->delete();

            $instructions = $assignmentData['instructions'];
            if (is_array($instructions)) {
                $instructions = implode("\n\n", array_map(function($item) {
                    return is_array($item) ? json_encode($item) : (string)$item;
                }, $instructions));
            }

            Assignment::create([
                'course_id' => $courseId,
                'evaluation_type' => $type,
                'assignment_title' => $assignmentData['title'],
                'instructions' => $instructions
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Successfully generated Quiz (10 MCQs) and Assignment for the " . ucfirst($type) . " Evaluation!"
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error generating evaluation for course {$courseId}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Failed to generate evaluation: " . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show edit MCQ form.
     */
    public function editQuestion($id)
    {
        $question = QuizQuestion::findOrFail($id);
        return view('courses.edit_question', compact('question'));
    }

    /**
     * Update an MCQ.
     */
    public function updateQuestion(Request $request, $id)
    {
        $question = QuizQuestion::findOrFail($id);

        $validated = $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_option' => 'required|in:A,B,C,D',
            'explanation' => 'nullable|string'
        ]);

        $question->update($validated);

        return redirect()->route('courses.show', $question->course_id)
            ->with('success', 'Quiz question successfully updated!');
    }

    /**
     * Delete an MCQ.
     */
    public function deleteQuestion($id)
    {
        $question = QuizQuestion::findOrFail($id);
        $courseId = $question->course_id;
        $question->delete();

        return redirect()->route('courses.show', $courseId)
            ->with('success', 'Question deleted successfully.');
    }

    /**
     * Export the complete course syllabus, weekly contents, quizzes and assignments as JSON.
     */
    public function exportCourse($id)
    {
        $course = Course::with(['weeklyContents', 'quizQuestions', 'assignments'])->findOrFail($id);
        
        $filename = strtolower(str_replace(' ', '_', $course->title)) . "_course_package.json";
        
        return response()->json($course)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Delete the course and all associated contents.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $course = Course::findOrFail($id);

            // 1. Detach students
            $course->students()->detach();

            // 2. Delete assignments
            $course->assignments()->delete();

            // 3. Delete quiz questions
            $course->quizQuestions()->delete();

            // 4. Delete weekly content chunks and weekly contents
            foreach ($course->weeklyContents as $content) {
                $content->chunks()->delete();
                $content->delete();
            }

            // 5. Delete the course itself
            $course->delete();

            DB::commit();

            return redirect()->route('courses.index')
                ->with('success', 'Course and all associated weekly contents, assessments, and chunks successfully deleted!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete course {$id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting course: ' . $e->getMessage());
        }
    }
}
