<x-layout>
    <x-slot:title>AI Lecture Tutor</x-slot>

    <!-- Top Intro Hero Section -->
    <div style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-bottom: 2px solid var(--border); padding: 3rem 0;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 1.5rem;">
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.4rem 0.8rem; background: rgba(79, 70, 229, 0.06); border: 1px solid rgba(79, 70, 229, 0.12); color: var(--primary); font-size: 0.75rem; font-weight: 800; margin-bottom: 1rem; letter-spacing: 0.05em; text-transform: uppercase;">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                AI Lecture Tutor & Study Assistant
            </div>
            <h1 style="font-size: 2.75rem; font-weight: 900; letter-spacing: -0.04em; color: var(--text-main); margin-bottom: 0.75rem; font-family: 'Outfit', sans-serif;">
                Semantic Lecture <span style="background: linear-gradient(135deg, var(--primary), var(--accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Search & Q&A</span>
            </h1>
            <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 750px; font-weight: 500; line-height: 1.6;">
                Ask questions about your courses. The system generates embeddings, performs a vector search over lecture transcripts and notes, and answers using exact matches.
            </p>
        </div>
    </div>

    <!-- Main Workspace Grid -->
    <div style="max-width: 1200px; margin: 0 auto; width: 100%; padding: 3rem 1.5rem; display: grid; grid-template-columns: 1fr 2fr; gap: 2.5rem; align-items: start;">
        
        <!-- Left Sidebar: Course Selector & Live Inspector -->
        <div style="display: flex; flex-direction: column; gap: 2rem;">
            
            <!-- Course Selector Card -->
            <div class="card" style="padding: 2rem; margin-bottom: 0; border-top: 4px solid var(--primary);">
                <h3 style="font-size: 1.2rem; font-weight: 800; margin-bottom: 1.25rem; font-family: 'Outfit', sans-serif; display: flex; align-items: center; gap: 0.5rem;">
                    <span>📚</span> Select Course Context
                </h3>
                
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <button 
                        type="button" 
                        class="course-btn active" 
                        onclick="selectCourse(this, '')"
                        style="width: 100%; justify-content: flex-start; text-align: left; padding: 0.85rem 1.25rem; background: var(--primary); color: white; border: none; font-weight: 700; font-size: 0.9rem; transition: all 0.2s;"
                    >
                        All Courses
                    </button>
                    
                    @foreach($courses as $course)
                        <button 
                            type="button" 
                            class="course-btn" 
                            onclick="selectCourse(this, '{{ $course->id }}')"
                            style="width: 100%; justify-content: flex-start; text-align: left; padding: 0.85rem 1.25rem; background: white; color: var(--text-main); border: 2px solid var(--border); font-weight: 700; font-size: 0.9rem; transition: all 0.2s;"
                        >
                            {{ $course->title }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Live Semantic Context Inspector Card -->
            <div class="card" style="padding: 2rem; margin-bottom: 0; border-top: 4px solid var(--accent); min-height: 300px; display: flex; flex-direction: column;">
                <h3 style="font-size: 1.2rem; font-weight: 800; margin-bottom: 1.25rem; font-family: 'Outfit', sans-serif; display: flex; align-items: center; gap: 0.5rem;">
                    <span>🔍</span> Context Retrieved (RAG)
                </h3>
                
                <div id="emptyInspector" style="display: flex; flex-direction: column; align-items: center; justify-content: center; flex-grow: 1; text-align: center; color: var(--text-muted); font-size: 0.9rem; gap: 0.75rem; padding: 2rem 0;">
                    <div style="font-size: 2rem; opacity: 0.4;">📂</div>
                    <p style="margin-bottom: 0; line-height: 1.4;">Matching lecture segments will be displayed here in real-time when you ask a question.</p>
                </div>
                
                <div id="inspectorContent" style="display: none; flex-grow: 1; overflow-y: auto; max-height: 350px; padding-right: 0.25rem;"></div>
            </div>
        </div>

        <!-- Right: AI Chat Interface -->
        <div class="card" style="padding: 2.5rem; margin-bottom: 0; display: flex; flex-direction: column; height: 600px; border-top: 4px solid var(--accent);">
            
            <!-- Messages Box -->
            <div id="messagesContainer" style="flex-grow: 1; overflow-y: auto; display: flex; flex-direction: column; gap: 1.25rem; padding-right: 0.5rem; margin-bottom: 1.5rem;">
                <!-- System Greeting -->
                <div style="display: flex; justify-content: flex-start;">
                    <div style="max-width: 85%; padding: 1rem 1.25rem; background: var(--bg-main); border: 1px solid var(--border); color: var(--text-main); font-size: 0.95rem; line-height: 1.6;">
                        Hello! I am your AI Lecture Tutor. Ask me any question related to your class lectures, notes, or transcripts.
                        <br><br>
                        For example: 
                        <br>
                        <code>"Explain database indexing in week 2."</code> or 
                        <code>"What are routes in Laravel?"</code>
                    </div>
                </div>
            </div>

            <!-- Input Form -->
            <form id="chatForm" onsubmit="sendMessage(event)" style="margin-top: auto;">
                <div style="display: flex; gap: 0.75rem; position: relative;">
                    <input 
                        type="text" 
                        id="chatInput" 
                        placeholder="Type your question here..." 
                        style="flex-grow: 1; padding: 1rem 1.25rem; font-size: 0.95rem;" 
                        autocomplete="off" 
                        required
                    >
                    <button type="submit" id="sendBtn" style="padding: 0 2rem; font-weight: 700; white-space: nowrap;">
                        Ask AI
                    </button>
                </div>
            </form>
        </div>

    </div>

    <!-- Page Specific Styles & Scripts -->
    <style>
        .course-btn:hover {
            border-color: var(--primary) !important;
            background: rgba(79, 70, 229, 0.04) !important;
            color: var(--primary) !important;
        }
        
        .course-btn.active {
            background: var(--primary) !important;
            color: white !important;
            border-color: var(--primary) !important;
        }

        .bubble {
            white-space: pre-wrap;
            word-break: break-word;
        }

        .chat-msg-user {
            display: flex;
            justify-content: flex-end;
        }

        .chat-msg-user .bubble {
            max-width: 85%;
            padding: 1rem 1.25rem;
            background: var(--primary);
            color: white;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .chat-msg-assistant {
            display: flex;
            justify-content: flex-start;
        }

        .chat-msg-assistant .bubble {
            max-width: 85%;
            padding: 1rem 1.25rem;
            background: white;
            border: 1px solid var(--border);
            color: var(--text-main);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .bubble code {
            background: rgba(0, 0, 0, 0.05);
            padding: 0.15rem 0.3rem;
            font-family: monospace;
            font-size: 0.85rem;
            color: #b91c1c;
        }

        .bubble pre {
            background: #f1f5f9;
            padding: 0.75rem;
            overflow-x: auto;
            border-left: 3px solid var(--primary);
            margin: 0.75rem 0;
            font-family: monospace;
            font-size: 0.85rem;
        }

        .bubble pre code {
            background: transparent;
            padding: 0;
            color: #334155;
        }

        .snippet-box {
            padding: 1rem;
            background: var(--bg-main);
            border: 1px solid var(--border);
            border-left: 3px solid var(--accent);
            font-size: 0.8rem;
            line-height: 1.5;
            margin-bottom: 0.75rem;
        }

        /* Typing indicator animation */
        .typing-dots {
            display: inline-flex;
            gap: 0.3rem;
            align-items: center;
        }
        .typing-dot {
            width: 6px;
            height: 6px;
            background: var(--text-muted);
            border-radius: 50%;
            animation: bounce 1.4s infinite ease-in-out both;
        }
        .typing-dot:nth-child(1) { animation-delay: -0.32s; }
        .typing-dot:nth-child(2) { animation-delay: -0.16s; }
        @keyframes bounce {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1.0); }
        }
    </style>

    <script>
        let activeCourseId = '';
        let conversationId = null;

        function selectCourse(btn, courseId) {
            document.querySelectorAll('.course-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            activeCourseId = courseId;
        }

        function appendMessage(sender, text) {
            const container = document.getElementById('messagesContainer');
            const row = document.createElement('div');
            row.className = `chat-msg-${sender}`;
            
            const bubble = document.createElement('div');
            bubble.className = 'bubble';
            
            // Basic formatting for Markdown style pre blocks and code inline
            let formattedText = text
                .replace(/```([a-z]*)\n([\s\S]+?)\n```/g, '<pre><code>$2</code></pre>')
                .replace(/`([^`]+)`/g, '<code>$1</code>')
                .replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>')
                .replace(/\*([^*]+)\*/g, '<em>$1</em>');
                
            bubble.innerHTML = formattedText;
            row.appendChild(bubble);
            container.appendChild(row);
            container.scrollTop = container.scrollHeight;
        }

        function showTypingIndicator() {
            const container = document.getElementById('messagesContainer');
            const row = document.createElement('div');
            row.className = 'chat-msg-assistant';
            row.id = 'typingIndicatorRow';
            
            const bubble = document.createElement('div');
            bubble.className = 'bubble';
            bubble.innerHTML = '<div class="typing-dots"><span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span></div>';
            
            row.appendChild(bubble);
            container.appendChild(row);
            container.scrollTop = container.scrollHeight;
        }

        function removeTypingIndicator() {
            const el = document.getElementById('typingIndicatorRow');
            if (el) el.remove();
        }

        function updateInspector(contextList) {
            const emptyEl = document.getElementById('emptyInspector');
            const contentEl = document.getElementById('inspectorContent');
            
            if (!contextList || contextList.length === 0) {
                emptyEl.style.display = 'flex';
                contentEl.style.display = 'none';
                return;
            }

            emptyEl.style.display = 'none';
            contentEl.style.display = 'block';
            contentEl.innerHTML = '';

            contextList.forEach(item => {
                const box = document.createElement('div');
                box.className = 'snippet-box';
                box.innerHTML = `
                    <div style="display:flex; justify-content:space-between; font-weight:700; color:var(--primary); margin-bottom:0.4rem; font-size:0.75rem;">
                        <span>${item.lesson} (W${item.week})</span>
                        <span style="color:var(--accent);">Score: ${item.similarity}</span>
                    </div>
                    <div style="color:var(--text-muted); font-style:italic;">"${item.snippet}"</div>
                `;
                contentEl.appendChild(box);
            });
        }

        async function sendMessage(event) {
            event.preventDefault();
            
            const inputEl = document.getElementById('chatInput');
            const message = inputEl.value.trim();
            if (!message) return;

            // Reset input and disable UI elements
            inputEl.value = '';
            inputEl.disabled = true;
            const sendBtn = document.getElementById('sendBtn');
            sendBtn.disabled = true;

            // Append user query in chat
            appendMessage('user', message);
            
            // Show typing indicator bubble
            showTypingIndicator();

            try {
                const response = await fetch('/tutor/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        message: message,
                        course_id: activeCourseId || null,
                        conversation_id: conversationId
                    })
                });

                if (!response.ok) {
                    throw new Error('Network response error');
                }

                removeTypingIndicator();

                const reader = response.body.getReader();
                const decoder = new TextDecoder();
                let buffer = '';
                let assistantBubble = null;
                let assistantBubbleText = '';

                while (true) {
                    const { done, value } = await reader.read();
                    if (done) break;

                    buffer += decoder.decode(value, { stream: true });
                    const lines = buffer.split('\n');
                    buffer = lines.pop(); // keep last incomplete line

                    for (const line of lines) {
                        const trimmed = line.trim();
                        if (!trimmed) continue;

                        if (trimmed.startsWith('data: ')) {
                            const dataStr = trimmed.slice(6);
                            if (dataStr === '[DONE]') {
                                break;
                            }

                            try {
                                const parsed = JSON.parse(dataStr);
                                if (parsed.type === 'metadata') {
                                    conversationId = parsed.conversation_id;
                                    updateInspector(parsed.context_used);
                                } else if (parsed.type === 'text_delta') {
                                    if (!assistantBubble) {
                                        const container = document.getElementById('messagesContainer');
                                        const row = document.createElement('div');
                                        row.className = 'chat-msg-assistant';
                                        
                                        assistantBubble = document.createElement('div');
                                        assistantBubble.className = 'bubble';
                                        row.appendChild(assistantBubble);
                                        container.appendChild(row);
                                    }

                                    assistantBubbleText += parsed.text;

                                    let formattedText = assistantBubbleText
                                        .replace(/```([a-z]*)\n([\s\S]+?)\n```/g, '<pre><code>$2</code></pre>')
                                        .replace(/`([^`]+)`/g, '<code>$1</code>')
                                        .replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>')
                                        .replace(/\*([^*]+)\*/g, '<em>$1</em>');

                                    assistantBubble.innerHTML = formattedText;
                                    
                                    const container = document.getElementById('messagesContainer');
                                    container.scrollTop = container.scrollHeight;
                                }
                            } catch (e) {
                                console.error('Error parsing stream chunk:', e);
                            }
                        }
                    }
                }

            } catch (error) {
                console.error(error);
                removeTypingIndicator();
                appendMessage('assistant', 'I could not communicate with the backend server. Please verify database connection and credentials.');
            } finally {
                inputEl.disabled = false;
                sendBtn.disabled = false;
                inputEl.focus();
            }
        }
    </script>
</x-layout>
