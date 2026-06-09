# Laravel Demo & Experimentation Playground

A personal Laravel 13 playground containing unrelated sandbox experiments, featuring independent AI-driven modules (using Groq & Gemini fallbacks), browser-recorded speech-to-text oral exam evaluators, and local MCP server integrations.

---

## 🧪 The Experimental Modules

Here are the independent modules implemented in this playground:

### 1. Course Assessment Planner
An experiment in combining YouTube content scraping with LLM generation to draft academic syllabus structures and test materials.
* **What it does**: Takes a YouTube playlist URL, distributes the videos across a 4, 8, or 12-week course, grabs transcripts from YouTube, and uses AI (Groq/Gemini fallbacks) to write syllabus summaries. It then auto-generates 10-question MCQ quizzes and subjective homework tasks.
* **Where to find it**: Access it at `/courses` (configured in `routes/web.php` via [CourseController](file:///c:/Users/Prathamesh/Herd/myapp/app/Http/Controllers/CourseController.php)).

### 2. AI Viva Voce (Oral Exam Evaluator)
An experiment in conversational speech-to-text academic assessments.
* **What it does**: Conducts a multi-turn oral exam between an AI examiner and the student. Students record their verbal responses directly in the browser, which are transcribed using the **Groq Whisper API** and evaluated on concepts, technical delivery, and style to compile a final scorecard.
* **Where to find it**: Access it from the Course Planner detail page under `/courses/{courseId}/viva` (configured in `routes/web.php` via [VivaController](file:///c:/Users/Prathamesh/Herd/myapp/app/Http/Controllers/VivaController.php)).

### 3. AI Tweet Generator
A small integration testing quick prompt completions with the Groq API.
* **What it does**: Generates a single tweet under 280 characters based on a custom topic and selected tone (casual, professional, humorous, or inspirational).
* **Where to find it**: Access it at `/tweet-generator` (configured via [TweetGeneratorController](file:///c:/Users/Prathamesh/Herd/myapp/app/Http/Controllers/TweetGeneratorController.php)).

### 4. Student & Daily Diary Evaluator
A sample CRUD and journal-logging experiment.
* **What it does**: Manages student records and tests daily journal/log evaluations (blockers, learnings, hours, skills) against academic guidelines by posting to an evaluation endpoint.
* **Where to find it**: Access it at `/sample` (configured via [StudentController](file:///c:/Users/Prathamesh/Herd/myapp/app/Http/Controllers/StudentController.php) and [AIController](file:///c:/Users/Prathamesh/Herd/myapp/app/Http/Controllers/AIController.php)).

### 5. Model Context Protocol (MCP) Server
An exploration of the Model Context Protocol to let AI agents execute actions locally.
* **What it does**: Configures a local MCP server (`HelloServer`) using `laravel/mcp`.
* **Where to find it**: Defined in the [ai.php](file:///c:/Users/Prathamesh/Herd/myapp/routes/ai.php) route file.

### 6. Service Container SMS Service Test
* **What it does**: A simple route to test resolving and invoking the custom `SmsService` from the Laravel service container.
* **Where to find it**: Access it at `/test-sms`.

### 7. Interactive AI Lecture Tutor (RAG)
An experiment in Retrieval-Augmented Generation (RAG) and local semantic search using Laravel 13 vector integrations.
* **What it does**: Allows students to ask questions about courses in the Course Planner. The system segments lecture transcripts into overlapping chunks, generates 3072-dimensional Gemini embeddings on-the-fly, and performs a semantic search. The AI Agent (`LectureTutor`) is strictly instructed to only answer using retrieved context, politely declining off-topic queries.
* **Vector Fallback Engine**: If pgvector extension is not installed (common on local Windows setups), the system catches the query exception and dynamically falls back to computing cosine similarity mathematically inside PHP, keeping search fully functional.
* **Where to find it**: Access it at `/tutor` (configured via [TutorChatController](file:///c:/Users/Prathamesh/Herd/myapp/app/Http/Controllers/TutorChatController.php) and routes in `routes/web.php`).

---

## 🛠️ Stack & Technologies Used

* **Framework**: Laravel 13 (PHP ^8.3)
* **Database**: PostgreSQL (with optional `pgvector` extension; fallbacks to native PHP cosine similarity logic if missing)
* **Frontend**: Blade templates, Vanilla CSS (harmonious light mode styling), CSS micro-animations, real-time RAG context inspector
* **AI Orchestration**: Custom multi-model failover logic in [AIService](file:///c:/Users/Prathamesh/Herd/myapp/app/Support/AIService.php) calling Groq Cloud (Llama & Whisper for Speech-to-Text) and Google Gemini APIs. Vectorization and RAG conversation memory managed via the `laravel/ai` SDK.
* **Testing & Tools**: Pest PHP, Laravel Pail, Laravel Tinker, Laravel MCP.

---

## ⚙️ How to Setup and Run Locally

1. **Clone the repository**:
   ```bash
   git clone https://github.com/Prathamesh-Udoshi/laravel-demo.git
   cd myapp
   ```

2. **Configure Environment Variables**:
   Copy `.env.example` to `.env` and fill in your API keys (Groq and/or Gemini) and database credentials:
   ```bash
   cp .env.example .env
   ```

3. **Install Dependencies and Setup Database**:
   Run the pre-configured Composer script which handles key generation, migrations, npm builds, and dependencies:
   ```bash
   composer run setup
   ```

4. **Synchronously Vectorize Existing Transcripts**:
   If there are existing courses in the planner that haven't been vectorized yet, run the Artisan command:
   ```bash
   php artisan app:chunk-transcripts
   ```

5. **Start the local server & queue listener**:
   For new courses and week summaries to be vectorized dynamically, a queue worker must be active:
   ```bash
   npm run dev
   ```
   *(This runs the PHP server, Vite assets, and `php artisan queue:listen` concurrently. If you run your server through Laravel Herd, open a terminal window and run `php artisan queue:listen`, or set `QUEUE_CONNECTION=sync` in your `.env` to process them instantly).*
