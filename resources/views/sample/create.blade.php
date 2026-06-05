<x-layout>
    <x-slot:title>Register New Student</x-slot>

    <!-- Header Section -->
    <div style="padding: 4rem 3rem; background: #ffffff; border-bottom: 1px solid var(--border);">
        <div style="max-width: 1200px; margin: 0 auto;">
            <h2 style="font-size: 3rem; margin-bottom: 0.5rem; letter-spacing: -0.05em; font-family: 'Outfit', sans-serif; font-weight: 900; color: var(--text-main);">Register Student</h2>
            <p style="font-size: 1.1rem; font-weight: 500; color: var(--text-muted); margin-bottom: 0;">Add a new student profile to the SQLite database with automatic validation checks.</p>
        </div>
    </div>

    <!-- Main Form Container -->
    <div style="max-width: 650px; margin: 0 auto; width: 100%; padding: 4rem 2rem;">
        
        <div class="card animate-fade-in" style="background: #ffffff; border: 2px solid var(--border); padding: 3rem;">
            
            @if ($errors->any())
                <div style="background: #fef2f2; border: 2px solid #ef4444; padding: 1.25rem; color: #b91c1c; font-weight: 700; margin-bottom: 2rem;">
                    <ul style="margin: 0; padding-left: 1.25rem; font-size: 0.9rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('students.store') }}" method="POST" style="display: grid; gap: 2rem;">
                @csrf
                
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="name" style="font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: var(--primary); margin-bottom: 0.5rem; display: block; letter-spacing: 0.05em;">
                        Full Student Name
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        placeholder="e.g., Alice Johnson"
                        style="width: 100%; padding: 1rem 1.25rem; border: 2px solid var(--border); font-size: 1rem;"
                        value="{{ old('name') }}"
                        required
                    >
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="class" style="font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: var(--primary); margin-bottom: 0.5rem; display: block; letter-spacing: 0.05em;">
                        Academic Class / Course Enrolled
                    </label>
                    <input 
                        type="text" 
                        id="class" 
                        name="class" 
                        placeholder="e.g., Master of Computer Applications"
                        style="width: 100%; padding: 1rem 1.25rem; border: 2px solid var(--border); font-size: 1rem;"
                        value="{{ old('class') }}"
                        required
                    >
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.5rem; margin-bottom: 0; font-weight: 500;">
                        Specify the exact class, department, or degree route.
                    </p>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <a href="/sample" style="flex: 1; padding: 1.25rem 2rem; background: #ffffff; border: 2px solid var(--border); color: var(--text-main); font-weight: 700; text-decoration: none; text-align: center; font-size: 1rem;">
                        Cancel
                    </a>
                    <button 
                        type="submit" 
                        style="flex: 1; padding: 1.25rem 2rem; background: var(--primary); color: white; font-weight: 700; border: none; font-size: 1rem; cursor: pointer; transition: background 0.2s;"
                    >
                        Register Profile
                    </button>
                </div>
            </form>
            
        </div>
        
    </div>
</x-layout>