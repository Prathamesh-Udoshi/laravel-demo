<x-layout>
    <x-slot:title>Student Registry Directory</x-slot>

    <!-- Header Section -->
    <div style="padding: 4rem 3rem; background: #ffffff; border-bottom: 1px solid var(--border);">
        <div style="max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 2rem;">
            <div>
                <h2 style="font-size: 3rem; margin-bottom: 0.5rem; letter-spacing: -0.05em; font-family: 'Outfit', sans-serif; font-weight: 900; color: var(--text-main);">Student Directory</h2>
                <p style="font-size: 1.1rem; font-weight: 500; color: var(--text-muted); margin-bottom: 0;">An interactive database registry to manage, register, and view student enrollments.</p>
            </div>
            <a href="/sample/create" class="btn" style="background: linear-gradient(135deg, var(--primary), var(--accent)); box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);">
                + Register Student
            </a>
        </div>
    </div>

    <!-- Main Content Container -->
    <div style="max-width: 1000px; margin: 0 auto; width: 100%; padding: 4rem 2rem;">
        
        @if(session('success'))
            <div style="background: #ecfdf5; border: 2px solid #10b981; padding: 1.25rem; color: #065f46; font-weight: 700; margin-bottom: 2.5rem; animation: fadeIn 0.3s ease-out;">
                {{ session('success') }}
            </div>
        @endif

        <div style="text-align: left; margin-bottom: 2rem;">
            <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em;">Registered Students ({{ count($students) }})</h3>
        </div>

        <div style="display: grid; gap: 1.5rem;">
            @foreach($students as $index => $student)
                <div class="card animate-fade-in" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0; padding: 2rem; border: 2px solid var(--border); background: #ffffff; border-left: 5px solid {{ $index % 2 === 0 ? 'var(--primary)' : 'var(--accent)' }};">
                    <div>
                        <h4 style="font-size: 1.25rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.35rem; font-family: 'Outfit', sans-serif;">
                            {{ $student->name }}
                        </h4>
                        <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                            <span style="font-size: 0.8rem; font-weight: 700; background: #f1f5f9; color: #475569; padding: 0.25rem 0.6rem;">
                                Course: {{ $student->class }}
                            </span>
                            <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500;">
                                ID: #{{ $student->id }}
                            </span>
                        </div>
                    </div>
                    <a href="/sample/{{ $student->id }}" class="btn" style="background: white; border: 2px solid var(--border); color: var(--text-main); padding: 0.6rem 1.25rem; font-size: 0.85rem; font-weight: 700;">
                        View Profile
                    </a>
                </div>
            @endforeach
        </div>
        
    </div>
</x-layout>
