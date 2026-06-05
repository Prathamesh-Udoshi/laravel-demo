<x-layout>
    <x-slot:title>AI Tweet Composer</x-slot>

    <!-- Full Width Header Section -->
    <div style="padding: 4rem 3rem; background: #ffffff; border-bottom: 1px solid var(--border);">
        <div style="max-width: 1200px; margin: 0 auto;">
            <h2 style="font-size: 3rem; margin-bottom: 0.5rem; letter-spacing: -0.05em; font-family: 'Outfit', sans-serif; font-weight: 900; color: var(--text-main);">AI Tweet Composer</h2>
            <p style="font-size: 1.1rem; font-weight: 500; color: var(--text-muted);">Craft engaging, high-impact tweets on any topic using advanced AI models.</p>
        </div>
    </div>

    <!-- Main Workspace Container -->
    <div style="max-width: 800px; margin: 0 auto; width: 100%; padding: 4rem 2rem;">
        
        <div class="card animate-fade-in" style="background: #ffffff; border: 2px solid var(--border); padding: 3rem; margin-bottom: 3rem;">
            <form id="tweetForm" style="display: grid; gap: 2rem;">
                @csrf
                
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="topic" style="font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: var(--primary); margin-bottom: 0.5rem; display: block; letter-spacing: 0.05em;">
                        What is your tweet topic?
                    </label>
                    <input 
                        type="text" 
                        id="topic" 
                        name="topic" 
                        placeholder="e.g., Machine Learning, Web Development, Productivity Tips..."
                        style="width: 100%; padding: 1rem 1.25rem; border: 2px solid var(--border); font-size: 1rem; font-family: inherit;"
                        required
                    >
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.5rem; margin-bottom: 0; font-weight: 500;">
                        Enter any concept, technology, news, or idea you want the AI to write about.
                    </p>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="tone" style="font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: var(--primary); margin-bottom: 0.5rem; display: block; letter-spacing: 0.05em;">
                        Select Conversational Tone
                    </label>
                    <select 
                        id="tone" 
                        name="tone"
                        style="width: 100%; padding: 1rem 1.25rem; border: 2px solid var(--border); background: #ffffff; font-size: 1rem; font-family: inherit; color: var(--text-main); cursor: pointer;"
                    >
                        <option value="casual">Casual (Friendly, conversational)</option>
                        <option value="professional">Professional (Authoritative, educational)</option>
                        <option value="humorous">Humorous (Witty, engaging)</option>
                        <option value="inspirational">Inspirational (Motivating, thought-provoking)</option>
                    </select>
                </div>

                <button 
                    type="submit"
                    style="width: 100%; padding: 1.25rem 2rem; background: var(--primary); color: white; font-weight: 700; border: none; font-size: 1.05rem; cursor: pointer; transition: background 0.2s; display: flex; align-items: center; justify-content: center; gap: 0.75rem;"
                >
                    <span id="buttonText">Generate Tweet</span>
                    <div id="spinner" class="hidden" style="width: 18px; height: 18px; border: 2.5px solid white; border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                </button>
            </form>

            <!-- Result Box -->
            <div id="result" class="hidden" style="margin-top: 3rem; padding: 2rem; background: #f8fafc; border: 2px solid var(--primary); border-left-width: 6px; animation: fadeIn 0.3s ease-out;">
                <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--primary); margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 0.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
                    Draft Generated Successfully
                </h3>
                
                <div id="tweetContent" style="background: #ffffff; border: 1px solid var(--border); padding: 1.5rem; font-size: 1.15rem; color: var(--text-main); font-weight: 500; line-height: 1.5; white-space: pre-wrap; word-break: break-word; font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; margin-bottom: 1.5rem; border-radius: 4px;">
                </div>
                
                <div style="display: flex; gap: 1rem;">
                    <button 
                        id="copyBtn"
                        style="flex: 1; padding: 0.85rem 1.5rem; background: #ffffff; border: 2px solid var(--border); color: var(--text-main); font-weight: 700; transition: all 0.2s;"
                    >
                        📋 Copy Tweet Draft
                    </button>
                    <button 
                        id="generateAgainBtn"
                        style="flex: 1; padding: 0.85rem 1.5rem; background: var(--text-main); color: white; font-weight: 700;"
                    >
                        🔄 Compose Another
                    </button>
                </div>
            </div>

            <!-- Error Notification -->
            <div id="error" class="hidden" style="margin-top: 3rem; padding: 1.25rem; background: #fef2f2; border: 2px solid #ef4444; color: #b91c1c; font-weight: 700; font-size: 0.95rem;">
                <p id="errorMessage" style="margin-bottom: 0;"></p>
            </div>
        </div>

        <!-- Instruction Card -->
        <div class="card" style="margin-bottom: 0; padding: 2.5rem; border-left: 4px solid var(--accent);">
            <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--text-main); margin-bottom: 1rem; font-family: 'Outfit', sans-serif;">💡 Expert Social Strategy Tips</h3>
            <ul style="padding-left: 1.25rem; display: grid; gap: 0.5rem; font-size: 0.925rem; color: var(--text-muted); font-weight: 500;">
                <li>✓ High readability and clear value propositions perform best.</li>
                <li>✓ Emojis add visual warmth and increase engagement by up to 25%.</li>
                <li>✓ Tailor your tone: Casual for community building, Professional for credibility.</li>
                <li>✓ Limit hashtags to 1-2 highly relevant keywords to prevent visual clutter.</li>
            </ul>
        </div>

    </div>

    <style>
        .hidden {
            display: none !important;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>

    <script>
        const form = document.getElementById('tweetForm');
        const resultDiv = document.getElementById('result');
        const errorDiv = document.getElementById('error');
        const tweetContent = document.getElementById('tweetContent');
        const spinner = document.getElementById('spinner');
        const buttonText = document.getElementById('buttonText');
        const copyBtn = document.getElementById('copyBtn');
        const generateAgainBtn = document.getElementById('generateAgainBtn');
        const errorMessage = document.getElementById('errorMessage');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const topic = document.getElementById('topic').value;
            const tone = document.getElementById('tone').value;

            // Show loading state
            buttonText.textContent = 'Composing Draft...';
            spinner.classList.remove('hidden');
            resultDiv.classList.add('hidden');
            errorDiv.classList.add('hidden');

            try {
                const response = await fetch('{{ route("tweet-generator.generate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ topic, tone }),
                });

                const data = await response.json();

                if (data.success) {
                    tweetContent.textContent = data.tweet;
                    resultDiv.classList.remove('hidden');
                    errorDiv.classList.add('hidden');
                } else {
                    errorMessage.textContent = data.message || 'Failed to generate tweet';
                    errorDiv.classList.remove('hidden');
                    resultDiv.classList.add('hidden');
                }
            } catch (error) {
                errorMessage.textContent = 'Error: ' + error.message;
                errorDiv.classList.remove('hidden');
                resultDiv.classList.add('hidden');
            } finally {
                buttonText.textContent = 'Generate Tweet';
                spinner.classList.add('hidden');
            }
        });

        copyBtn.addEventListener('click', () => {
            const text = tweetContent.textContent;
            navigator.clipboard.writeText(text).then(() => {
                copyBtn.textContent = '✅ Copied to Clipboard!';
                copyBtn.style.borderColor = '#10b981';
                copyBtn.style.color = '#065f46';
                copyBtn.style.background = '#ecfdf5';
                setTimeout(() => {
                    copyBtn.textContent = '📋 Copy Tweet Draft';
                    copyBtn.style.borderColor = 'var(--border)';
                    copyBtn.style.color = 'var(--text-main)';
                    copyBtn.style.background = '#ffffff';
                }, 2500);
            });
        });

        generateAgainBtn.addEventListener('click', () => {
            resultDiv.classList.add('hidden');
            document.getElementById('topic').value = '';
            document.getElementById('topic').focus();
        });
    </script>
</x-layout>
