<x-layout>
    <x-slot:title>{{ $course->title }} - Workspace</x-slot>

    <!-- Header Section -->
    <div style="padding: 3rem; background: #ffffff; border-bottom: 2px solid var(--border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 2rem;">
        <div>
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                <a href="{{ route('courses.index') }}" style="color: var(--text-muted); text-decoration: none; display: flex; align-items: center; font-weight: 600;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.25rem;">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Syllabus Library
                </a>
            </div>
            <h2 style="font-size: 2.5rem; margin-bottom: 0.5rem; letter-spacing: -0.05em; color: var(--text-main); font-weight: 800;">
                {{ $course->title }}
            </h2>
            <p style="font-size: 1rem; font-weight: 500; color: var(--text-muted); margin-bottom: 0;">
                {{ $course->duration_weeks }} Weeks Course Structure • Managed by Edutainer
            </p>
        </div>

        <div style="display: flex; gap: 1rem;">
            <a href="{{ route('courses.export', $course->id) }}" class="btn" style="background: var(--bg-main); border: 2px solid var(--border); color: var(--text-main); padding: 0.75rem 1.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem; vertical-align: middle;">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Export Course package (JSON)
            </a>
        </div>
    </div>

    <!-- Main Workspace Grid -->
    <div style="display: grid; grid-template-columns: 380px 1fr; min-height: calc(100vh - 180px); background: #f8fafc;">
        
        <!-- Left Panel: Weekly Playlist Content -->
        <div style="background: #ffffff; border-right: 2px solid var(--border); overflow-y: auto; padding: 2rem; max-height: calc(100vh - 180px);">
            <h3 style="font-size: 1.1rem; text-transform: uppercase; color: var(--primary); font-weight: 800; letter-spacing: 0.05em; margin-bottom: 1.5rem; border-bottom: 2px solid var(--border); padding-bottom: 0.75rem;">
                Weekly Lecture Contents
            </h3>
            
            @php
                $groupedWeeks = $course->weeklyContents->groupBy('week_number');
            @endphp

            <div style="display: grid; gap: 1.5rem;">
                @foreach($groupedWeeks as $weekNum => $lectures)
                    <div style="padding: 1.25rem; border: 2px solid var(--border); background: #ffffff; position: relative; padding-top: 1.75rem; border-top: 4px solid var(--primary);">
                        <span style="font-size: 0.75rem; font-weight: 800; background: var(--primary); color: white; padding: 0.25rem 0.75rem; position: absolute; top: -10px; left: 15px; text-transform: uppercase; letter-spacing: 0.05em;">
                            Week {{ $weekNum }}
                        </span>

                        <div style="display: grid; gap: 1.25rem; margin-top: 0.5rem;">
                            @foreach($lectures as $index => $lecture)
                                <div style="padding-bottom: 0.75rem; {{ !$loop->last ? 'border-bottom: 1px dashed var(--border);' : '' }}">
                                    <h4 style="font-size: 0.95rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.35rem; line-height: 1.3;">
                                        Lecture {{ $weekNum }}.{{ $index + 1 }}: {{ $lecture->video_title }}
                                    </h4>

                                    @if(!empty($lecture->youtube_url))
                                        <a href="{{ $lecture->youtube_url }}" target="_blank" style="font-size: 0.75rem; color: #ef4444; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; margin-bottom: 0.5rem;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.25rem;">
                                                <path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path>
                                                <polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon>
                                            </svg>
                                            Watch Video
                                        </a>
                                    @endif

                                    @if(!empty($lecture->summary))
                                        <div style="font-size: 0.75rem; color: var(--text-muted); background: #f8fafc; border: 1px solid var(--border); padding: 0.5rem 0.75rem; line-height: 1.4; border-radius: 4px;">
                                            {{ $lecture->summary }}
                                        </div>
                                    @else
                                        <span style="font-size: 0.7rem; font-weight: 700; color: #b45309; background: #fffbeb; border: 1px solid #fef3c7; padding: 0.15rem 0.4rem; display: inline-block;">
                                            ⏳ Pending Auto-Analysis
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Right Panel: Evaluations Hub -->
        <div style="padding: 3rem; overflow-y: auto; max-height: calc(100vh - 180px);">
            
            @if(session('success'))
                <div style="background: #ecfdf5; border: 2px solid #10b981; padding: 1.25rem; color: #065f46; font-weight: 700; margin-bottom: 2rem;">
                    {{ session('success') }}
                </div>
            @endif

            <div style="display: grid; grid-template-columns: 1fr; gap: 3rem;">
                
                <!-- 1. Mid-Term Evaluation Card -->
                <div class="card" style="margin-bottom: 0; background: #ffffff; border: 2px solid var(--border); padding: 3rem; position: relative;">
                    <div style="position: absolute; top: -12px; left: 30px; font-size: 0.8rem; font-weight: 800; background: var(--primary); color: white; padding: 0.35rem 1rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        Assessment 1: Mid-Term Evaluation
                    </div>

                    <p style="color: var(--text-muted); margin-bottom: 2rem; font-size: 1rem; line-height: 1.5;">
                        Covers the first 50% of the course lectures (Weeks 1 to {{ (int)ceil($course->duration_weeks / 2) }}). Includes a comprehensive Quiz (10 MCQs) and a structured descriptive assignment task.
                    </p>

                    @if($midtermQuiz->isEmpty())
                        <div style="text-align: center; padding: 3rem 1.5rem; border: 2px dashed var(--border); background: #f8fafc;">
                            <h4 style="font-size: 1.2rem; color: var(--text-main); margin-bottom: 0.5rem; font-weight: 700;">No Mid-Term Assessments Generated</h4>
                            <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1.5rem; max-width: 450px; margin-left: auto; margin-right: auto;">
                                Press the button below to fetch all week transcripts sequentially and run the zero-compute AI planner to build your Mid-Term Quiz and Assignment package.
                            </p>
                            <button onclick="triggerGeneration('midterm', {{ json_encode($course->weeklyContents->pluck('week_number')->unique()->take(ceil($course->duration_weeks / 2))->values()) }})" class="btn" style="background: linear-gradient(135deg, var(--primary), var(--accent));">
                                Generate Mid-Term Assessments
                            </button>
                        </div>
                    @else
                        <!-- Midterm Assessments Desk -->
                        <div style="display: grid; gap: 2.5rem;">
                            
                            <!-- Mid-Term Assignment View -->
                            <div style="border-left: 4px solid var(--accent); padding-left: 1.5rem;">
                                <h4 style="font-size: 1.25rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.5rem;">
                                    📝 Subjective Assignment: {{ $midtermAssignment->assignment_title ?? 'Comprehensive Assignment' }}
                                </h4>
                                <div style="font-size: 0.95rem; color: var(--text-main); line-height: 1.6; white-space: pre-line; background: #f8fafc; padding: 1.25rem; border: 1px solid var(--border);">
                                    {!! $midtermAssignment->instructions ?? '' !!}
                                </div>
                            </div>

                            <!-- Mid-Term Quiz View -->
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                                    <h4 style="font-size: 1.25rem; font-weight: 800; color: var(--text-main); margin-bottom: 0;">
                                        🎓 Exam Quiz: Mid-Term Question Book (10 MCQs)
                                    </h4>
                                    <button onclick="triggerGeneration('midterm', {{ json_encode($course->weeklyContents->take(ceil($course->duration_weeks / 2))->pluck('week_number')) }})" class="btn" style="background: var(--bg-main); border: 2px solid var(--border); color: var(--text-main); padding: 0.5rem 1rem; font-size: 0.85rem;">
                                        Regenerate via AI
                                    </button>
                                </div>

                                <!-- MCQs list -->
                                <div style="display: grid; gap: 1.5rem;">
                                    @foreach($midtermQuiz as $index => $q)
                                        <div style="padding: 1.5rem; border: 2px solid var(--border); background: #ffffff; position: relative;">
                                            
                                            <!-- CRUD Controls -->
                                            <div style="position: absolute; right: 1rem; top: 1rem; display: flex; gap: 0.5rem;">
                                                <a href="{{ route('questions.edit', $q->id) }}" style="padding: 0.4rem 0.8rem; font-size: 0.75rem; font-weight: 700; border: 2px solid var(--border); background: #ffffff; text-decoration: none; color: var(--text-main);">
                                                    Edit
                                                </a>
                                                <form action="{{ route('questions.destroy', $q->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this question?');" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" style="padding: 0.4rem 0.8rem; font-size: 0.75rem; font-weight: 700; border: 2px solid #ef4444; background: #ffffff; color: #ef4444;">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>

                                            <div style="font-weight: 800; color: var(--primary); font-size: 0.9rem; margin-bottom: 0.5rem; text-transform: uppercase;">
                                                Question {{ $index + 1 }}
                                            </div>

                                            <h5 style="font-size: 1.05rem; font-weight: 800; color: var(--text-main); margin-bottom: 1rem; max-width: 80%; line-height: 1.4;">
                                                {{ $q->question_text }}
                                            </h5>

                                            <!-- Options Grid -->
                                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem;">
                                                @foreach(['A' => 'option_a', 'B' => 'option_b', 'C' => 'option_c', 'D' => 'option_d'] as $key => $col)
                                                    <div style="padding: 0.75rem 1rem; border: 1px solid var(--border); font-size: 0.9rem; {{ $q->correct_option === $key ? 'background: #ecfdf5; border-color: #10b981; font-weight: 700; color: #065f46;' : 'background: #ffffff;' }}">
                                                        <span style="font-weight: 800; margin-right: 0.5rem;">{{ $key }}.</span> {{ $q->$col }}
                                                    </div>
                                                @endforeach
                                            </div>

                                            @if(!empty($q->explanation))
                                                <div style="font-size: 0.85rem; color: #0f172a; background: #f1f5f9; padding: 0.75rem 1rem; border-left: 3px solid var(--primary); line-height: 1.4;">
                                                    <strong>Answer Logic:</strong> {{ $q->explanation }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- 2. End-Term Evaluation Card -->
                <div class="card" style="margin-bottom: 0; background: #ffffff; border: 2px solid var(--border); padding: 3rem; position: relative;">
                    <div style="position: absolute; top: -12px; left: 30px; font-size: 0.8rem; font-weight: 800; background: var(--accent); color: white; padding: 0.35rem 1rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        Assessment 2: End-Term Evaluation
                    </div>

                    <p style="color: var(--text-muted); margin-bottom: 2rem; font-size: 1rem; line-height: 1.5;">
                        Covers the second 50% of the course lectures (Weeks {{ (int)ceil($course->duration_weeks / 2) + 1 }} to {{ $course->duration_weeks }}). Includes a cumulative Quiz (10 MCQs) and a comprehensive descriptive assignment.
                    </p>

                    @if($finalQuiz->isEmpty())
                        <div style="text-align: center; padding: 3rem 1.5rem; border: 2px dashed var(--border); background: #f8fafc;">
                            <h4 style="font-size: 1.2rem; color: var(--text-main); margin-bottom: 0.5rem; font-weight: 700;">No End-Term Assessments Generated</h4>
                            <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1.5rem; max-width: 450px; margin-left: auto; margin-right: auto;">
                                Press the button below to fetch all week transcripts sequentially and run the zero-compute AI planner to build your cumulative End-Term assessments.
                            </p>
                            <button onclick="triggerGeneration('final', {{ json_encode($course->weeklyContents->pluck('week_number')->unique()->slice(ceil($course->duration_weeks / 2))->values()) }})" class="btn" style="background: linear-gradient(135deg, var(--accent), var(--primary));">
                                Generate End-Term Assessments
                            </button>
                        </div>
                    @else
                        <!-- Endterm Assessments Desk -->
                        <div style="display: grid; gap: 2.5rem;">
                            
                            <!-- End-Term Assignment View -->
                            <div style="border-left: 4px solid var(--primary); padding-left: 1.5rem;">
                                <h4 style="font-size: 1.25rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.5rem;">
                                    📝 Subjective Assignment: {{ $finalAssignment->assignment_title ?? 'Comprehensive Assignment' }}
                                </h4>
                                <div style="font-size: 0.95rem; color: var(--text-main); line-height: 1.6; white-space: pre-line; background: #f8fafc; padding: 1.25rem; border: 1px solid var(--border);">
                                    {!! $finalAssignment->instructions ?? '' !!}
                                </div>
                            </div>

                            <!-- End-Term Quiz View -->
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                                    <h4 style="font-size: 1.25rem; font-weight: 800; color: var(--text-main); margin-bottom: 0;">
                                        🎓 Exam Quiz: End-Term Question Book (10 MCQs)
                                    </h4>
                                    <button onclick="triggerGeneration('final', {{ json_encode($course->weeklyContents->slice(ceil($course->duration_weeks / 2))->pluck('week_number')->values()) }})" class="btn" style="background: var(--bg-main); border: 2px solid var(--border); color: var(--text-main); padding: 0.5rem 1rem; font-size: 0.85rem;">
                                        Regenerate via AI
                                    </button>
                                </div>

                                <!-- MCQs list -->
                                <div style="display: grid; gap: 1.5rem;">
                                    @foreach($finalQuiz as $index => $q)
                                        <div style="padding: 1.5rem; border: 2px solid var(--border); background: #ffffff; position: relative;">
                                            
                                            <!-- CRUD Controls -->
                                            <div style="position: absolute; right: 1rem; top: 1rem; display: flex; gap: 0.5rem;">
                                                <a href="{{ route('questions.edit', $q->id) }}" style="padding: 0.4rem 0.8rem; font-size: 0.75rem; font-weight: 700; border: 2px solid var(--border); background: #ffffff; text-decoration: none; color: var(--text-main);">
                                                    Edit
                                                </a>
                                                <form action="{{ route('questions.destroy', $q->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this question?');" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" style="padding: 0.4rem 0.8rem; font-size: 0.75rem; font-weight: 700; border: 2px solid #ef4444; background: #ffffff; color: #ef4444;">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>

                                            <div style="font-weight: 800; color: var(--accent); font-size: 0.9rem; margin-bottom: 0.5rem; text-transform: uppercase;">
                                                Question {{ $index + 1 }}
                                            </div>

                                            <h5 style="font-size: 1.05rem; font-weight: 800; color: var(--text-main); margin-bottom: 1rem; max-width: 80%; line-height: 1.4;">
                                                {{ $q->question_text }}
                                            </h5>

                                            <!-- Options Grid -->
                                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem;">
                                                @foreach(['A' => 'option_a', 'B' => 'option_b', 'C' => 'option_c', 'D' => 'option_d'] as $key => $col)
                                                    <div style="padding: 0.75rem 1rem; border: 1px solid var(--border); font-size: 0.9rem; {{ $q->correct_option === $key ? 'background: #ecfdf5; border-color: #10b981; font-weight: 700; color: #065f46;' : 'background: #ffffff;' }}">
                                                        <span style="font-weight: 800; margin-right: 0.5rem;">{{ $key }}.</span> {{ $q->$col }}
                                                    </div>
                                                @endforeach
                                            </div>

                                            @if(!empty($q->explanation))
                                                <div style="font-size: 0.85rem; color: #0f172a; background: #f1f5f9; padding: 0.75rem 1rem; border-left: 3px solid var(--accent); line-height: 1.4;">
                                                    <strong>Answer Logic:</strong> {{ $q->explanation }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>

    </div>

    <!-- AI Active Pipeline Modal Overlay -->
    <div id="ai-overlay" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px); z-index: 1000; display: none; align-items: center; justify-content: center;">
        <div style="background: #ffffff; border: 2px solid var(--border); padding: 3rem; max-width: 550px; width: 90%; max-height: 80vh; display: flex; flex-direction: column;">
            
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                <!-- Spinning Loader -->
                <div style="width: 32px; height: 32px; border: 4px solid var(--primary); border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                <h3 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 0; color: var(--text-main);">Edutainer AI Syllabus Analyzer</h3>
            </div>

            <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1.5rem; line-height: 1.4; border-bottom: 1px solid var(--border); padding-bottom: 1rem;">
                Fetching pre-transcribed transcripts sequentially from YouTube and extracting weekly core lecture concepts. This consumes 0% local server compute.
            </p>

            <div id="ai-status-list" style="background: #f8fafc; border: 1px solid var(--border); padding: 1.5rem; font-family: monospace; font-size: 0.85rem; color: var(--text-main); overflow-y: auto; flex-grow: 1; min-height: 150px; line-height: 1.6;">
                <!-- Real-time logs added here -->
            </div>
        </div>
    </div>

    <style>
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>

    <script>
        async function triggerGeneration(type, weeksArray) {
            if (!confirm(`Are you sure you want to run the AI generator to create assessments for the ${type} evaluation?`)) {
                return;
            }

            document.getElementById('ai-overlay').style.display = 'flex';
            const logBox = document.getElementById('ai-status-list');
            logBox.innerHTML = '';

            // 1. Process week-by-week summaries sequentially
            for (const wk of weeksArray) {
                const row = document.createElement('div');
                row.style.marginBottom = '0.5rem';
                row.innerHTML = `⏳ Analyzing Lecture Week ${wk}...`;
                logBox.appendChild(row);
                logBox.scrollTop = logBox.scrollHeight;

                try {
                    const response = await fetch(`/courses/{{ $course->id }}/process-week/${wk}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        row.innerHTML = `✅ Week ${wk} Syllabus Summary Cached.`;
                        row.style.color = '#10b981';
                    } else {
                        throw new Error(data.message);
                    }
                } catch (err) {
                    row.innerHTML = `❌ Week ${wk} failed: ${err.message}`;
                    row.style.color = '#ef4444';
                    
                    const errorMsg = document.createElement('div');
                    errorMsg.style.fontWeight = 'bold';
                    errorMsg.style.marginTop = '1rem';
                    errorMsg.style.color = '#ef4444';
                    errorMsg.innerHTML = `⚠️ Generation aborted due to errors.`;
                    logBox.appendChild(errorMsg);
                    return;
                }
            }

            // 2. Trigger final comprehensive evaluations generation
            const finalRow = document.createElement('div');
            finalRow.style.fontWeight = 'bold';
            finalRow.style.marginTop = '1rem';
            finalRow.innerHTML = `🔮 Generating final Quiz (10 MCQs) and Assignment...`;
            logBox.appendChild(finalRow);
            logBox.scrollTop = logBox.scrollHeight;

            try {
                const response = await fetch(`/courses/{{ $course->id }}/generate-evaluation`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        evaluation_type: type
                    })
                });

                const data = await response.json();

                if (data.success) {
                    finalRow.innerHTML = `🎉 Mid-Term / Final Assessments Generated Successfully!`;
                    finalRow.style.color = '#10b981';
                    
                    const reloadRow = document.createElement('div');
                    reloadRow.innerHTML = `🔄 Refreshing Workspace...`;
                    logBox.appendChild(reloadRow);
                    
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    throw new Error(data.message);
                }
            } catch (err) {
                finalRow.innerHTML = `❌ Comprehensive generation failed: ${err.message}`;
                finalRow.style.color = '#ef4444';
            }
        }
    </script>
</x-layout>
