<x-layout>
    <x-slot:title>Edit MCQ - Edutainer AI</x-slot>

    <!-- Header Section -->
    <div style="padding: 4rem 3rem; background: #ffffff; border-bottom: 2px solid var(--border);">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
            <a href="{{ route('courses.show', $question->course_id) }}" style="color: var(--text-muted); text-decoration: none; display: flex; align-items: center; font-weight: 600;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.25rem;">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Cancel & Return
            </a>
        </div>
        <h2 style="font-size: 3rem; margin-bottom: 0.5rem; letter-spacing: -0.05em; color: var(--text-main); font-weight: 800;">Refine Generated Question</h2>
        <p style="font-size: 1.1rem; font-weight: 500; color: var(--text-muted);">
            Manually edit the MCQ text, options, correct selection, and explanation.
        </p>
    </div>

    <!-- Main Container -->
    <div class="container animate-fade-in" style="background: transparent; border: none; padding: 3rem; max-width: 800px; margin: 0 auto;">
        
        <div class="card" style="padding: 3rem; background: #ffffff; border: 2px solid var(--border);">
            
            <form method="POST" action="{{ route('questions.update', $question->id) }}" style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">
                @csrf
                @method('PUT')

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: var(--primary); margin-bottom: 0.5rem; display: block;">Question Text</label>
                    <textarea name="question_text" rows="3" required>{{ old('question_text', $question->question_text) }}</textarea>
                    @error('question_text')
                        <p style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1rem;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: var(--primary); margin-bottom: 0.5rem; display: block;">Option A</label>
                        <input type="text" name="option_a" value="{{ old('option_a', $question->option_a) }}" required>
                        @error('option_a')
                            <p style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: var(--primary); margin-bottom: 0.5rem; display: block;">Option B</label>
                        <input type="text" name="option_b" value="{{ old('option_b', $question->option_b) }}" required>
                        @error('option_b')
                            <p style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: var(--primary); margin-bottom: 0.5rem; display: block;">Option C</label>
                        <input type="text" name="option_c" value="{{ old('option_c', $question->option_c) }}" required>
                        @error('option_c')
                            <p style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: var(--primary); margin-bottom: 0.5rem; display: block;">Option D</label>
                        <input type="text" name="option_d" value="{{ old('option_d', $question->option_d) }}" required>
                        @error('option_d')
                            <p style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: var(--primary); margin-bottom: 0.5rem; display: block;">Correct Answer Option</label>
                    <select name="correct_option" required style="width: 100%; padding: 1rem 1.25rem; background: #ffffff; border: 2px solid var(--border); border-radius: 0; color: var(--text-main); font-size: 1rem;">
                        <option value="A" {{ old('correct_option', $question->correct_option) === 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ old('correct_option', $question->correct_option) === 'B' ? 'selected' : '' }}>B</option>
                        <option value="C" {{ old('correct_option', $question->correct_option) === 'C' ? 'selected' : '' }}>C</option>
                        <option value="D" {{ old('correct_option', $question->correct_option) === 'D' ? 'selected' : '' }}>D</option>
                    </select>
                    @error('correct_option')
                        <p style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: var(--primary); margin-bottom: 0.5rem; display: block;">Answer Explanation (Logic)</label>
                    <textarea name="explanation" rows="3">{{ old('explanation', $question->explanation) }}</textarea>
                    @error('explanation')
                        <p style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn" style="padding: 1.5rem; background: var(--primary); font-size: 1.1rem; width: 100%;">
                    Save and Update Question
                </button>

            </form>

        </div>

    </div>
</x-layout>
