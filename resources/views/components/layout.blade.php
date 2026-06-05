<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Herd AI' }} — Workspace Hub</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite('resources/css/app.css') 
    
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg-main: #f8fafc;
            --bg-card: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --accent: #6366f1;
            --accent-hover: #4f46e5;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Outfit', system-ui, sans-serif;
            background-color: var(--bg-main);
            color: var(--text-main);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Unified Header & Glassmorphic Nav */
        .workspace-header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 1rem 3rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .logo-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            color: white;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
            font-family: 'Outfit', sans-serif;
        }

        .logo-text {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.04em;
            font-family: 'Outfit', sans-serif;
        }

        .logo-text span {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .workspace-nav ul {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-link {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.925rem;
            font-weight: 600;
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-link:hover {
            color: var(--primary);
            background: rgba(79, 70, 229, 0.05);
        }

        .nav-link.active {
            color: var(--primary);
            background: rgba(79, 70, 229, 0.08);
            font-weight: 700;
        }

        /* Unified Footer */
        .workspace-footer {
            background: #ffffff;
            border-top: 1px solid var(--border);
            padding: 2.5rem 3rem;
            text-align: center;
            margin-top: auto;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .footer-logo {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.1rem;
            color: var(--text-main);
        }

        .footer-copy {
            font-size: 0.875rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .footer-links {
            display: flex;
            gap: 1.5rem;
        }

        .footer-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: var(--primary);
        }

        /* Adjust global layouts */
        main {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            width: 100%;
        }
    </style>
</head>
<body>
    <header class="workspace-header">
        <a href="/" class="logo-group">
            <div class="logo-icon">H</div>
            <div class="logo-text">Herd<span>AI</span> Workspace</div>
        </a>
        <nav class="workspace-nav">
            <ul>
                @php
                    $currentUri = Request::getRequestUri();
                @endphp
                <li>
                    <a href="/" class="nav-link {{ $currentUri === '/' ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="/courses" class="nav-link {{ str_starts_with($currentUri, '/courses') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6 6h10M6 10h10"/></svg>
                        Course Planner
                    </a>
                </li>
                <li>
                    <a href="/analyze" class="nav-link {{ str_starts_with($currentUri, '/analyze') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        STT Work Diary
                    </a>
                </li>
                <li>
                    <a href="/tweet-generator" class="nav-link {{ str_starts_with($currentUri, '/tweet-generator') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
                        Tweet Composer
                    </a>
                </li>
                <li>
                    <a href="/sample" class="nav-link {{ str_starts_with($currentUri, '/sample') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        Student Directory
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        {{ $slot }}
    </main>

    <footer class="workspace-footer">
        <div class="footer-content">
            <div class="footer-logo">Herd AI Workspace Hub</div>
            <div class="footer-copy">© 2026 Herd AI Inc. Powered by Laravel & Advanced Large Language Models.</div>
            <div class="footer-links">
                <a href="/">Dashboard</a>
                <a href="/courses">Course Planner</a>
                <a href="/analyze">Work Diary</a>
                <a href="/tweet-generator">Tweet Composer</a>
            </div>
        </div>
    </footer>
</body>
</html>