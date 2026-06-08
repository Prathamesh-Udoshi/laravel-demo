<x-layout>
    <x-slot:title>AI Context & Payload Inspector</x-slot>

    <!-- Header Section -->
    <div style="padding: 4rem 3rem; background: #ffffff; border-bottom: 1px solid var(--border);">
        <div style="max-width: 1400px; margin: 0 auto;">
            <span style="font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: var(--primary); letter-spacing: 0.1em; display: block; margin-bottom: 0.75rem;">Developer Diagnostic Playground</span>
            <h2 style="font-size: 3rem; margin-bottom: 0.5rem; letter-spacing: -0.05em; font-family: 'Outfit', sans-serif; font-weight: 900; color: var(--text-main);">AI Context & Payload Inspector</h2>
            <p style="font-size: 1.1rem; font-weight: 500; color: var(--text-muted); max-width: 900px;">
                Learn how request-scoped metadata is tracked internally via Laravel's <strong>Context</strong> facade, how system instructions shape the AI's response, and how to inspect outbound API payloads.
            </p>
        </div>
    </div>

    <!-- Educational Explanation Cards -->
    <div style="max-width: 1400px; margin: 2rem auto 0 auto; padding: 0 3rem; display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        <!-- Card 1: Laravel Context -->
        <div style="background: #ffffff; border: 1.5px solid var(--border); padding: 1.75rem; border-left: 4px solid var(--primary); border-radius: 4px;">
            <h3 style="font-size: 1.15rem; font-weight: 800; margin-top: 0; margin-bottom: 0.5rem; color: var(--text-main); font-family: 'Outfit', sans-serif; display: flex; align-items: center; gap: 0.5rem;">
                📦 Internal Laravel Context
            </h3>
            <p style="font-size: 0.875rem; color: var(--text-muted); line-height: 1.5; margin: 0; font-weight: 500;">
                Laravel's <strong>Context</strong> is a thread-safe, request-scoped local store. It tracks internal metadata (like trace IDs, user segments) in the PHP application layer. It is <strong>never</strong> transmitted to third-party APIs (like Groq or Gemini), keeping internal credentials and state secure.
            </p>
        </div>
        <!-- Card 2: AI System Context -->
        <div style="background: #ffffff; border: 1.5px solid var(--border); padding: 1.75rem; border-left: 4px solid #10b981; border-radius: 4px;">
            <h3 style="font-size: 1.15rem; font-weight: 800; margin-top: 0; margin-bottom: 0.5rem; color: var(--text-main); font-family: 'Outfit', sans-serif; display: flex; align-items: center; gap: 0.5rem;">
                ✨ AI System Context
            </h3>
            <p style="font-size: 0.875rem; color: var(--text-muted); line-height: 1.5; margin: 0; font-weight: 500;">
                AI Context (system instructions) is explicitly passed in the <strong>outbound HTTP Request Body</strong> payload. It guides the LLM on how to behave. You can view this instructions string inside the captured request body payload under the <strong>Outbound Payload Traces</strong> tab.
            </p>
        </div>
    </div>

    <!-- Workspace Layout -->
    <div style="max-width: 1400px; margin: 0 auto; width: 100%; padding: 2rem 3rem 4rem 3rem; display: grid; grid-template-columns: 1.1fr 1.9fr; gap: 3rem; align-items: start;">
        
        <!-- Left Panel: Input & Context Settings -->
        <div class="card animate-fade-in" style="background: #ffffff; border: 2px solid var(--border); padding: 2.5rem; display: flex; flex-direction: column; gap: 2rem;">
            <h3 style="font-size: 1.35rem; font-weight: 800; border-bottom: 2px solid var(--border); padding-bottom: 0.75rem; margin: 0; font-family: 'Outfit', sans-serif; display: flex; align-items: center; gap: 0.5rem; color: var(--text-main);">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.1a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                Execution Settings
            </h3>

            <form id="inspectorForm" style="display: flex; flex-direction: column; gap: 1.75rem;">
                @csrf

                <!-- Provider Selection -->
                <div>
                    <label style="font-weight: 800; font-size: 0.75rem; text-transform: uppercase; color: var(--primary); margin-bottom: 0.5rem; display: block; letter-spacing: 0.05em;">AI Provider</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <label class="provider-radio-label">
                            <input type="radio" name="provider" value="groq" checked style="margin-right: 0.5rem;">
                            <strong>Groq</strong>
                            <span style="font-size: 0.75rem; color: var(--text-muted); display: block; font-weight: 500;">llama-3.1-8b-instant</span>
                        </label>
                        <label class="provider-radio-label">
                            <input type="radio" name="provider" value="gemini" style="margin-right: 0.5rem;">
                            <strong>Gemini</strong>
                            <span style="font-size: 0.75rem; color: var(--text-muted); display: block; font-weight: 500;">gemini-3-flash-preview</span>
                        </label>
                    </div>
                </div>

                <!-- System Instructions -->
                <div>
                    <label for="system_context" style="font-weight: 800; font-size: 0.75rem; text-transform: uppercase; color: var(--primary); margin-bottom: 0.5rem; display: block; letter-spacing: 0.05em;">System Context (Instructions)</label>
                    <textarea 
                        id="system_context" 
                        name="system_context" 
                        rows="2"
                        placeholder="e.g., You are a strict academic editor. Answer concisely in bullet points..."
                        style="width: 100%; padding: 0.85rem 1rem; border: 2px solid var(--border); font-size: 0.95rem; font-family: inherit; resize: vertical;"
                    >You are a friendly, highly concise tech mentor.</textarea>
                </div>

                <!-- User Prompt -->
                <div>
                    <label for="prompt" style="font-weight: 800; font-size: 0.75rem; text-transform: uppercase; color: var(--primary); margin-bottom: 0.5rem; display: block; letter-spacing: 0.05em;">User Prompt</label>
                    <textarea 
                        id="prompt" 
                        name="prompt" 
                        rows="3"
                        placeholder="e.g., Explain routing in Laravel."
                        style="width: 100%; padding: 0.85rem 1rem; border: 2px solid var(--border); font-size: 0.95rem; font-family: inherit; resize: vertical;"
                        required
                    >Explain the difference between a Controller and a Middleware in one sentence.</textarea>
                </div>

                <!-- Custom Laravel Context Key-Values -->
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                        <label style="font-weight: 800; font-size: 0.75rem; text-transform: uppercase; color: var(--primary); display: block; letter-spacing: 0.05em; margin: 0;">Custom Laravel Context (Local metadata)</label>
                        <button type="button" id="addContextBtn" style="padding: 0.25rem 0.6rem; border: 1.5px solid var(--primary); background: transparent; color: var(--primary); font-size: 0.75rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 0.25rem;">
                            ➕ Add Variable
                        </button>
                    </div>
                    
                    <p style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500; margin-top: -0.25rem; margin-bottom: 0.75rem;">
                        These variables reside in Laravel's request-scoped <code>Context</code> store and are accessed locally at the end of the request thread.
                    </p>

                    <!-- Key-Value Rows Container -->
                    <div id="contextRows" style="display: flex; flex-direction: column; gap: 0.6rem;">
                        <!-- Default key-value inputs -->
                        <div class="context-row" style="display: flex; gap: 0.5rem; align-items: center;">
                            <input type="text" name="custom_context[0][key]" placeholder="Key" value="user_tier" style="flex: 1; padding: 0.5rem 0.75rem; border: 2.5px dashed var(--border); font-size: 0.85rem; font-family: monospace;">
                            <input type="text" name="custom_context[0][value]" placeholder="Value" value="premium" style="flex: 1; padding: 0.5rem 0.75rem; border: 2.5px dashed var(--border); font-size: 0.85rem; font-family: monospace;">
                            <button type="button" class="remove-row-btn" style="background: transparent; border: none; color: #ef4444; font-size: 1.1rem; cursor: pointer; padding: 0.25rem;">✕</button>
                        </div>
                        <div class="context-row" style="display: flex; gap: 0.5rem; align-items: center;">
                            <input type="text" name="custom_context[1][key]" placeholder="Key" value="trace_id" style="flex: 1; padding: 0.5rem 0.75rem; border: 2.5px dashed var(--border); font-size: 0.85rem; font-family: monospace;">
                            <input type="text" name="custom_context[1][value]" placeholder="Value" value="tx_9921_abc" style="flex: 1; padding: 0.5rem 0.75rem; border: 2.5px dashed var(--border); font-size: 0.85rem; font-family: monospace;">
                            <button type="button" class="remove-row-btn" style="background: transparent; border: none; color: #ef4444; font-size: 1.1rem; cursor: pointer; padding: 0.25rem;">✕</button>
                        </div>
                    </div>
                </div>

                <button 
                    type="submit"
                    id="submitBtn"
                    style="width: 100%; padding: 1.25rem 2rem; background: var(--primary); color: white; font-weight: 700; border: none; font-size: 1.05rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.75rem;"
                >
                    <span id="buttonText">Send Request & Trace Payload</span>
                    <div id="spinner" class="hidden" style="width: 18px; height: 18px; border: 2.5px solid white; border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                </button>
            </form>
        </div>

        <!-- Right Panel: Results, Context & Payload Traces -->
        <div style="display: flex; flex-direction: column; gap: 2rem;">
            
            <!-- Result Console Header Tabs -->
            <div style="background: #ffffff; border: 2px solid var(--border); display: flex; flex-direction: column;">
                <div style="display: flex; border-bottom: 2px solid var(--border); background: #f8fafc;">
                    <button class="tab-btn active" onclick="switchMainTab('responseTab')">✨ AI Response</button>
                    <button class="tab-btn" onclick="switchMainTab('contextTab')">📦 Laravel Context</button>
                    <button class="tab-btn" onclick="switchMainTab('payloadTab')">🔌 Outbound Payload Traces</button>
                </div>

                <!-- Tab 1: AI Response -->
                <div id="responseTab" class="tab-content active-content" style="padding: 2rem; min-height: 350px;">
                    <div id="welcomeMessage" style="text-align: center; color: var(--text-muted); padding: 4rem 1rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 1rem; opacity: 0.6; color: var(--primary);"><path d="m21 16-4 4-4-4"/><path d="M17 20V4"/><path d="m3 8 4-4 4 4"/><path d="M7 4v16"/></svg>
                        <h4 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 0.25rem; color: var(--text-main);">Console Idle</h4>
                        <p style="font-size: 0.9rem; margin: 0;">Fill in your settings on the left and submit to initiate the trace.</p>
                    </div>

                    <!-- AI Response Display -->
                    <div id="responseContainer" class="hidden">
                        <h4 style="font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: var(--primary); margin-top: 0; margin-bottom: 1rem; letter-spacing: 0.05em;">
                            Response Text
                        </h4>
                        <div id="aiResponseText" style="font-size: 1.1rem; line-height: 1.6; color: var(--text-main); font-weight: 500; font-family: 'Plus Jakarta Sans', sans-serif;"></div>
                    </div>
                </div>

                <!-- Tab 2: Laravel Context -->
                <div id="contextTab" class="tab-content" style="padding: 2rem; display: none; min-height: 350px;">
                    <h4 style="font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: var(--primary); margin-top: 0; margin-bottom: 0.5rem; letter-spacing: 0.05em;">Laravel Context Store</h4>
                    <p style="font-size: 0.85rem; color: var(--text-muted); font-weight: 500; margin-bottom: 1.5rem;">
                        This shows the exact state of Laravel's <code>Context</code> facade collected at the end of the request thread. Notice that these values are kept strictly local to your app and not leaked to the outbound HTTP headers.
                    </p>

                    <div id="contextValuesContainer" style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <p style="font-style: italic; color: var(--text-muted); margin: 0; font-size: 0.9rem;">No context variables registered yet.</p>
                    </div>
                </div>

                <!-- Tab 3: Outbound Payload Traces -->
                <div id="payloadTab" class="tab-content" style="padding: 2rem; display: none; min-height: 350px;">
                    <h4 style="font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: var(--primary); margin-top: 0; margin-bottom: 0.5rem; letter-spacing: 0.05em;">Outbound HTTP Payloads</h4>
                    <p style="font-size: 0.85rem; color: var(--text-muted); font-weight: 500; margin-bottom: 1.5rem;">
                        These are the actual HTTP request & response payloads captured by our listener hook from the Laravel HTTP client. Look inside <strong>Request Body</strong> to see how your <em>System Context (Instructions)</em> is structured and passed to the LLM.
                    </p>

                    <div id="httpCallsList" style="display: flex; flex-direction: column; gap: 1.5rem;">
                        <p style="font-style: italic; color: var(--text-muted); margin: 0; font-size: 0.9rem;">No HTTP trace captured yet.</p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Interactive Styling -->
    <style>
        .hidden {
            display: none !important;
        }

        .provider-radio-label {
            border: 2px solid var(--border);
            padding: 1rem;
            cursor: pointer;
            display: block;
            transition: all 0.2s ease;
        }

        .provider-radio-label:has(input:checked) {
            border-color: var(--primary);
            background: rgba(79, 70, 229, 0.04);
        }

        /* Tabs Styling */
        .tab-btn {
            flex: 1;
            padding: 1.1rem 1rem;
            border: none;
            background: transparent;
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
            border-bottom: 3px solid transparent;
            font-family: 'Outfit', sans-serif;
        }

        .tab-btn:hover {
            color: var(--primary);
            background: rgba(79, 70, 229, 0.02);
        }

        .tab-btn.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
            background: #ffffff;
        }

        /* Key-Value styling */
        .badge-key {
            background: #f1f5f9;
            color: #475569;
            padding: 0.4rem 0.75rem;
            font-weight: 700;
            font-family: monospace;
            font-size: 0.8rem;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
        }

        .badge-val {
            background: #eef2ff;
            color: #4f46e5;
            padding: 0.4rem 0.75rem;
            font-weight: 600;
            font-family: monospace;
            font-size: 0.85rem;
            border: 1px solid #c7d2fe;
            border-radius: 4px;
            word-break: break-all;
        }

        /* HTTP Call Card Styling */
        .http-card {
            border: 1.5px solid var(--border);
            background: #ffffff;
            border-radius: 4px;
            overflow: hidden;
        }

        .http-header-summary {
            padding: 1rem;
            background: #f8fafc;
            border-bottom: 1.5px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 700;
            font-size: 0.875rem;
        }

        .http-method {
            background: var(--primary);
            color: white;
            padding: 0.25rem 0.6rem;
            border-radius: 3px;
            font-family: monospace;
            font-size: 0.75rem;
        }

        .http-status {
            padding: 0.25rem 0.6rem;
            border-radius: 3px;
            font-family: monospace;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .status-success {
            background: #d1fae5;
            color: #065f46;
        }

        .status-error {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Payload Tabs inside call card */
        .subtab-btn {
            padding: 0.6rem 1rem;
            font-size: 0.75rem;
            font-weight: 700;
            border: none;
            background: transparent;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            color: var(--text-muted);
            transition: all 0.2s;
        }

        .subtab-btn.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }

        .code-block {
            background: #0f172a;
            color: #cbd5e1;
            padding: 1.25rem;
            margin: 0;
            font-family: 'Consolas', 'Courier New', monospace;
            font-size: 0.85rem;
            line-height: 1.5;
            overflow-x: auto;
            border-bottom-left-radius: 4px;
            border-bottom-right-radius: 4px;
            max-height: 450px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>

    <script>
        // Main Tab Switcher
        function switchMainTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => {
                el.style.display = 'none';
            });
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('active');
            });
            
            const targetEl = document.getElementById(tabId);
            if (targetEl) {
                targetEl.style.display = 'block';
            }
            
            // Find button trigger and set active
            const clickedBtn = Array.from(document.querySelectorAll('.tab-btn')).find(btn => {
                return btn.getAttribute('onclick').includes(tabId);
            });
            if (clickedBtn) {
                clickedBtn.classList.add('active');
            }
        }

        // Subtab switcher for inner Call Details
        function switchSubTab(btn, paneClass) {
            const card = btn.closest('.http-card');
            
            // Remove active from sibling subtab buttons
            card.querySelectorAll('.subtab-btn').forEach(b => b.classList.remove('active'));
            // Hide all subtab content panes in this card
            card.querySelectorAll('.subtab-pane').forEach(pane => pane.classList.add('hidden'));
            
            // Add active to current button
            btn.classList.add('active');
            // Show corresponding pane
            card.querySelector('.' + paneClass).classList.remove('hidden');
        }

        // Add dynamic context variables logic
        let contextIndex = 2;
        const contextRowsContainer = document.getElementById('contextRows');
        const addContextBtn = document.getElementById('addContextBtn');

        addContextBtn.addEventListener('click', () => {
            const newRow = document.createElement('div');
            newRow.className = 'context-row';
            newRow.style.display = 'flex';
            newRow.style.gap = '0.5rem';
            newRow.style.alignItems = 'center';
            newRow.innerHTML = `
                <input type="text" name="custom_context[${contextIndex}][key]" placeholder="Key" style="flex: 1; padding: 0.5rem 0.75rem; border: 2.5px dashed var(--border); font-size: 0.85rem; font-family: monospace;">
                <input type="text" name="custom_context[${contextIndex}][value]" placeholder="Value" style="flex: 1; padding: 0.5rem 0.75rem; border: 2.5px dashed var(--border); font-size: 0.85rem; font-family: monospace;">
                <button type="button" class="remove-row-btn" style="background: transparent; border: none; color: #ef4444; font-size: 1.1rem; cursor: pointer; padding: 0.25rem;">✕</button>
            `;
            contextRowsContainer.appendChild(newRow);
            contextIndex++;
        });

        // Delegate deletion event for custom context rows
        contextRowsContainer.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-row-btn')) {
                e.target.closest('.context-row').remove();
            }
        });

        // Form Submit Ajax Handler
        const form = document.getElementById('inspectorForm');
        const submitBtn = document.getElementById('submitBtn');
        const buttonText = document.getElementById('buttonText');
        const spinner = document.getElementById('spinner');

        // Result displays
        const welcomeMessage = document.getElementById('welcomeMessage');
        const responseContainer = document.getElementById('responseContainer');
        const aiResponseText = document.getElementById('aiResponseText');
        const contextValuesContainer = document.getElementById('contextValuesContainer');
        const httpCallsList = document.getElementById('httpCallsList');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Setup loading state
            buttonText.textContent = 'Executing & Intercepting Outbound Payloads...';
            spinner.classList.remove('hidden');
            submitBtn.disabled = true;

            const formData = new FormData(form);

            try {
                const res = await fetch('{{ route("ai.context-inspector.send") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });

                const data = await res.json();

                if (data.success || data.response) {
                    welcomeMessage.classList.add('hidden');
                    
                    // 1. Populate AI Response
                    aiResponseText.innerHTML = data.response.replace(/\n/g, '<br>');
                    responseContainer.classList.remove('hidden');

                    // 2. Populate Laravel Context variables
                    contextValuesContainer.innerHTML = '';
                    const contextKeys = Object.keys(data.laravel_context);
                    if (contextKeys.length > 0) {
                        contextKeys.forEach(key => {
                            const pairRow = document.createElement('div');
                            pairRow.style.display = 'flex';
                            pairRow.style.gap = '1rem';
                            pairRow.style.alignItems = 'center';
                            pairRow.innerHTML = `
                                <span class="badge-key">${escapeHtml(key)}</span>
                                <span style="color: var(--text-muted); font-size: 0.9rem;">➔</span>
                                <span class="badge-val">${escapeHtml(data.laravel_context[key])}</span>
                            `;
                            contextValuesContainer.appendChild(pairRow);
                        });
                    } else {
                        contextValuesContainer.innerHTML = '<p style="font-style: italic; color: var(--text-muted); margin: 0; font-size: 0.9rem;">No custom context keys registered.</p>';
                    }

                    // 3. Populate HTTP Call Payload Traces
                    httpCallsList.innerHTML = '';
                    if (data.http_calls && data.http_calls.length > 0) {
                        data.http_calls.forEach((call, index) => {
                            const statusClass = call.response_status && call.response_status >= 200 && call.response_status < 300 
                                ? 'status-success' 
                                : 'status-error';

                            const card = document.createElement('div');
                            card.className = 'http-card';
                            card.innerHTML = `
                                <div class="http-header-summary">
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <span class="http-method">${escapeHtml(call.method)}</span>
                                        <span style="font-family: monospace; color: var(--text-main); font-size: 0.85rem; word-break: break-all;">${escapeHtml(call.url)}</span>
                                    </div>
                                    <span class="http-status ${statusClass}">Status: ${call.response_status || 'Connection Failed'}</span>
                                </div>
                                <div style="display: flex; background: #f1f5f9; border-bottom: 1px solid var(--border);">
                                    <button type="button" class="subtab-btn active" onclick="switchSubTab(this, 'req-body')">Request Body</button>
                                    <button type="button" class="subtab-btn" onclick="switchSubTab(this, 'req-headers')">Request Headers</button>
                                    <button type="button" class="subtab-btn" onclick="switchSubTab(this, 'res-body')">Response Body</button>
                                    <button type="button" class="subtab-btn" onclick="switchSubTab(this, 'res-headers')">Response Headers</button>
                                </div>
                                
                                <div class="req-body subtab-pane">
                                    <pre class="code-block"><code>${escapeHtml(JSON.stringify(call.request_body, null, 2))}</code></pre>
                                </div>
                                <div class="req-headers subtab-pane hidden">
                                    <pre class="code-block"><code>${escapeHtml(JSON.stringify(call.request_headers, null, 2))}</code></pre>
                                </div>
                                <div class="res-body subtab-pane hidden">
                                    <pre class="code-block"><code>${escapeHtml(JSON.stringify(call.response_body, null, 2))}</code></pre>
                                </div>
                                <div class="res-headers subtab-pane hidden">
                                    <pre class="code-block"><code>${escapeHtml(JSON.stringify(call.response_headers, null, 2))}</code></pre>
                                </div>
                            `;
                            httpCallsList.appendChild(card);
                        });
                    } else {
                        httpCallsList.innerHTML = '<p style="font-style: italic; color: var(--text-muted); margin: 0; font-size: 0.9rem;">No outbound API calls were triggered or intercepted.</p>';
                    }

                    // Auto-focus the main AI Response tab
                    switchMainTab('responseTab');

                } else {
                    // Fail state
                    welcomeMessage.classList.add('hidden');
                    responseContainer.classList.remove('hidden');
                    aiResponseText.innerHTML = `<span style="color: #ef4444; font-weight: 700;">Error calling AI:</span><br>${escapeHtml(data.error)}`;
                }

            } catch (err) {
                welcomeMessage.classList.add('hidden');
                responseContainer.classList.remove('hidden');
                aiResponseText.innerHTML = `<span style="color: #ef4444; font-weight: 700;">Unexpected Error:</span><br>${escapeHtml(err.message)}`;
            } finally {
                buttonText.textContent = 'Send Request & Trace Payload';
                spinner.classList.add('hidden');
                submitBtn.disabled = false;
            }
        });

        // Helper helper to escape html tags safely
        function escapeHtml(unsafe) {
            if (typeof unsafe !== 'string') {
                return typeof unsafe === 'object' ? JSON.stringify(unsafe, null, 2) : String(unsafe);
            }
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }
    </script>
</x-layout>
