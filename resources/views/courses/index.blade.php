<x-layout>
    <x-slot:title>Course Library - Edutainer AI</x-slot>

    <!-- Header Section -->
    <div style="padding: 4rem 3rem; background: #ffffff; border-bottom: 2px solid var(--border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 2rem;">
        <div>
            <h2 style="font-size: 3rem; margin-bottom: 0.5rem; letter-spacing: -0.05em; color: var(--text-main);">Course Assessment Planner</h2>
            <p style="font-size: 1.1rem; font-weight: 500; color: var(--text-muted);">
                Generate comprehensive Mid-Term & End-Term evaluations from YouTube playlists instantly.
            </p>
        </div>
        <a href="{{ route('courses.create') }}" class="btn" style="padding: 1.25rem 2.5rem; background: linear-gradient(135deg, var(--primary), var(--accent)); box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2); transition: all 0.2s;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem; vertical-align: middle;">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Import Playlist Course
        </a>
    </div>

    <!-- Main Container -->
    <div class="container animate-fade-in" style="background: transparent; border: none; padding: 3rem;">
        
        @if(session('success'))
            <div style="background: #ecfdf5; border: 2px solid #10b981; padding: 1.25rem; color: #065f46; font-weight: 700; margin-bottom: 2rem;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #fef2f2; border: 2px solid #ef4444; padding: 1.25rem; color: #991b1b; font-weight: 700; margin-bottom: 2rem;">
                {{ session('error') }}
            </div>
        @endif

        @if($courses->isEmpty())
            <div style="text-align: center; padding: 6rem 3rem; background: #ffffff; border: 2px solid var(--border); max-width: 800px; margin: 0 auto;">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 1.5rem;">
                    <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"></path>
                    <path d="M6 6h10"></path>
                    <path d="M6 10h10"></path>
                </svg>
                <h3 style="font-size: 1.5rem; color: var(--text-main); margin-bottom: 0.5rem;">No Courses Scaffolded Yet</h3>
                <p style="color: var(--text-muted); margin-bottom: 2rem; max-width: 500px; margin-left: auto; margin-right: auto;">
                    Edutainer coordinates hundreds of VTU Online curriculum pages. Paste a single NPTEL or lecture playlist URL to auto-generate weekly schedules, exams, and assignments.
                </p>
                <a href="{{ route('courses.create') }}" class="btn">Import Your First Playlist</a>
            </div>
        @else
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 2rem;">
                @foreach($courses as $course)
                    <div class="card" style="margin-bottom: 0; padding: 0; display: flex; flex-direction: column; height: 100%; border: 2px solid var(--border); transition: all 0.2s; position: relative; background: #ffffff;">
                        
                        <!-- Top Accent Banner -->
                        <div style="height: 6px; background: linear-gradient(90deg, var(--primary), var(--accent));"></div>

                        <!-- Card Body -->
                        <div style="padding: 2rem; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                    <span style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; background: #f1f5f9; color: var(--text-muted); padding: 0.25rem 0.75rem; border: 1px solid var(--border);">
                                        {{ $course->duration_weeks }} Weeks Course
                                    </span>
                                    <span style="font-size: 0.8rem; font-weight: 700; color: var(--primary);">
                                        {{ $course->weekly_contents_count }} Videos Linked
                                    </span>
                                </div>

                                <h3 style="font-size: 1.5rem; margin-bottom: 0.75rem; color: var(--text-main); line-height: 1.25; font-weight: 800;">
                                    <a href="{{ route('courses.show', $course->id) }}" style="color: inherit; text-decoration: none;">
                                        {{ $course->title }}
                                    </a>
                                </h3>

                                <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 2rem; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.5;">
                                    {{ $course->description ?? 'No course description provided. Set up video content to generate evaluations.' }}
                                </p>
                            </div>

                            <div style="border-top: 1px solid var(--border); padding-top: 1.5rem; display: flex; gap: 0.75rem; width: 100%;">
                                <a href="{{ route('courses.show', $course->id) }}" class="btn" style="padding: 0.75rem 1.5rem; flex-grow: 1; text-align: center; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
                                    Open Workspace Dashboard
                                </a>
                                <form action="{{ route('courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this course and all its contents?');" style="display: inline-flex;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" style="padding: 0.75rem 1rem; display: inline-flex; align-items: center; justify-content: center; height: 100%;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layout>
