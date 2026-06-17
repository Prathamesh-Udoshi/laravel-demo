<x-layout>
    <x-slot:title>AI Email Agent Control Center</x-slot>

    <!-- Header -->
    <div style="padding: 4rem 3rem; background: #ffffff; border-bottom: 1px solid var(--border);">
        <div style="max-width: 1200px; margin: 0 auto;">
            <span style="font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: var(--primary); letter-spacing: 0.1em; display: block; margin-bottom: 0.75rem;">AI Student Engagement Engine</span>
            <h2 style="font-size: 3rem; margin-bottom: 0.5rem; letter-spacing: -0.05em; font-family: 'Outfit', sans-serif; font-weight: 900; color: var(--text-main);">AI Email Reminder Agent</h2>
            <p style="font-size: 1.1rem; font-weight: 500; color: var(--text-muted); max-width: 800px;">
                Manage students registered across courses, track progress, and trigger AI-generated, progress-sensitive email reminders.
            </p>
        </div>
    </div>

    <!-- Main Container -->
    <div style="max-width: 1200px; margin: 2rem auto 4rem auto; padding: 0 3rem; display: grid; grid-template-columns: 1fr 2fr; gap: 3rem; align-items: start;">
        
        <!-- Left: Registration Panel -->
        <div class="card" style="background: white; border: 2px solid var(--border); padding: 2rem;">
            <h3 style="font-family: 'Outfit', sans-serif; font-weight: 800; margin-top: 0; margin-bottom: 1.5rem; color: var(--text-main); font-size: 1.25rem; border-bottom: 1.5px solid var(--border); padding-bottom: 0.5rem;">
                Register Student
            </h3>

            @if(session('success'))
                <div style="background: #d1fae5; border-left: 4px solid #10b981; color: #065f46; padding: 0.75rem 1rem; font-size: 0.875rem; font-weight: 600; margin-bottom: 1.5rem;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="background: #fee2e2; border-left: 4px solid #ef4444; color: #991b1b; padding: 0.75rem 1rem; font-size: 0.875rem; font-weight: 600; margin-bottom: 1.5rem;">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('email-agent.enroll') }}" method="POST" style="display: flex; flex-direction: column; gap: 1.25rem;">
                @csrf
                <div>
                    <label style="font-weight: 700; font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); display: block; margin-bottom: 0.5rem;">Select Student</label>
                    <select name="student_id" style="width: 100%; padding: 0.75rem; border: 1.5px solid var(--border); font-weight: 600;" required>
                        @foreach($students as $st)
                            <option value="{{ $st->id }}">{{ $st->name }} ({{ $st->class }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="font-weight: 700; font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); display: block; margin-bottom: 0.5rem;">Select Course</label>
                    <select name="course_id" style="width: 100%; padding: 0.75rem; border: 1.5px solid var(--border); font-weight: 600;" required>
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}">{{ $c->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="font-weight: 700; font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); display: block; margin-bottom: 0.5rem;">Student Email Address</label>
                    <input type="email" name="email" placeholder="e.g. alice@example.com" style="width: 100%; padding: 0.75rem; border: 1.5px solid var(--border); font-weight: 600;">
                </div>

                <div>
                    <label style="font-weight: 700; font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); display: block; margin-bottom: 0.5rem;">Course Progress (%)</label>
                    <input type="number" name="progress_percent" min="0" max="100" value="35" style="width: 100%; padding: 0.75rem; border: 1.5px solid var(--border); font-weight: 600;" required>
                </div>

                <button type="submit" style="background: var(--primary); color: white; border: none; padding: 0.85rem; font-weight: 700; cursor: pointer; text-align: center;">
                    Add/Update Registration
                </button>
            </form>
        </div>

        <!-- Right: Registered Students Directory & Reminders -->
        <div class="card" style="background: white; border: 2px solid var(--border); padding: 2rem;">
            <h3 style="font-family: 'Outfit', sans-serif; font-weight: 800; margin-top: 0; margin-bottom: 1.5rem; color: var(--text-main); font-size: 1.25rem; border-bottom: 1.5px solid var(--border); padding-bottom: 0.5rem;">
                Active Enrollments & AI Reminder Prompts
            </h3>

            @php $hasEnrollments = false; @endphp
            @foreach($courses as $course)
                @if($course->students->isNotEmpty())
                    @php $hasEnrollments = true; @endphp
                    <div style="margin-bottom: 2rem;">
                        <h4 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: var(--primary); font-size: 1.1rem; margin-bottom: 1rem;">
                            📚 {{ $course->title }}
                        </h4>
                        
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            @foreach($course->students as $student)
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; border: 1px solid var(--border); background: #f8fafc; border-radius: 4px;">
                                    <div>
                                        <div style="font-weight: 800; color: var(--text-main); font-size: 0.95rem;">{{ $student->name }}</div>
                                        <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 500;">
                                            Email: {{ $student->email ?? 'N/A (Update to send mail)' }}
                                        </div>
                                        <div style="margin-top: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                                            <span style="font-size: 0.75rem; background: #eef2ff; color: var(--primary); padding: 0.2rem 0.5rem; font-weight: 700; border-radius: 3px;">
                                                Progress: {{ $student->pivot->progress_percent }}%
                                            </span>
                                            @if($student->pivot->last_reminded_at)
                                                <span style="font-size: 0.75rem; background: #f1f5f9; color: var(--text-muted); padding: 0.2rem 0.5rem; font-weight: 600; border-radius: 3px;">
                                                    Last Reminded: {{ \Carbon\Carbon::parse($student->pivot->last_reminded_at)->diffForHumans() }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <form action="{{ route('email-agent.send-reminder') }}" method="POST" onsubmit="this.submitBtn.disabled=true; this.submitBtn.textContent='Compiling Email...';">
                                            @csrf
                                            <input type="hidden" name="student_id" value="{{ $student->id }}">
                                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                                            <button type="submit" name="submitBtn" style="background: var(--text-main); color: white; border: none; padding: 0.5rem 1rem; font-size: 0.8rem; font-weight: 700; cursor: pointer; border-radius: 3px; display: inline-flex; align-items: center; gap: 0.25rem;">
                                                ⚡ Trigger Email Agent
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

            @if(!$hasEnrollments)
                <p style="font-style: italic; color: var(--text-muted); margin: 0; font-size: 0.9rem; text-align: center; padding: 3rem 0;">
                    No student registrations recorded yet. Use the panel on the left to register a student in a course!
                </p>
            @endif
        </div>

    </div>
</x-layout>
