<x-layout>
    <x-slot:title>AI Workspace Hub</x-slot>

    <!-- Premium Hero Section -->
    <div style="padding: 6rem 3rem; background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%); border-bottom: 1px solid var(--border); position: relative; overflow: hidden;">
        <!-- Backdrop subtle gradients -->
        <div style="position: absolute; width: 400px; height: 400px; border-radius: 50%; background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%); top: -100px; right: -50px; z-index: 1;"></div>
        <div style="position: absolute; width: 300px; height: 300px; border-radius: 50%; background: radial-gradient(circle, rgba(79, 70, 229, 0.1) 0%, transparent 70%); bottom: -50px; left: 5%; z-index: 1;"></div>

        <div style="max-width: 1200px; margin: 0 auto; position: relative; z-index: 2;">
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: rgba(79, 70, 229, 0.08); border: 1px solid rgba(79, 70, 229, 0.15); color: var(--primary); font-size: 0.8rem; font-weight: 800; margin-bottom: 2rem; letter-spacing: 0.05em; text-transform: uppercase;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="margin-top: -1px;"><path d="m12 3-1.912 5.886H3.888L8.7 12.514 6.788 18.4 12 14.772 17.212 18.4l-1.912-5.886 4.812-3.628h-6.2L12 3z"/></svg>
                Next-Generation AI Intelligence Hub
            </div>
            
            <h1 style="font-size: 4.5rem; font-weight: 900; margin-bottom: 1.5rem; line-height: 1.1; letter-spacing: -0.05em; color: var(--text-main); font-family: 'Outfit', sans-serif;">
                One Workspace. <br>Infinite <span style="background: linear-gradient(135deg, var(--primary), var(--accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">AI Productivity</span>.
            </h1>
            
            <p style="font-size: 1.25rem; max-width: 750px; margin-bottom: 3.5rem; color: var(--text-muted); font-weight: 500; line-height: 1.6;">
                Welcome to your unified AI command center. Seamlessly jump between playlist-scaffolded syllabus planners, voice-enabled diary analysis, AI tweet writers, and students directory records.
            </p>
            
            <div style="display: flex; gap: 1.25rem; flex-wrap: wrap;">
                <a href="/courses" class="btn" style="padding: 1rem 2.5rem; background: linear-gradient(135deg, var(--primary), var(--accent)); box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);">
                    Explore Course Planner
                </a>
                <a href="#features-grid" class="btn" style="background: white; border: 2px solid var(--border); color: var(--text-main); padding: 1rem 2.5rem;">
                    View Features Directory
                </a>
            </div>
        </div>
    </div>

    <!-- Active Workspace Features Directory -->
    <div id="features-grid" style="padding: 6rem 3rem; max-width: 1200px; margin: 0 auto; width: 100%;">
        <div style="text-align: center; margin-bottom: 4rem;">
            <h2 style="font-size: 2.25rem; font-weight: 800; letter-spacing: -0.04em; color: var(--text-main); margin-bottom: 1rem; font-family: 'Outfit', sans-serif;">
                Workspace Modules
            </h2>
            <p style="color: var(--text-muted); max-width: 600px; margin: 0 auto; font-size: 1.05rem; font-weight: 500;">
                Discover and access all five segregated productivity engines currently running inside your application.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 2.5rem;">
            
            <!-- Card 1: Course Assessment Planner -->
            <div class="card animate-fade-in" style="margin-bottom: 0; padding: 3rem; display: flex; flex-direction: column; height: 100%; border-top: 4px solid var(--primary); transition: all 0.3s ease;">
                <div style="width: 54px; height: 54px; background: rgba(79, 70, 229, 0.08); display: flex; align-items: center; justify-content: center; margin-bottom: 1.75rem; color: var(--primary);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6 6h10M6 10h10"/></svg>
                </div>
                
                <h3 style="font-size: 1.45rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.75rem; font-family: 'Outfit', sans-serif;">
                    Course Assessment Planner
                </h3>
                
                <p style="color: var(--text-muted); font-size: 0.975rem; line-height: 1.6; margin-bottom: 2rem; flex-grow: 1;">
                    Specially optimized for <strong>Visvesvaraya Technological University (VTU)</strong> and NPTEL structures. Paste any YouTube playlist URL, scaffold lectures dynamically, cache professional bulleted summaries, and instantly compile comprehensive Mid-Term & End-Term evaluations (10 MCQs + subjective assignments).
                </p>

                <div style="padding: 0.85rem 1.25rem; background: #f8fafc; border-left: 3px solid var(--primary); font-size: 0.85rem; font-family: monospace; color: #475569; margin-bottom: 2rem;">
                    <strong>API Tech:</strong> Groq Llama-3.3-70B • Gemini 1.5 Flash • SQLite Cache
                </div>
                
                <a href="/courses" class="btn" style="width: 100%; padding: 0.85rem 1.5rem; display: flex; justify-content: center; gap: 0.5rem; background: var(--text-main); font-size: 0.95rem;">
                    Launch Course Planner
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
            </div>

            <!-- Card 2: STT Daily Work Diary -->
            <div class="card animate-fade-in" style="margin-bottom: 0; padding: 3rem; display: flex; flex-direction: column; height: 100%; border-top: 4px solid var(--accent); transition: all 0.3s ease;">
                <div style="width: 54px; height: 54px; background: rgba(99, 102, 241, 0.08); display: flex; align-items: center; justify-content: center; margin-bottom: 1.75rem; color: var(--accent);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                
                <h3 style="font-size: 1.45rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.75rem; font-family: 'Outfit', sans-serif;">
                    STT Daily Work Diary
                </h3>
                
                <p style="color: var(--text-muted); font-size: 0.975rem; line-height: 1.6; margin-bottom: 2rem; flex-grow: 1;">
                    Analyze daily progress reports with speech-to-text functionality. Speak directly into your mic using standard Web Speech API or switch to local high-precision Faster-Whisper servers, then generate deep evaluations of learnings, blockers, metrics, and actionable feedback.
                </p>

                <div style="padding: 0.85rem 1.25rem; background: #f8fafc; border-left: 3px solid var(--accent); font-size: 0.85rem; font-family: monospace; color: #475569; margin-bottom: 2rem;">
                    <strong>API Tech:</strong> Web Speech API • Faster-Whisper Server • AI Insight Scorer
                </div>
                
                <a href="/analyze" class="btn" style="width: 100%; padding: 0.85rem 1.5rem; display: flex; justify-content: center; gap: 0.5rem; background: var(--text-main); font-size: 0.95rem;">
                    Open Work Diary
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
            </div>

            <!-- Card 3: AI Tweet Composer -->
            <div class="card animate-fade-in" style="margin-bottom: 0; padding: 3rem; display: flex; flex-direction: column; height: 100%; border-top: 4px solid #0284c7; transition: all 0.3s ease;">
                <div style="width: 54px; height: 54px; background: rgba(2, 132, 199, 0.08); display: flex; align-items: center; justify-content: center; margin-bottom: 1.75rem; color: #0284c7;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
                </div>
                
                <h3 style="font-size: 1.45rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.75rem; font-family: 'Outfit', sans-serif;">
                    AI Tweet Composer
                </h3>
                
                <p style="color: var(--text-muted); font-size: 0.975rem; line-height: 1.6; margin-bottom: 2rem; flex-grow: 1;">
                    Instantly craft engaging, viral-ready microblogging content for social media. Input any tech, business, or lifestyle topic, pick a customizable tone (Humorous, Inspirational, Professional, Casual), and let the AI draft highly shareable content complete with hashtags and formatting.
                </p>

                <div style="padding: 0.85rem 1.25rem; background: #f8fafc; border-left: 3px solid #0284c7; font-size: 0.85rem; font-family: monospace; color: #475569; margin-bottom: 2rem;">
                    <strong>API Tech:</strong> Groq Llama-3.1-8B • Precise Temperature • Clipboard APIs
                </div>
                
                <a href="/tweet-generator" class="btn" style="width: 100%; padding: 0.85rem 1.5rem; display: flex; justify-content: center; gap: 0.5rem; background: var(--text-main); font-size: 0.95rem;">
                    Compose AI Tweets
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
            </div>

            <!-- Card 4: Students Registry Directory -->
            <div class="card animate-fade-in" style="margin-bottom: 0; padding: 3rem; display: flex; flex-direction: column; height: 100%; border-top: 4px solid #0d9488; transition: all 0.3s ease;">
                <div style="width: 54px; height: 54px; background: rgba(13, 148, 136, 0.08); display: flex; align-items: center; justify-content: center; margin-bottom: 1.75rem; color: #0d9488;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                </div>
                
                <h3 style="font-size: 1.45rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.75rem; font-family: 'Outfit', sans-serif;">
                    Students Registry Directory
                </h3>
                
                <p style="color: var(--text-muted); font-size: 0.975rem; line-height: 1.6; margin-bottom: 2rem; flex-grow: 1;">
                    A functional registry database containing student profiles. Add new students to the SQLite database with full validation checks, list existing students with dynamic highlights, and inspect individual class enrollments and profiles.
                </p>

                <div style="padding: 0.85rem 1.25rem; background: #f8fafc; border-left: 3px solid #0d9488; font-size: 0.85rem; font-family: monospace; color: #475569; margin-bottom: 2rem;">
                    <strong>API Tech:</strong> SQLite Database • Eloquent ORM • Registration Validation
                </div>
                
                <a href="/sample" class="btn" style="width: 100%; padding: 0.85rem 1.5rem; display: flex; justify-content: center; gap: 0.5rem; background: var(--text-main); font-size: 0.95rem;">
                    Open Directory
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
            </div>

            <!-- Card 5: AI Lecture Tutor (RAG) -->
            <div class="card animate-fade-in" style="margin-bottom: 0; padding: 3rem; display: flex; flex-direction: column; height: 100%; border-top: 4px solid var(--primary); transition: all 0.3s ease;">
                <div style="width: 54px; height: 54px; background: rgba(79, 70, 229, 0.08); display: flex; align-items: center; justify-content: center; margin-bottom: 1.75rem; color: var(--primary);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>
                
                <h3 style="font-size: 1.45rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.75rem; font-family: 'Outfit', sans-serif;">
                    AI Lecture Tutor (RAG)
                </h3>
                
                <p style="color: var(--text-muted); font-size: 0.975rem; line-height: 1.6; margin-bottom: 2rem; flex-grow: 1;">
                    Query course lecture notes, video transcripts, and summaries semantically using dynamic vector embeddings. Ask questions in natural language and get replies backed by matching class material segments.
                </p>

                <div style="padding: 0.85rem 1.25rem; background: #f8fafc; border-left: 3px solid var(--primary); font-size: 0.85rem; font-family: monospace; color: #475569; margin-bottom: 2rem;">
                    <strong>API Tech:</strong> Gemini Embeddings • Cosine Similarity Fallback • AI Conversational Agent
                </div>
                
                <a href="/tutor" class="btn" style="width: 100%; padding: 0.85rem 1.5rem; display: flex; justify-content: center; gap: 0.5rem; background: var(--text-main); font-size: 0.95rem;">
                    Launch AI Tutor
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
            </div>

        </div>
    </div>
</x-layout>