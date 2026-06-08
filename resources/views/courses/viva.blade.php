<x-layout>
    <x-slot:title>AI Viva Voce Simulator — {{ $course->title }}</x-slot>

    <!-- Header Section -->
    <div style="padding: 3rem; background: #ffffff; border-bottom: 2px solid var(--border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 2rem;">
        <div>
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                <a href="{{ route('courses.show', $course->id) }}" style="color: var(--text-muted); text-decoration: none; display: flex; align-items: center; font-weight: 600; font-size: 0.9rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.25rem;">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Back to Workspace
                </a>
            </div>
            <h2 style="font-size: 2.25rem; margin-bottom: 0.5rem; letter-spacing: -0.05em; color: var(--text-main); font-weight: 800; font-family: 'Outfit', sans-serif;">
                AI Viva Voce (Oral Exam)
            </h2>
            <p style="font-size: 0.95rem; font-weight: 500; color: var(--text-muted); margin-bottom: 0;">
                Practice verbal question-answering with an adaptive VTU/NPTEL AI Examiner.
            </p>
        </div>
    </div>

    <!-- Main Workspace Container -->
    <div style="max-width: 900px; margin: 0 auto; width: 100%; padding: 4rem 2rem; flex-grow: 1; display: flex; flex-direction: column;">
        
        <!-- Step 1: Setup Panel -->
        <div id="viva-setup-panel" class="card animate-fade-in" style="background: #ffffff; border: 2px solid var(--border); padding: 3rem; text-align: center;">
            <div style="width: 64px; height: 64px; background: rgba(79, 70, 229, 0.08); display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem; color: var(--primary); border-radius: 50%;">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" y1="19" x2="12" y2="22"/></svg>
            </div>
            
            <h3 style="font-size: 1.6rem; font-weight: 800; color: var(--text-main); margin-bottom: 1rem; font-family: 'Outfit', sans-serif;">Configure Your Oral Exam</h3>
            <p style="color: var(--text-muted); max-width: 500px; margin: 0 auto 2.5rem; font-size: 0.95rem; line-height: 1.5;">
                Select which portion of the syllabus you want to be tested on. The AI Examiner will ask 3 questions sequentially based on your lecture summaries.
            </p>

            <div style="display: flex; justify-content: center; gap: 2rem; margin-bottom: 3rem;">
                <label style="display: flex; flex-direction: column; align-items: center; gap: 0.75rem; cursor: pointer; padding: 1.5rem; border: 2px solid var(--border); width: 220px; text-align: center; transition: border-color 0.2s;" id="scope-midterm-label">
                    <input type="radio" name="scope" value="midterm" checked style="accent-color: var(--primary);" onchange="updateScopeStyles()">
                    <span style="font-weight: 800; font-size: 1.1rem; color: var(--text-main);">Mid-Term Scope</span>
                    <span style="font-size: 0.8rem; color: var(--text-muted);">Weeks 1 to {{ (int)ceil($course->duration_weeks / 2) }}</span>
                </label>
                
                <label style="display: flex; flex-direction: column; align-items: center; gap: 0.75rem; cursor: pointer; padding: 1.5rem; border: 2px solid var(--border); width: 220px; text-align: center; transition: border-color 0.2s;" id="scope-final-label">
                    <input type="radio" name="scope" value="final" style="accent-color: var(--primary);" onchange="updateScopeStyles()">
                    <span style="font-weight: 800; font-size: 1.1rem; color: var(--text-main);">End-Term Scope</span>
                    <span style="font-size: 0.8rem; color: var(--text-muted);">Weeks {{ (int)ceil($course->duration_weeks / 2) + 1 }} to {{ $course->duration_weeks }}</span>
                </label>
            </div>

            <button onclick="startViva()" class="btn" style="padding: 1rem 3rem; font-size: 1.05rem; background: linear-gradient(135deg, var(--primary), var(--accent)); border: none; box-shadow: 0 4px 14px rgba(79, 70, 229, 0.25);">
                Start Viva Voce
            </button>
        </div>

        <!-- Step 2: Exam Arena -->
        <div id="viva-arena" class="hidden" style="flex-grow: 1; display: flex; flex-direction: column; gap: 2rem; animation: fadeIn 0.3s ease-out;">
            
            <!-- Chat Log / dialogue history -->
            <div id="viva-chat-log" style="background: #ffffff; border: 2px solid var(--border); padding: 2rem; display: flex; flex-direction: column; gap: 1.5rem; min-height: 250px; max-height: 400px; overflow-y: auto;">
                <!-- Dynamically populated dialogue logs -->
            </div>

            <!-- Speech/Console Input Area -->
            <div class="card" style="background: #ffffff; border: 2px solid var(--border); padding: 2.5rem; margin-bottom: 0; position: relative;">
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span id="turn-badge" style="background: var(--primary); color: white; font-size: 0.75rem; font-weight: 800; padding: 0.25rem 0.75rem; border-radius: 2px; text-transform: uppercase;">Question 1/3</span>
                        <span id="listening-status" style="font-size: 0.85rem; font-weight: 700; color: #ef4444;" class="hidden">● LISTENING FOR YOUR VOICE...</span>
                    </div>
                    
                    <button onclick="toggleInputMethod()" id="input-toggle-btn" style="background: transparent; border: none; color: var(--primary); font-size: 0.85rem; font-weight: 700; cursor: pointer; text-decoration: underline;">
                        Switch to Manual Typing
                    </button>
                </div>

                <!-- Voice input wrapper -->
                <div id="voice-input-wrapper" style="text-align: center; padding: 1.5rem 0;">
                    <!-- Pulse Soundwave Animation -->
                    <div id="soundwave" class="hidden" style="display: flex; justify-content: center; align-items: center; gap: 4px; height: 40px; margin-bottom: 1.5rem;">
                        <span class="bar bar-1"></span>
                        <span class="bar bar-2"></span>
                        <span class="bar bar-3"></span>
                        <span class="bar bar-4"></span>
                        <span class="bar bar-5"></span>
                    </div>

                    <button id="mic-trigger-btn" onclick="toggleMicrophone()" style="width: 72px; height: 72px; border-radius: 50%; border: 3px solid var(--border); background: #ffffff; color: var(--text-muted); cursor: pointer; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" y1="19" x2="12" y2="22"/></svg>
                    </button>
                    
                    <p id="mic-instruction" style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600; margin-top: 1rem; margin-bottom: 1.5rem;">
                        Click the microphone and start speaking your answer.
                    </p>

                    <!-- Realtime Speech-to-Text Transcript Visual Box -->
                    <div id="speech-transcript-box" style="width: 100%; border: 2px dashed var(--border); padding: 1.25rem 1.5rem; text-align: left; font-size: 1rem; color: var(--text-main); font-weight: 500; min-height: 60px; line-height: 1.5; background: #f8fafc; margin-bottom: 1.5rem; white-space: pre-wrap;">(Your voice answer will appear here...)</div>
                </div>

                <!-- Text input wrapper -->
                <div id="text-input-wrapper" class="hidden" style="margin-bottom: 1.5rem;">
                    <textarea id="typed-answer-input" placeholder="Type your descriptive answer here..." rows="4" style="width: 100%; padding: 1rem 1.25rem; border: 2px solid var(--border); border-radius: 0; font-size: 1rem; resize: none;"></textarea>
                </div>

                <!-- Submit trigger -->
                <button id="submit-answer-btn" onclick="submitAnswer()" disabled class="btn" style="width: 100%; padding: 1.25rem; font-size: 1.05rem; display: flex; justify-content: center; align-items: center; gap: 0.75rem; border: none; background: #94a3b8; color: white;">
                    <span id="submit-btn-text">Submit Answer</span>
                    <div id="submit-spinner" class="hidden" style="width: 18px; height: 18px; border: 2.5px solid white; border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                </button>
            </div>
        </div>

        <!-- Step 3: Scorecard Results -->
        <div id="viva-scorecard" class="hidden animate-fade-in" style="display: grid; gap: 2.5rem; margin-bottom: 2rem;">
            
            <div class="card" style="background: #ffffff; border: 2px solid var(--border); padding: 3.5rem; text-align: center; border-top: 6px solid var(--primary); position: relative;">
                <div style="position: absolute; top: -14px; left: 30px; font-size: 0.8rem; font-weight: 800; background: var(--primary); color: white; padding: 0.35rem 1.25rem; text-transform: uppercase; letter-spacing: 0.05em;">
                    Exam Finished
                </div>

                <div style="display: flex; justify-content: center; align-items: center; flex-direction: column; margin-bottom: 2.5rem;">
                    <div style="width: 100px; height: 100px; border-radius: 50%; border: 4px solid var(--primary); display: flex; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: 900; color: var(--primary); font-family: 'Outfit', sans-serif; background: rgba(79, 70, 229, 0.04); margin-bottom: 1rem;">
                        <span id="scorecard-score">8</span>/10
                    </div>
                    <h4 style="font-size: 1.5rem; font-weight: 800; color: var(--text-main); margin-bottom: 0;">Viva Voce Grade</h4>
                </div>

                <div style="text-align: left; display: grid; gap: 2.5rem;">
                    <!-- Rubric 1: Conceptual accuracy -->
                    <div style="border-left: 4px solid var(--primary); padding-left: 1.5rem;">
                        <h5 style="font-size: 1.1rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.5rem;">Conceptual Accuracy</h5>
                        <p id="scorecard-concepts" style="font-size: 0.95rem; color: var(--text-main); line-height: 1.6; margin-bottom: 0; white-space: pre-line;"></p>
                    </div>

                    <!-- Rubric 2: Communication / Verbal Delivery -->
                    <div style="border-left: 4px solid var(--accent); padding-left: 1.5rem;">
                        <h5 style="font-size: 1.1rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.5rem;">Communication & Verbal Delivery</h5>
                        <p id="scorecard-delivery" style="font-size: 0.95rem; color: var(--text-main); line-height: 1.6; margin-bottom: 0; white-space: pre-line;"></p>
                    </div>

                    <!-- Rubric 3: Model Answers Guide -->
                    <div style="border-left: 4px solid #10b981; padding-left: 1.5rem;">
                        <h5 style="font-size: 1.1rem; font-weight: 800; color: #10b981; margin-bottom: 0.5rem;">Ideal Answer Key Guide</h5>
                        <p id="scorecard-ideal" style="font-size: 0.95rem; color: var(--text-main); line-height: 1.6; margin-bottom: 0; white-space: pre-line;"></p>
                    </div>
                </div>

                <button onclick="resetViva()" class="btn" style="margin-top: 3.5rem; padding: 1rem 3rem; background: var(--text-main); color: white; border: none; font-size: 1rem;">
                    Restart Another Exam
                </button>
            </div>
        </div>

    </div>

    <style>
        .hidden {
            display: none !important;
        }

        /* Soundwave bars styling */
        .bar {
            display: inline-block;
            width: 4px;
            height: 5px;
            background-color: #ef4444;
            border-radius: 2px;
            animation: wave 1s ease-in-out infinite alternate;
        }
        .bar-1 { animation-delay: 0.1s; }
        .bar-2 { animation-delay: 0.3s; }
        .bar-3 { animation-delay: 0.5s; }
        .bar-4 { animation-delay: 0.2s; }
        .bar-5 { animation-delay: 0.4s; }

        @keyframes wave {
            0% { height: 5px; }
            100% { height: 35px; }
        }

        /* Scope Cards radio button styles */
        input[type="radio"]:checked + span {
            color: var(--primary);
        }
    </style>

    <script>
        let isUsingVoice = true;
        let isRecording = false;
        let recognition;
        let activeQuestionNum = 1;
        let currentRecordedAnswer = "";

        // Scope highlights update
        function updateScopeStyles() {
            const isFinalSelected = document.querySelector('input[name="scope"][value="final"]').checked;
            document.getElementById('scope-midterm-label').style.borderColor = isFinalSelected ? 'var(--border)' : 'var(--primary)';
            document.getElementById('scope-final-label').style.borderColor = isFinalSelected ? 'var(--primary)' : 'var(--border)';
        }
        updateScopeStyles(); // initialize

        let mediaRecorder;
        let audioChunks = [];
        let audioStream;

        function toggleInputMethod() {
            isUsingVoice = !isUsingVoice;
            if (isRecording) stopRecording();
            
            const typedInput = document.getElementById('typed-answer-input');
            const transcriptBox = document.getElementById('speech-transcript-box');
            
            if (!isUsingVoice) {
                // Sync voice to text
                typedInput.value = currentRecordedAnswer.trim();
            } else {
                // Sync text to voice
                currentRecordedAnswer = typedInput.value.trim();
                transcriptBox.innerText = currentRecordedAnswer || "(Your voice answer will appear here...)";
                transcriptBox.style.color = 'var(--text-main)';
            }
            
            document.getElementById('voice-input-wrapper').classList.toggle('hidden', !isUsingVoice);
            document.getElementById('text-input-wrapper').classList.toggle('hidden', isUsingVoice);
            document.getElementById('input-toggle-btn').innerText = isUsingVoice ? 'Switch to Manual Typing' : 'Switch to Voice Input';
            
            // Re-evaluate submit button state
            evaluateSubmitStateOnInput();
        }

        // Add real-time text input checking
        document.getElementById('typed-answer-input').addEventListener('input', () => {
            evaluateSubmitStateOnInput();
        });

        function evaluateSubmitStateOnInput() {
            const answer = isUsingVoice 
                ? currentRecordedAnswer.trim() 
                : document.getElementById('typed-answer-input').value.trim();
            updateSubmitButtonState(answer);
        }

        function updateSubmitButtonState(answerText) {
            const btn = document.getElementById('submit-answer-btn');
            if (answerText.length > 0) {
                btn.removeAttribute('disabled');
                btn.style.background = 'var(--primary)';
            } else {
                btn.setAttribute('disabled', 'true');
                btn.style.background = '#94a3b8';
            }
        }

        function toggleMicrophone() {
            if (isRecording) {
                stopRecording();
            } else {
                startRecording();
            }
        }

        async function startRecording() {
            try {
                audioStream = await navigator.mediaDevices.getUserMedia({ audio: true });
            } catch (err) {
                alert("Microphone permission denied or unavailable. Please switch to manual typing.");
                toggleInputMethod();
                return;
            }

            isRecording = true;
            audioChunks = [];
            
            // Set up MediaRecorder
            mediaRecorder = new MediaRecorder(audioStream);
            
            mediaRecorder.ondataavailable = (event) => {
                if (event.data.size > 0) {
                    audioChunks.push(event.data);
                }
            };

            mediaRecorder.onstop = async () => {
                // Combine chunks into webm blob
                const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                
                // Release audio stream tracks
                if (audioStream) {
                    audioStream.getTracks().forEach(track => track.stop());
                }

                // Show processing/transcribing feedback
                const transcriptBox = document.getElementById('speech-transcript-box');
                transcriptBox.innerText = "Transcribing your answer via Groq Whisper API...";
                transcriptBox.style.color = 'var(--primary)';
                
                // Temporarily disable submit button
                const submitBtn = document.getElementById('submit-answer-btn');
                submitBtn.setAttribute('disabled', 'true');
                submitBtn.style.background = '#94a3b8';
                
                // Create FormData payload
                const formData = new FormData();
                formData.append('audio', audioBlob, 'recording.webm');

                try {
                    const response = await fetch(`/courses/{{ $course->id }}/viva/transcribe`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (data.success) {
                        currentRecordedAnswer = data.transcript;
                        transcriptBox.innerText = currentRecordedAnswer || "(No speech detected. Please record again.)";
                        transcriptBox.style.color = 'var(--text-main)';
                        evaluateSubmitStateOnInput();
                    } else {
                        throw new Error(data.message || 'Unknown transcription error.');
                    }
                } catch (error) {
                    console.error("Transcription error:", error);
                    transcriptBox.innerText = "Transcription failed: " + error.message + "\nPlease try again or switch to manual typing.";
                    transcriptBox.style.color = '#ef4444';
                }
            };

            // UI listening updates
            document.getElementById('listening-status').classList.remove('hidden');
            document.getElementById('soundwave').classList.remove('hidden');
            
            const btn = document.getElementById('mic-trigger-btn');
            btn.style.borderColor = '#ef4444';
            btn.style.background = '#fef2f2';
            btn.style.color = '#ef4444';
            document.getElementById('mic-instruction').innerText = "Recording... Click again to stop.";

            mediaRecorder.start();
        }

        function stopRecording() {
            isRecording = false;
            document.getElementById('listening-status').classList.add('hidden');
            document.getElementById('soundwave').classList.add('hidden');
            
            const btn = document.getElementById('mic-trigger-btn');
            btn.style.borderColor = 'var(--border)';
            btn.style.background = '#ffffff';
            btn.style.color = 'var(--text-muted)';
            document.getElementById('mic-instruction').innerText = "Click the microphone and start speaking your answer.";
            
            if (mediaRecorder && mediaRecorder.state !== 'inactive') {
                mediaRecorder.stop();
            }
        }

        // 1. AJAX Start
        async function startViva() {
            const selectedScope = document.querySelector('input[name="scope"]:checked').value;
            
            try {
                const response = await fetch(`/courses/{{ $course->id }}/viva/start`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ scope: selectedScope })
                });

                const data = await response.json();

                if (data.success) {
                    // Hide setup, show arena
                    document.getElementById('viva-setup-panel').classList.add('hidden');
                    document.getElementById('viva-arena').classList.remove('hidden');
                    
                    activeQuestionNum = 1;
                    document.getElementById('turn-badge').innerText = `Question ${activeQuestionNum}/3`;
                    
                    // Add examiner's question to the chat log
                    appendChatBubble('examiner', data.question);
                } else {
                    alert(data.message || 'Error starting session.');
                }
            } catch (err) {
                alert('Connection error starting Viva: ' + err.message);
            }
        }

        function formatScorecardSection(val) {
            if (!val) return '';
            if (typeof val === 'string') {
                return val.replace(/\n/g, '<br>');
            }
            if (Array.isArray(val)) {
                return val.map(item => `• ${item}`).join('<br>');
            }
            if (typeof val === 'object') {
                let html = '';
                for (const key in val) {
                    if (val.hasOwnProperty(key)) {
                        const capitalizedKey = key.charAt(0).toUpperCase() + key.slice(1);
                        const subVal = val[key];
                        if (Array.isArray(subVal)) {
                            html += `<strong>${capitalizedKey}:</strong><br>` + subVal.map(item => `• ${item}`).join('<br>') + '<br><br>';
                        } else if (typeof subVal === 'object') {
                            html += `<strong>${capitalizedKey}:</strong><br>`;
                            for (const subKey in subVal) {
                                if (subVal.hasOwnProperty(subKey)) {
                                    html += `• <em>${subKey}:</em> ${subVal[subKey]}<br>`;
                                }
                            }
                            html += '<br>';
                        } else {
                            html += `<strong>${capitalizedKey}:</strong> ${subVal}<br><br>`;
                        }
                    }
                }
                return html.trim();
            }
            return '';
        }

        // 2. AJAX Submit
        async function submitAnswer() {
            if (isRecording) stopRecording();

            const answer = isUsingVoice 
                ? currentRecordedAnswer.trim() 
                : document.getElementById('typed-answer-input').value.trim();

            if (!answer) return;

            // Show loading state
            document.getElementById('submit-spinner').classList.remove('hidden');
            document.getElementById('submit-answer-btn').setAttribute('disabled', 'true');
            document.getElementById('submit-btn-text').innerText = "Submitting Answer...";

            // Append student's answer to layout
            appendChatBubble('student', answer);

            try {
                const response = await fetch(`/courses/{{ $course->id }}/viva/submit`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ answer: answer })
                });

                const data = await response.json();

                if (data.success) {
                    // Clear inputs
                    currentRecordedAnswer = "";
                    document.getElementById('speech-transcript-box').innerText = "(Your voice answer will appear here...)";
                    document.getElementById('typed-answer-input').value = "";
                    updateSubmitButtonState("");

                    if (!data.finished) {
                        activeQuestionNum++;
                        document.getElementById('turn-badge').innerText = `Question ${activeQuestionNum}/3`;
                        appendChatBubble('examiner', data.question);
                    } else {
                        // End Viva, show scorecard
                        document.getElementById('viva-arena').classList.add('hidden');
                        document.getElementById('viva-scorecard').classList.remove('hidden');

                        document.getElementById('scorecard-score').innerText = data.scorecard.score;
                        document.getElementById('scorecard-concepts').innerHTML = formatScorecardSection(data.scorecard.concepts);
                        document.getElementById('scorecard-delivery').innerHTML = formatScorecardSection(data.scorecard.delivery);
                        document.getElementById('scorecard-ideal').innerHTML = formatScorecardSection(data.scorecard.ideal_answers);
                    }
                } else {
                    alert(data.message || 'Error submitting answer.');
                }
            } catch (err) {
                alert('Connection error submitting answer: ' + err.message);
            } finally {
                document.getElementById('submit-spinner').classList.add('hidden');
                document.getElementById('submit-btn-text').innerText = "Submit Answer";
            }
        }

        // 3. Reset
        async function resetViva() {
            try {
                await fetch(`/courses/{{ $course->id }}/viva/reset`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
            } catch (err) {}

            document.getElementById('viva-scorecard').classList.add('hidden');
            document.getElementById('viva-setup-panel').classList.remove('hidden');
            document.getElementById('viva-chat-log').innerHTML = "";
        }

        // Helper to append bubbles to chat log
        function appendChatBubble(role, content) {
            const chatLog = document.getElementById('viva-chat-log');
            const bubble = document.createElement('div');
            bubble.style.display = 'flex';
            bubble.style.gap = '1rem';
            bubble.style.alignItems = 'flex-start';
            bubble.style.maxWidth = '85%';
            bubble.style.animation = 'fadeIn 0.25s ease-out';

            if (role === 'examiner') {
                bubble.style.alignSelf = 'flex-start';
                bubble.innerHTML = `
                    <div style="width: 38px; height: 38px; background: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 800; color: white; font-family: 'Outfit'; font-size: 0.95rem;">
                        EX
                    </div>
                    <div style="background: #f1f5f9; border: 1px solid var(--border); padding: 1rem; line-height: 1.5; font-size: 0.95rem; color: var(--text-main); font-weight: 500;">
                        <strong>Examiner:</strong> ${content}
                    </div>
                `;
            } else {
                bubble.style.alignSelf = 'flex-end';
                bubble.style.flexDirection = 'row-reverse';
                bubble.innerHTML = `
                    <div style="width: 38px; height: 38px; background: var(--accent); display: flex; align-items: center; justify-content: center; font-weight: 800; color: white; font-family: 'Outfit'; font-size: 0.95rem;">
                        ST
                    </div>
                    <div style="background: rgba(99, 102, 241, 0.05); border: 1px solid rgba(99, 102, 241, 0.15); padding: 1rem; line-height: 1.5; font-size: 0.95rem; color: var(--text-main); font-weight: 500;">
                        <strong>You (Student):</strong> ${content}
                    </div>
                `;
            }

            chatLog.appendChild(bubble);
            chatLog.scrollTop = chatLog.scrollHeight;
        }
    </script>
</x-layout>
