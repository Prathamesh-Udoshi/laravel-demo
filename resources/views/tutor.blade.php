<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AI Lecture Tutor - Semantic Course Assistant</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #020617 100%);
            --panel-bg: rgba(30, 41, 59, 0.45);
            --panel-border: rgba(255, 255, 255, 0.08);
            --glass-blur: blur(16px);
            
            --primary: #818cf8;
            --primary-glow: rgba(129, 140, 248, 0.3);
            --secondary: #c084fc;
            --accent: #22d3ee;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --sidebar-width: 320px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-gradient);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            overflow: hidden;
        }

        /* Layout */
        .container {
            display: flex;
            width: 100vw;
            height: 100vh;
        }

        /* Sidebar styling */
        .sidebar {
            width: var(--sidebar-width);
            background: rgba(15, 23, 42, 0.6);
            border-right: 1px solid var(--panel-border);
            backdrop-filter: var(--glass-blur);
            display: flex;
            flex-direction: column;
            padding: 1.5rem;
            z-index: 10;
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 2.5rem;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #fff;
            box-shadow: 0 4px 14px var(--primary-glow);
        }

        .logo-text h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            background: linear-gradient(to right, #fff, var(--text-muted));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .logo-text span {
            font-size: 0.75rem;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600;
        }

        .section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .course-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            overflow-y: auto;
            flex: 1;
            padding-right: 0.25rem;
        }

        .course-list::-webkit-scrollbar {
            width: 4px;
        }
        .course-list::-webkit-scrollbar-thumb {
            background: var(--panel-border);
            border-radius: 4px;
        }

        .course-item {
            padding: 1rem;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--panel-border);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .course-item:hover, .course-item.active {
            background: rgba(129, 140, 248, 0.08);
            border-color: rgba(129, 140, 248, 0.4);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .course-item.active {
            background: linear-gradient(135deg, rgba(129, 140, 248, 0.15), rgba(192, 132, 252, 0.05));
        }

        .course-item h3 {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: #fff;
            line-height: 1.4;
        }

        .course-item p {
            font-size: 0.75rem;
            color: var(--text-muted);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.5;
        }

        /* Main Chat Area */
        .chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
            position: relative;
            background: transparent;
        }

        .header-panel {
            padding: 1.5rem 2.5rem;
            border-bottom: 1px solid var(--panel-border);
            backdrop-filter: var(--glass-blur);
            background: rgba(15, 23, 42, 0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-info h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.35rem;
            font-weight: 600;
            color: #fff;
        }

        .header-info p {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 0.2rem;
        }

        .badge {
            background: rgba(34, 211, 238, 0.1);
            color: var(--accent);
            border: 1px solid rgba(34, 211, 238, 0.2);
            padding: 0.25rem 0.75rem;
            border-radius: 99px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            scroll-behavior: smooth;
        }

        .messages-container::-webkit-scrollbar {
            width: 6px;
        }
        .messages-container::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 99px;
        }

        .message-row {
            display: flex;
            width: 100%;
        }

        .message-row.user {
            justify-content: flex-end;
        }

        .message-bubble {
            max-width: 70%;
            padding: 1.2rem 1.5rem;
            border-radius: 20px;
            line-height: 1.6;
            font-size: 0.95rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            animation: fadeInBubble 0.3s ease-out;
        }

        @keyframes fadeInBubble {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .message-row.user .message-bubble {
            background: linear-gradient(135deg, var(--primary), #6366f1);
            color: #fff;
            border-bottom-right-radius: 4px;
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.3);
        }

        .message-row.assistant .message-bubble {
            background: var(--panel-bg);
            border: 1px solid var(--panel-border);
            color: #e2e8f0;
            border-bottom-left-radius: 4px;
            backdrop-filter: var(--glass-blur);
        }

        .message-bubble code {
            background: rgba(0, 0, 0, 0.2);
            padding: 0.2rem 0.4rem;
            border-radius: 4px;
            font-family: monospace;
            font-size: 0.85rem;
            color: #fda4af;
        }

        .message-bubble pre {
            background: rgba(0, 0, 0, 0.3);
            padding: 1rem;
            border-radius: 8px;
            overflow-x: auto;
            margin: 0.8rem 0;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .message-bubble pre code {
            background: transparent;
            padding: 0;
            color: #e2e8f0;
            font-size: 0.85rem;
        }

        /* Input Area */
        .input-panel {
            padding: 1.5rem 2.5rem;
            background: rgba(15, 23, 42, 0.4);
            border-top: 1px solid var(--panel-border);
            backdrop-filter: var(--glass-blur);
        }

        .input-wrapper {
            display: flex;
            gap: 1rem;
            position: relative;
        }

        .chat-input {
            flex: 1;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--panel-border);
            border-radius: 16px;
            padding: 1.2rem 1.5rem;
            color: #fff;
            font-family: inherit;
            font-size: 0.95rem;
            outline: none;
            transition: all 0.3s;
            box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .chat-input:focus {
            border-color: rgba(129, 140, 248, 0.6);
            box-shadow: 0 0 15px rgba(129, 140, 248, 0.15), inset 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .send-button {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            color: #fff;
            padding: 0 1.8rem;
            border-radius: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px var(--primary-glow);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .send-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(129, 140, 248, 0.4);
        }

        .send-button:active {
            transform: translateY(0);
        }

        .send-button:disabled {
            background: #334155;
            color: var(--text-muted);
            box-shadow: none;
            cursor: not-allowed;
            transform: none;
        }

        /* RAG Debug Inspector panel (Right side) */
        .inspector-panel {
            width: 360px;
            background: rgba(15, 23, 42, 0.4);
            border-left: 1px solid var(--panel-border);
            backdrop-filter: var(--glass-blur);
            display: flex;
            flex-direction: column;
            padding: 1.5rem;
            overflow-y: auto;
        }

        .inspector-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-family: 'Outfit', sans-serif;
            font-size: 1.1rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--panel-border);
        }

        .inspector-icon {
            color: var(--accent);
        }

        .snippet-card {
            background: rgba(30, 41, 59, 0.3);
            border: 1px solid var(--panel-border);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            font-size: 0.8rem;
            line-height: 1.5;
            animation: fadeIn 0.5s ease;
        }

        .snippet-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--primary);
        }

        .snippet-score {
            background: rgba(34, 211, 238, 0.12);
            color: var(--accent);
            padding: 0.1rem 0.4rem;
            border-radius: 4px;
            font-size: 0.7rem;
        }

        .snippet-text {
            color: var(--text-muted);
            font-style: italic;
        }

        .empty-inspector {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex: 1;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.85rem;
            gap: 1rem;
        }

        .empty-icon {
            font-size: 2.5rem;
            opacity: 0.3;
        }

        /* Typing indicator */
        .typing-indicator {
            display: flex;
            gap: 0.4rem;
            padding: 0.3rem 0;
            align-items: center;
            justify-content: center;
        }

        .dot {
            width: 8px;
            height: 8px;
            background: var(--text-muted);
            border-radius: 50%;
            animation: bounce 1.4s infinite ease-in-out both;
        }

        .dot:nth-child(1) { animation-delay: -0.32s; }
        .dot:nth-child(2) { animation-delay: -0.16s; }

        @keyframes bounce {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1.0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>

<div class="container">
    
    <!-- Sidebar: Course Selector -->
    <aside class="sidebar">
        <div class="logo-area">
            <div class="logo-icon">AI</div>
            <div class="logo-text">
                <h1>Lecture Tutor</h1>
                <span>Laravel 13 RAG</span>
            </div>
        </div>
        
        <h2 class="section-title">Select a Course</h2>
        <ul class="course-list" id="courseList">
            <li class="course-item active" data-course-id="" onclick="selectCourse(this, '')">
                <h3>All Courses</h3>
                <p>Query transcripts across all registered courses semantically.</p>
            </li>
            @foreach($courses as $course)
                <li class="course-item" data-course-id="{{ $course->id }}" onclick="selectCourse(this, '{{ $course->id }}')">
                    <h3>{{ $course->title }}</h3>
                    <p>{{ $course->description }}</p>
                </li>
            @endforeach
        </ul>
    </aside>

    <!-- Main Chat Window -->
    <main class="chat-area">
        <header class="header-panel">
            <div class="header-info">
                <h2 id="currentCourseTitle">All Courses</h2>
                <p>AI lecture assistant powered by Gemini Embeddings & Vector Search</p>
            </div>
            <div>
                <span class="badge" id="vectorStatus">Active Fallback Mode</span>
            </div>
        </header>

        <!-- Messages Area -->
        <section class="messages-container" id="messagesContainer">
            <div class="message-row assistant">
                <div class="message-bubble">
                    Hello! I'm your AI Lecture Tutor. Ask me any questions about your lectures, transcripts, or notes! 
                    For example, you could ask me: 
                    <br><br>
                    <code>"What did the instructor say about database indexes in week 2?"</code> or 
                    <code>"How do routes work in Laravel?"</code>
                </div>
            </div>
        </section>

        <!-- Input Box -->
        <footer class="input-panel">
            <form id="chatForm" onsubmit="sendMessage(event)" class="input-wrapper">
                <input 
                    type="text" 
                    id="chatInput" 
                    class="chat-input" 
                    placeholder="Ask a question about the lectures..." 
                    autocomplete="off" 
                    required
                >
                <button type="submit" id="sendBtn" class="send-button">
                    <span>Ask AI</span>
                </button>
            </form>
        </footer>
    </main>

    <!-- Right Panel: RAG Context Inspector -->
    <aside class="inspector-panel" id="inspectorPanel">
        <h3 class="inspector-title">
            <span class="inspector-icon">🔍</span> Semantic Context Used
        </h3>
        <div class="empty-inspector" id="emptyInspector">
            <div class="empty-icon">📂</div>
            <p>Retrieved segments will show up here in real-time when you ask a question.</p>
        </div>
        <div id="inspectorContent" style="display: none;"></div>
    </aside>

</div>

<script>
    let activeCourseId = '';
    let conversationId = null;

    function selectCourse(element, courseId) {
        document.querySelectorAll('.course-item').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
        activeCourseId = courseId;
        
        const title = element.querySelector('h3').textContent;
        document.getElementById('currentCourseTitle').textContent = title;
    }

    function appendMessage(sender, text) {
        const container = document.getElementById('messagesContainer');
        const row = document.createElement('div');
        row.className = `message-row ${sender}`;
        
        const bubble = document.createElement('div');
        bubble.className = 'message-bubble';
        
        // Handle code snippets display formatted
        let formattedText = text
            .replace(/`([^`]+)`/g, '<code>$1</code>')
            .replace(/```([a-z]*)\n([\s\S]+?)\n```/g, '<pre><code>$2</code></pre>');
            
        bubble.innerHTML = formattedText;
        row.appendChild(bubble);
        container.appendChild(row);
        container.scrollTop = container.scrollHeight;
    }

    function showTypingIndicator() {
        const container = document.getElementById('messagesContainer');
        const row = document.createElement('div');
        row.className = 'message-row assistant';
        row.id = 'typingIndicatorRow';
        
        const bubble = document.createElement('div');
        bubble.className = 'message-bubble';
        
        const indicator = document.createElement('div');
        indicator.className = 'typing-indicator';
        indicator.innerHTML = '<span class="dot"></span><span class="dot"></span><span class="dot"></span>';
        
        bubble.appendChild(indicator);
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
            const card = document.createElement('div');
            card.className = 'snippet-card';
            
            const header = document.createElement('div');
            header.className = 'snippet-header';
            header.innerHTML = `
                <span>${item.lesson} (W${item.week})</span>
                <span class="snippet-score">Similarity: ${item.similarity}</span>
            `;
            
            const text = document.createElement('div');
            text.className = 'snippet-text';
            text.textContent = `"${item.snippet}"`;

            card.appendChild(header);
            card.appendChild(text);
            contentEl.appendChild(card);
        });
    }

    async function sendMessage(event) {
        event.preventDefault();
        
        const inputEl = document.getElementById('chatInput');
        const message = inputEl.value.trim();
        if (!message) return;

        // Reset input and disable UI
        inputEl.value = '';
        inputEl.disabled = true;
        const sendBtn = document.getElementById('sendBtn');
        sendBtn.disabled = true;

        // Show user message
        appendMessage('user', message);
        
        // Show typing animation
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
                throw new Error('Network response was not ok');
            }

            const data = await response.json();
            
            // Remove typing bubble
            removeTypingIndicator();
            
            // Append assistant response
            appendMessage('assistant', data.reply);
            
            // Update conversation tracker
            conversationId = data.conversation_id;
            
            // Update semantic context inspector panel
            updateInspector(data.context_used);

        } catch (error) {
            console.error('Error:', error);
            removeTypingIndicator();
            appendMessage('assistant', 'Sorry, I couldn\'t process that request. Please make sure your server is running and check console log.');
        } finally {
            inputEl.disabled = false;
            sendBtn.disabled = false;
            inputEl.focus();
        }
    }
</script>

</body>
</html>
