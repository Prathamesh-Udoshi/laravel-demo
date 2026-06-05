<x-layout>
    <x-slot:title>{{ $student->name }} — Profile</x-slot>

    <!-- Header Section -->
    <div style="padding: 4rem 3rem; background: #ffffff; border-bottom: 1px solid var(--border);">
        <div style="max-width: 1200px; margin: 0 auto; display: flex; align-items: center; gap: 1.5rem;">
            <a href="/sample" style="color: var(--text-muted); text-decoration: none; display: flex; align-items: center; font-weight: 600;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.25rem;">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to Directory
            </a>
        </div>
    </div>

    <!-- Profile Detail Container -->
    <div style="max-width: 700px; margin: 0 auto; width: 100%; padding: 4rem 2rem;">
        
        <div class="card animate-fade-in" style="background: #ffffff; border: 2px solid var(--border); padding: 4rem; position: relative;">
            <!-- Badge -->
            <div style="position: absolute; top: -12px; left: 30px; font-size: 0.75rem; font-weight: 800; background: var(--primary); color: white; padding: 0.35rem 1rem; text-transform: uppercase; letter-spacing: 0.05em;">
                Student Registry Profile
            </div>

            <div style="display: flex; align-items: center; gap: 2rem; border-bottom: 2px solid var(--border); padding-bottom: 2rem; margin-bottom: 2rem; flex-wrap: wrap;">
                <!-- Large Avatar Placeholder -->
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--primary), var(--accent)); display: flex; align-items: center; justify-content: center; font-size: 2.25rem; font-weight: 900; color: white; font-family: 'Outfit', sans-serif;">
                    {{ strtoupper(substr($student->name, 0, 1)) }}
                </div>
                
                <div>
                    <h2 style="font-size: 2rem; font-weight: 900; color: var(--text-main); margin-bottom: 0.35rem; font-family: 'Outfit', sans-serif;">
                        {{ $student->name }}
                    </h2>
                    <span style="font-size: 0.9rem; font-weight: 700; color: var(--primary); background: rgba(79, 70, 229, 0.08); padding: 0.35rem 0.75rem;">
                        {{ $student->class }}
                    </span>
                </div>
            </div>

            <!-- Profile Fields Table -->
            <div style="display: grid; gap: 1.5rem; font-size: 0.95rem; line-height: 1.5;">
                <div style="display: grid; grid-template-columns: 180px 1fr; gap: 1rem; border-bottom: 1px dashed var(--border); padding-bottom: 1rem;">
                    <span style="font-weight: 800; color: var(--text-muted); text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.05em;">Database ID</span>
                    <span style="font-weight: 600; color: var(--text-main);">#{{ $student->id }}</span>
                </div>

                <div style="display: grid; grid-template-columns: 180px 1fr; gap: 1rem; border-bottom: 1px dashed var(--border); padding-bottom: 1rem;">
                    <span style="font-weight: 800; color: var(--text-muted); text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.05em;">Registered Name</span>
                    <span style="font-weight: 600; color: var(--text-main);">{{ $student->name }}</span>
                </div>

                <div style="display: grid; grid-template-columns: 180px 1fr; gap: 1rem; border-bottom: 1px dashed var(--border); padding-bottom: 1rem;">
                    <span style="font-weight: 800; color: var(--text-muted); text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.05em;">Course / Class</span>
                    <span style="font-weight: 600; color: var(--text-main);">{{ $student->class }}</span>
                </div>

                <div style="display: grid; grid-template-columns: 180px 1fr; gap: 1rem; padding-bottom: 0.5rem;">
                    <span style="font-weight: 800; color: var(--text-muted); text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.05em;">Enrollment Date</span>
                    <span style="font-weight: 600; color: var(--text-main);">{{ $student->created_at ? $student->created_at->toDayDateTimeString() : 'N/A' }}</span>
                </div>
            </div>
            
        </div>
        
    </div>
</x-layout>