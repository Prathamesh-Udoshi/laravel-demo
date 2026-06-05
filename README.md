# Laravel Demo & Experimentation Playground

This repository is a personal sandbox project created to learn, build, and test various unrelated features, AI integrations, and tools using Laravel 13, Vite, and PHP 8.3+. 

Each module in this project stands on its own and represents a different experiment.

---

## 🧪 The Experimental Modules

Here are the independent modules implemented in this playground:

### 1. Course Assessment Planner
An experiment in combining YouTube content scraping with LLM generation to draft academic syllabus structures and test materials.
* **What it does**: Takes a YouTube playlist URL, distributes the videos across a 4, 8, or 12-week course, grabs transcripts from YouTube, and uses AI (Groq/Gemini fallbacks) to write syllabus summaries. It then auto-generates 10-question MCQ quizzes and subjective homework tasks.
* **Where to find it**: Access it at `/courses` (configured in `routes/web.php` via [CourseController](file:///c:/Users/Prathamesh/Herd/myapp/app/Http/Controllers/CourseController.php)).

### 2. Tweet Generator
A small integration testing quick prompt completions with the Groq API.
* **What it does**: Generates a single tweet under 280 characters based on a custom topic and selected tone (casual, professional, humorous, or inspirational).
* **Where to find it**: Access it at `/tweet-generator` (configured via [TweetGeneratorController](file:///c:/Users/Prathamesh/Herd/myapp/app/Http/Controllers/TweetGeneratorController.php)).

### 3. Student & Daily Diary Evaluator
A sample CRUD and journal-logging experiment.
* **What it does**: Manages student records and tests daily journal/log evaluations (blockers, learnings, hours, skills) against academic guidelines by posting to an evaluation endpoint.
* **Where to find it**: Access it at `/sample` (configured via [StudentController](file:///c:/Users/Prathamesh/Herd/myapp/app/Http/Controllers/StudentController.php) and [AIController](file:///c:/Users/Prathamesh/Herd/myapp/app/Http/Controllers/AIController.php)).

### 4. Model Context Protocol (MCP) Server
An exploration of the Model Context Protocol to let AI agents execute actions locally.
* **What it does**: Configures a local MCP server (`HelloServer`) using `laravel/mcp`.
* **Where to find it**: Defined in the [ai.php](file:///c:/Users/Prathamesh/Herd/myapp/routes/ai.php) route file.

### 5. Service Container SMS Service Test
* **What it does**: A simple route to test resolving and invoking the custom `SmsService` from the Laravel service container.
* **Where to find it**: Access it at `/test-sms`.

---

## 🛠️ Stack & Technologies Used

* **Framework**: Laravel 13 (PHP ^8.3)
* **Frontend**: Blade templates, TailwindCSS, Vite
* **AI Orchestration**: Custom multi-model failover logic in [AIService](file:///c:/Users/Prathamesh/Herd/myapp/app/Support/AIService.php) calling Groq Cloud (Llama) and Google Gemini APIs.
* **Testing & Tools**: Pest PHP, Laravel Pail, Laravel Tinker, Laravel MCP.

---

## ⚙️ How to Setup and Run Locally

1. **Clone the repository**:
   ```bash
   git clone https://github.com/Prathamesh-Udoshi/laravel-demo.git
   cd myapp
   ```

2. **Configure Environment Variables**:
   Copy `.env.example` to `.env` and fill in your API keys (Groq and/or Gemini):
   ```bash
   cp .env.example .env
   ```

3. **Install Dependencies and Setup Database**:
   Run the pre-configured Composer script which handles key generation, migrations, npm builds, and dependencies:
   ```bash
   composer run setup
   ```

4. **Start the local server**:
   ```bash
   npm run dev
   ```
   This will concurrently run the PHP server, the Vite asset builder, and the Laravel queue listener.
