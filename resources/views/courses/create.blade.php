<x-layout>
    <x-slot:title>Import Playlist - Edutainer AI</x-slot>

    <!-- Header Section -->
    <div style="padding: 4rem 3rem; background: #ffffff; border-bottom: 2px solid var(--border);">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
            <a href="{{ route('courses.index') }}" style="color: var(--text-muted); text-decoration: none; display: flex; align-items: center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.25rem;">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to Library
            </a>
        </div>
        <h2 style="font-size: 3rem; margin-bottom: 0.5rem; letter-spacing: -0.05em; color: var(--text-main);">Create Course from YouTube Playlist</h2>
        <p style="font-size: 1.1rem; font-weight: 500; color: var(--text-muted);">
            Instantly import a syllabus structure and auto-scaffold weeks from a single link.
        </p>
    </div>

    <!-- Main Container -->
    <div class="container animate-fade-in" style="background: transparent; border: none; padding: 3rem; max-width: 800px; margin: 0 auto;">
        
        <div class="card" style="padding: 3rem; background: #ffffff; border: 2px solid var(--border);">
            
            <form method="POST" action="{{ route('courses.store') }}" style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">
                @csrf

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: var(--primary); margin-bottom: 0.5rem; display: block;">Course Title</label>
                    <input type="text" name="title" placeholder="e.g., VTU: Object Oriented Programming with Java" required value="{{ old('title') }}">
                    @error('title')
                        <p style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: var(--primary); margin-bottom: 0.5rem; display: block;">Syllabus Course Duration</label>
                    <select name="duration_weeks" required style="width: 100%; padding: 1rem 1.25rem; background: #ffffff; border: 2px solid var(--border); border-radius: 0; color: var(--text-main); font-size: 1rem;">
                        <option value="" disabled selected>Select weeks duration...</option>
                        <option value="4" {{ old('duration_weeks') == '4' ? 'selected' : '' }}>4 Weeks (Short Term / NPTEL Course)</option>
                        <option value="8" {{ old('duration_weeks') == '8' ? 'selected' : '' }}>8 Weeks (Standard NPTEL Course)</option>
                        <option value="12" {{ old('duration_weeks') == '12' ? 'selected' : '' }}>12 Weeks (Full Semester Course)</option>
                    </select>
                    @error('duration_weeks')
                        <p style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: var(--primary); margin-bottom: 0.5rem; display: block;">YouTube Playlist URL (Optional)</label>
                    <input type="url" name="youtube_playlist_url" placeholder="e.g., https://www.youtube.com/playlist?list=..." value="{{ old('youtube_playlist_url') }}">
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.5rem; line-height: 1.4;">
                        💡 <strong>Highly Recommended:</strong> Paste an NPTEL or YouTube course playlist URL. The system will parse all lectures in sequence, auto-populate week titles, and link the video players instantly!
                    </p>
                    @error('youtube_playlist_url')
                        <p style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: var(--primary); margin-bottom: 0.5rem; display: block;">Course Description / Objectives</label>
                    <textarea name="description" placeholder="Summarize what students will learn..." rows="4">{{ old('description') }}</textarea>
                    @error('description')
                        <p style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn" style="padding: 1.5rem; background: linear-gradient(135deg, var(--primary), var(--accent)); box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2); font-size: 1.1rem; width: 100%;">
                    Scaffold & Import Course Structure
                </button>

            </form>

        </div>

    </div>
</x-layout>
