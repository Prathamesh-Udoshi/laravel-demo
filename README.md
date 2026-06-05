# AI Course Assessment Planner & Developer Suite

An AI-powered suite of developer and educational tools built on top of Laravel 13. This platform integrates multiple LLM providers (Groq and Gemini) with automated transcript retrieval and semantic processing to streamline academic course planning, daily internship/diary logging, and content creation.

---

## 🚀 Key Features

### 1. AI Course Assessment Planner
This module automatically parses learning materials and constructs structured evaluations (Quizzes and Assignments) using advanced LLM reasoning.
* **Smart Course Scaffolding**: Create courses representing **4, 8, or 12 weeks** of study.
* **YouTube Playlist Integration**: Automatically extracts videos, titles, and IDs from a YouTube playlist to map lectures to specific weeks of the course.
* **Automated Transcripts & Summaries**: Fetches English captions/transcripts directly from YouTube. Generates detailed, academic, NPTEL-style syllabus summaries using AI.
* **Evaluation Generator**: Generates 10-question multiple-choice quizzes (with correct keys and theoretical explanations) and descriptive project assignments based on the aggregated lecture summaries.
* **Management & Export**: Full CRUD interfaces to edit or delete generated quiz questions, and a single-click JSON exporter to download the complete course syllabus package.

### 2. Daily Internship Diary Evaluator
An interactive analyzer tailored for evaluating student and developer logs.
* Accepts parameters such as hours worked, daily learnings, blockers, links, and skills.
* Analyzes inputs against standard assessment rubrics using backend API evaluation endpoints.

### 3. AI Tweet Generator
A quick-access developer tool to generate optimized promotional or educational tweets.
* Generates engaging messages under 280 characters based on a custom topic.
* Support for multiple tones: `casual`, `professional`, `humorous`, and `inspirational` using the Groq API.

### 4. Model Context Protocol (MCP) Support
* Includes local MCP server integration (`laravel/mcp`) with standard servers (e.g., `HelloServer`) enabling LLMs to execute tasks locally on behalf of the project.

---

## 🛠️ Technology Stack

* **Framework**: Laravel 13 (PHP ^8.3)
* **Frontend**: Laravel Blade, Tailwind CSS, AJAX / JSON client integrations
* **Database**: Eloquent ORM with support for SQLite / MySQL
* **AI Orchestration**:
  * **Groq Cloud API** (Llama 3.3 70B Versatile, Llama 3.1 8B Instant) as the primary provider for speed.
  * **Gemini API** (Gemini 1.5 Flash, Gemini Pro) as a fallback mechanism for resilience.
* **Developer Tools**: Pest PHP (Testing framework), Laravel Pail (Log viewer), Laravel Tinker

---

## 📋 Prerequisites

Before setting up the project, make sure you have:
* **PHP 8.3** or higher
* **Composer**
* **Node.js** and **NPM**
* API keys for either **Groq** or **Gemini** (or both for fallback functionality)

---

## ⚙️ Installation & Setup

1. **Clone the repository** (if not already local):
   ```bash
   git clone https://github.com/Prathamesh-Udoshi/laravel-demo.git
   cd myapp
   ```

2. **Configure Environment Variables**:
   Copy `.env.example` to `.env`:
   ```bash
   cp .env.example .env
   ```
   Open the `.env` file and fill in your API credentials:
   ```env
   GROQ_API_KEY=your_groq_api_key_here
   GEMINI_API_KEY=your_gemini_api_key_here
   
   # Optional: Configure default provider ('groq' or 'gemini')
   AI_DEFAULT_PROVIDER=groq
   ```

3. **Run the Automated Setup script**:
   The project has a custom setup script pre-configured in `composer.json`. Run:
   ```bash
   composer run setup
   ```
   This command installs composer dependencies, copies the environment if needed, generates application keys, triggers database migrations, runs npm installs, and builds frontend assets.

---

## 💻 Running the Application

To run the application locally, use the concurrent dev script:
```bash
npm run dev
```

This starts three processes in parallel:
1. **Local PHP Server**: Serves the application at `http://127.0.0.1:8000`
2. **Queue Listener**: Runs background jobs for intensive tasks
3. **Vite Server**: Hot-reloading compiler for assets

---

## 📂 Project Architecture

Here are the main components that drive the AI features:
* **Models**:
  * [Course](file:///c:/Users/Prathamesh/Herd/myapp/app/Models/Course.php): Manages course metadata.
  * [WeeklyContent](file:///c:/Users/Prathamesh/Herd/myapp/app/Models/WeeklyContent.php): Holds weekly lecture details, video URLs, transcripts, and AI-generated summaries.
  * [QuizQuestion](file:///c:/Users/Prathamesh/Herd/myapp/app/Models/QuizQuestion.php): Represents the 10 MCQs generated for evaluations.
  * [Assignment](file:///c:/Users/Prathamesh/Herd/myapp/app/Models/Assignment.php): Contains descriptive term evaluation tasks.
* **Services**:
  * [AIService](file:///c:/Users/Prathamesh/Herd/myapp/app/Support/AIService.php): Orchestrates LLM prompt injection, parsing, structured JSON formatting, and failover/retry strategies.
  * [YouTubeService](file:///c:/Users/Prathamesh/Herd/myapp/app/Support/YouTubeService.php): Handles scraping of playlist details and XML transcript retrieval.
* **Controllers**:
  * [CourseController](file:///c:/Users/Prathamesh/Herd/myapp/app/Http/Controllers/CourseController.php): Orchestrates the assessment generation workflow.
  * [TweetGeneratorController](file:///c:/Users/Prathamesh/Herd/myapp/app/Http/Controllers/TweetGeneratorController.php): Controls the prompt generation and HTTP request to Groq for generating tweets.
