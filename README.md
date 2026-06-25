# Laravel Demo & Experimentation Playground

A personal Laravel 13 playground containing unrelated sandbox experiments, featuring independent AI-driven modules (using Groq & Gemini fallbacks), browser-recorded speech-to-text oral exam evaluators, and local MCP server integrations.

---

## 🧪 The Experimental Modules

Here are the independent modules implemented in this playground:

### 1. Course Assessment Planner
An experiment in combining YouTube content scraping with LLM generation to draft academic syllabus structures and test materials.
* **What it does**: Takes a YouTube playlist URL, distributes the videos across a 4, 8, or 12-week course, and uses OpenAI's cheapest model (gpt-4o-mini) to generate syllabus summaries directly based on video titles. It then auto-generates 10-question MCQ quizzes and subjective homework tasks.
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
* **What it does**: Allows students to ask questions about courses in the Course Planner. **This works exclusively for courses dynamically created and processed in the Course Planner (`/courses`).** The system segments weekly summaries into overlapping chunks, generates 3072-dimensional Gemini embeddings on-the-fly, and performs a semantic search. The AI Agent (`LectureTutor`) is strictly instructed to only answer using retrieved context, politely declining off-topic queries.
* **Local Ollama Integration**: Supports toggling between Cloud API (OpenAI gpt-4o-mini) and a locally running LLM via Ollama (such as `qwen2.5:1.5b` or `qwen2.5:0.5b`). The search query embeddings continue to run on the Gemini API to preserve existing 3072-dimensional vector cache stores, while completion logic runs on the local CPU to save API generation costs. The local tutor includes a semantic interceptor to gracefully reject off-topic questions without wasting local resources.
* **Vector Fallback Engine**: If pgvector extension is not installed (common on local Windows setups), the system catches the query exception and dynamically falls back to computing cosine similarity mathematically inside PHP, keeping search fully functional.
* **Where to find it**: Access it at `/tutor` (configured via [TutorChatController](file:///c:/Users/Prathamesh/Herd/myapp/app/Http/Controllers/TutorChatController.php) and routes in `routes/web.php`).

### 8. AI Context & Payload Inspector
An experiment in tracing Laravel thread-safe request-scoped metadata and intercepting outbound HTTP API payloads.
* **What it does**: Allows developers to input custom variables into Laravel's `Context` store and system context instructions. When prompts are sent to Groq or Gemini, request-scoped listener hooks intercept and display outbound HTTP payload JSON details (request/response headers and bodies, with credentials masked) alongside the local context.
* **Where to find it**: Access it at `/ai-context-inspector` (configured via [AIContextController](file:///c:/Users/Prathamesh/Herd/myapp/app/Http/Controllers/AIContextController.php) and routes in `routes/web.php`).

### 9. AI Email Reminder Agent
An experiment in generating highly personalized completion reminders using database state and LLM instructions.
* **What it does**: Tracks student enrollments, completion percentage, and last reminded timestamps. When triggered manually or run via background sweeps, an AI agent compiles progress metrics into a personalized email copy designed to motivate the student. The compiled reminder is dispatched using Laravel Mailable envelopes.
* **Where to find it**: Access it at `/email-agent` (configured via [EmailAgentController](file:///c:/Users/Prathamesh/Herd/myapp/app/Http/Controllers/EmailAgentController.php) and routes in `routes/web.php`).

---

## 🛠️ Stack & Technologies Used

* **Framework**: Laravel 13 (PHP ^8.3)
* **Database**: PostgreSQL (with optional `pgvector` extension; fallbacks to native PHP cosine similarity logic if missing)
* **Frontend**: Blade templates, Vanilla CSS (harmonious light mode styling), CSS micro-animations, real-time RAG context inspector
* **AI Orchestration**: Integrates OpenAI's gpt-4o-mini for direct title-based summarization, along with Groq (Whisper/Llama) and Gemini failover APIs for quizzes, assignments, and oral exam (Viva Voce) evaluations. Vectorization and RAG conversation memory managed via the `laravel/ai` SDK.
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

4. **Vectorize Weekly Summaries and Process Background Jobs**:
   By default, the Course Planner chunks and generates vector embeddings **synchronously on-the-fly** during week-processing requests (via `ChunkWeeklyContentJob::dispatchSync`). This ensures that vector chunks are immediately indexed in the database and available to the RAG Tutor dashboard without requiring a running queue worker.
   
   If you have legacy courses or need to force-regenerate vector chunks for all existing course materials at once, you can run the console command:
   ```bash
   php artisan app:chunk-transcripts
   ```

5. **Start the local server**:
   If you are running the server locally, you can start Vite and the server together:
   ```bash
   npm run dev
   ```
   *(If you run your server through Laravel Herd, the app is instantly available at http://myapp.test/courses)*

6. **Running AI Tutor Completions Locally (Ollama Optional Setup)**:
    To run the tutor response completion locally without cloud API fees:
    * Download and install Ollama from [Ollama.com](https://ollama.com).
    * Pull a lightweight completion model of your choice in Command Prompt / PowerShell:
      ```bash
      # For standard CPU/8GB RAM setups (recommended)
      ollama pull llama3.2:1b
      # For ultra-lightweight CPU execution
      ollama pull qwen2.5:0.5b
      ```
    * Append the following variables to your `.env` configuration file:
      ```env
      OLLAMA_MODEL=llama3.2:1b
      OLLAMA_URL=http://localhost:11434
      ```
    * Navigate to `/tutor` and switch the toggle button in the chat header card from **Cloud API** to **Local (Ollama)**.
