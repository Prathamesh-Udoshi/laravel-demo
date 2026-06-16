<x-layout>
    <x-slot:title>STT Console</x-slot>

        <!-- Full Width Header Section -->
        <div style="padding: 4rem 3rem; background: #ffffff; border-bottom: 2px solid var(--border);">
            <h2 style="font-size: 3rem; margin-bottom: 0.5rem; letter-spacing: -0.05em;">Diary Entry Console</h2>
            <p style="font-size: 1.1rem; font-weight: 500;">Capture your daily progress with high-precision STT tools.
            </p>
        </div>

        <!-- Hybrid STT Toggle -->
        <div class="stt-toggle">
            <span id="label-web" class="active">WEB SPEECH API</span>
            <label class="switch" style="position: relative; display: inline-block; width: 60px; height: 30px;">
                <input type="checkbox" id="sttModeToggle" onchange="toggleSTTUI()"
                    style="opacity: 0; width: 0; height: 0;">
                <span class="slider"
                    style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #e2e8f0; transition: .4s; border: 2px solid var(--border);"></span>
            </label>
            <span id="label-whisper">FASTER-WHISPER AI</span>
        </div>

        <div class="container animate-fade-in" style="background: transparent; border: none; padding: 3rem;">
            <form method="POST" action="/analyze" id="analyzeForm"
                style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
                @csrf

                <div class="form-group">
                    <label
                        style="font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: var(--primary); margin-bottom: 0.5rem; display: block;">Report
                        Date</label>
                    <input type="date" name="date" required>
                </div>

                <div class="form-group" id="group-description">
                    <label
                        style="font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: var(--primary); margin-bottom: 0.5rem; display: block;">Daily
                        Activity</label>
                    <textarea name="description" id="description" placeholder="Describe your activity..." rows="4"
                        required></textarea>
                    <button type="button" class="mic-btn" onclick="handleMicClick('description', this)">
                        <svg class="mic-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z" />
                            <path d="M19 10v2a7 7 0 0 1-14 0v-2" />
                            <line x1="12" x2="12" y1="19" y2="22" />
                            <line x1="8" x2="16" y1="22" y2="22" />
                        </svg>
                        <svg class="spinner-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20" height="20" style="display: none; animation: spin 1s linear infinite;">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" style="opacity: 0.25;"></circle>
                            <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" style="opacity: 0.75;"></path>
                        </svg>
                    </button>
                    <div class="timer-badge">
                        <span class="ping-dot"></span>
                        <span class="timer-text">00:00</span>
                    </div>
                </div>

                <div class="form-group">
                    <label
                        style="font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: var(--primary); margin-bottom: 0.5rem; display: block;">Development
                        Hours</label>
                    <input type="text" name="hours" placeholder="e.g. 8.5" required>
                </div>

                <div class="form-group" id="group-learnings">
                    <label
                        style="font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: var(--primary); margin-bottom: 0.5rem; display: block;">Key
                        Learnings</label>
                    <textarea name="learnings" id="learnings" placeholder="What did you learn today?"
                        rows="3"></textarea>
                    <button type="button" class="mic-btn" onclick="handleMicClick('learnings', this)">
                        <svg class="mic-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z" />
                            <path d="M19 10v2a7 7 0 0 1-14 0v-2" />
                            <line x1="12" x2="12" y1="19" y2="22" />
                            <line x1="8" x2="16" y1="22" y2="22" />
                        </svg>
                        <svg class="spinner-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20" height="20" style="display: none; animation: spin 1s linear infinite;">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" style="opacity: 0.25;"></circle>
                            <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" style="opacity: 0.75;"></path>
                        </svg>
                    </button>
                    <div class="timer-badge">
                        <span class="ping-dot"></span>
                        <span class="timer-text">00:00</span>
                    </div>
                </div>

                <div class="form-group" id="group-blockers">
                    <label
                        style="font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: var(--primary); margin-bottom: 0.5rem; display: block;">Critical
                        Blockers</label>
                    <textarea name="blockers" id="blockers" placeholder="Any issues faced?" rows="3"></textarea>
                    <button type="button" class="mic-btn" onclick="handleMicClick('blockers', this)">
                        <svg class="mic-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z" />
                            <path d="M19 10v2a7 7 0 0 1-14 0v-2" />
                            <line x1="12" x2="12" y1="19" y2="22" />
                            <line x1="8" x2="16" y1="22" y2="22" />
                        </svg>
                        <svg class="spinner-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20" height="20" style="display: none; animation: spin 1s linear infinite;">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" style="opacity: 0.25;"></circle>
                            <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" style="opacity: 0.75;"></path>
                        </svg>
                    </button>
                    <div class="timer-badge">
                        <span class="ping-dot"></span>
                        <span class="timer-text">00:00</span>
                    </div>
                </div>

                <div class="form-group">
                    <label
                        style="font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: var(--primary); margin-bottom: 0.5rem; display: block;">Skills
                        Involved</label>
                    <input type="text" name="skills" placeholder="React, Python, Laravel...">
                </div>

                <button type="submit" class="btn" style="padding: 1.5rem; margin-top: 1rem;">
                    Process Detailed AI Analysis
                </button>
            </form>
        </div>

        <style>
            .slider:before {
                position: absolute;
                content: "";
                height: 22px;
                width: 22px;
                left: 4px;
                bottom: 2px;
                background-color: var(--text-muted);
                transition: .4s;
            }

            input:checked+.slider {
                background-color: var(--primary);
            }

            input:checked+.slider:before {
                transform: translateX(30px);
                background-color: white;
            }
        </style>

        <script>
            // JS remains largely the same, optimized for the new UI elements
            let activeInputId = null;
            let activeBtn = null;
            let webRecognition;
            let mediaRecorder;
            let audioChunks = [];
            let startTime;
            let timerInterval;
            let baseText = '';

            function initWebSpeech() {
                const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                if (!SpeechRecognition) {
                    console.warn("Speech Recognition not supported in this browser.");
                    return null;
                }
                const recognition = new SpeechRecognition();
                recognition.continuous = true;
                recognition.interimResults = true;
                recognition.lang = 'en-US';

                recognition.onresult = (e) => {
                    if (!activeInputId) return;
                    const input = document.getElementById(activeInputId);
                    let finalTranscript = '';
                    let interimTranscript = '';
                    for (let i = 0; i < e.results.length; ++i) {
                        if (e.results[i].isFinal) {
                            finalTranscript += e.results[i][0].transcript;
                        } else {
                            interimTranscript += e.results[i][0].transcript;
                        }
                    }
                    const currentSpeech = finalTranscript + interimTranscript;
                    input.value = baseText + (baseText && currentSpeech ? ' ' : '') + currentSpeech;
                };

                recognition.onerror = (e) => {
                    console.error("Speech Recognition Error:", e.error);
                    if (e.error === 'not-allowed') {
                        alert("Microphone permission denied. Please ensure you are using HTTPS or localhost.");
                    }
                    stopWebSpeech();
                };

                recognition.onend = () => {
                    // If it ends but we didn't manually stop it, it might have timed out
                    if (activeInputId) {
                        console.log("Speech Recognition ended unexpectedly. Restarting...");
                        try { recognition.start(); } catch (err) { }
                    }
                };

                return recognition;
            }

            webRecognition = initWebSpeech();

            function toggleSTTUI() {
                const isWhisper = document.getElementById('sttModeToggle').checked;
                document.getElementById('label-web').classList.toggle('active', !isWhisper);
                document.getElementById('label-whisper').classList.toggle('active', isWhisper);
                if (activeInputId) stopCurrentSession();
            }

            function handleMicClick(inputId, btn) {
                const isWhisper = document.getElementById('sttModeToggle').checked;
                if (activeInputId === inputId) {
                    stopCurrentSession();
                } else {
                    if (activeInputId) stopCurrentSession();
                    isWhisper ? startWhisperSession(inputId, btn) : startWebSession(inputId, btn);
                }
            }

            function stopCurrentSession() {
                const isWhisper = document.getElementById('sttModeToggle').checked;
                isWhisper ? stopWhisperSession() : stopWebSpeech();
            }

            function startWebSession(inputId, btn) {
                if (!window.isSecureContext && window.location.hostname !== 'localhost') {
                    alert("Web Speech API requires a secure context (HTTPS) or localhost. Please run 'herd secure' or access via localhost.");
                    return;
                }

                if (!webRecognition) {
                    webRecognition = initWebSpeech();
                }

                if (!webRecognition) {
                    alert("Web Speech API is not supported in this browser. Please switch to 'FASTER-WHISPER AI' mode or use a Chromium-based browser like Google Chrome.");
                    return;
                }

                activeInputId = inputId;
                activeBtn = btn;
                btn.classList.add('is-listening');

                const input = document.getElementById(inputId);
                baseText = input ? input.value : '';

                // Start timer display
                startTime = Date.now();
                const timerBadge = btn.parentElement.querySelector('.timer-badge');
                const timerText = timerBadge ? (timerBadge.querySelector('.timer-text') || timerBadge) : null;
                if (timerText) {
                    timerText.innerText = "00:00";
                    timerInterval = setInterval(() => {
                        const elapsed = Math.floor((Date.now() - startTime) / 1000);
                        const m = Math.floor(elapsed / 60).toString().padStart(2, '0');
                        const s = (elapsed % 60).toString().padStart(2, '0');
                        timerText.innerText = `${m}:${s}`;
                    }, 1000);
                }

                try {
                    webRecognition.start();
                } catch (err) {
                    console.error("Failed to start speech recognition:", err);
                    stopWebSpeech();
                    setTimeout(() => startWebSession(inputId, btn), 100);
                }
            }

            function stopWebSpeech() {
                if (activeBtn) activeBtn.classList.remove('is-listening');
                if (webRecognition) webRecognition.stop();
                clearInterval(timerInterval);
                activeInputId = null;
                activeBtn = null;
            }

            async function startWhisperSession(inputId, btn) {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    mediaRecorder = new MediaRecorder(stream);
                    audioChunks = [];
                    activeInputId = inputId;
                    activeBtn = btn;
                    mediaRecorder.ondataavailable = (e) => audioChunks.push(e.data);
                    mediaRecorder.onstop = () => processWhisperAudio();
                    mediaRecorder.start();
                    btn.classList.add('is-listening');
                    startTime = Date.now();
                    const timerBadge = btn.parentElement.querySelector('.timer-badge');
                    const timerText = timerBadge ? (timerBadge.querySelector('.timer-text') || timerBadge) : null;
                    if (timerText) {
                        timerText.innerText = "00:00";
                        timerInterval = setInterval(() => {
                            const elapsed = Math.floor((Date.now() - startTime) / 1000);
                            const m = Math.floor(elapsed / 60).toString().padStart(2, '0');
                            const s = (elapsed % 60).toString().padStart(2, '0');
                            timerText.innerText = `${m}:${s}`;
                        }, 1000);
                    }
                } catch (err) { alert("Mic denied."); }
            }

            function stopWhisperSession() {
                mediaRecorder.stop();
                mediaRecorder.stream.getTracks().forEach(t => t.stop());
                activeBtn.classList.remove('is-listening');
                clearInterval(timerInterval);
            }

            async function processWhisperAudio() {
                const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                const input = document.getElementById(activeInputId);
                const btn = activeBtn;
                if (!input || !btn) return;

                const timerBadge = btn.parentElement.querySelector('.timer-badge');
                const timerText = timerBadge ? (timerBadge.querySelector('.timer-text') || timerBadge) : null;

                // Add processing class to textarea and button
                btn.classList.add('is-processing');
                input.classList.add('stt-processing');
                if (timerText) {
                    timerText.innerText = 'Processing...';
                }

                const formData = new FormData();
                formData.append('file', audioBlob, 'rec.wav');
                try {
                    const res = await fetch('http://localhost:8001/api/v1/transcribe/', { method: 'POST', body: formData });
                    const data = await res.json();
                    if (data.transcription) input.value += (input.value ? ' ' : '') + data.transcription;
                } catch (e) {
                    console.error("Whisper Transcription Error:", e);
                    alert("Whisper server is unreachable. Please make sure the Whisper service is running on port 8001.");
                }
                finally {
                    btn.classList.remove('is-processing');
                    input.classList.remove('stt-processing');
                    activeInputId = null;
                    activeBtn = null;
                }
            }
        </script>
</x-layout>