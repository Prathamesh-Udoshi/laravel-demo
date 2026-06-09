<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\WeeklyContent;
use Illuminate\Database\Seeder;

class CourseContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create a Laravel course
        $course1 = Course::create([
            'title' => 'Modern Laravel Web Development',
            'description' => 'A comprehensive course covering Laravel 13 fundamentals, Eloquent ORM, AI SDK integration, and advanced architecture.',
            'duration_weeks' => 3,
            'youtube_playlist_url' => 'https://www.youtube.com/playlist?list=PL38f_laravel13',
        ]);

        // Week 1 Content
        WeeklyContent::create([
            'course_id' => $course1->id,
            'week_number' => 1,
            'video_title' => 'Introduction to Laravel 13 and Routing',
            'youtube_url' => 'https://www.youtube.com/watch?v=intro-routing',
            'summary' => 'Introduction to Laravel 13 directory structure, routing architecture, and MVC patterns.',
            'transcript_or_notes' => 'Welcome back to class! Today we are introducing Laravel 13. We will cover the basic directory structure, MVC (Model-View-Controller) architecture, and how to write basic routes in routes/web.php. Laravel is a PHP framework designed to make building web applications simple and expressive. Routes are the entry points of your application. When a user requests a URL, Laravel maps it using the Router to a closure or controller. We will also look at Blade templating, which is the default view rendering engine in Laravel, letting us write clean HTML layouts with custom components.',
        ]);

        // Week 2 Content
        WeeklyContent::create([
            'course_id' => $course1->id,
            'week_number' => 2,
            'video_title' => 'Eloquent ORM, Migrations, and Indexes',
            'youtube_url' => 'https://www.youtube.com/watch?v=eloquent-migrations',
            'summary' => 'Deep dive into database migrations, schema builders, Eloquent ORM, and optimization using database indexes.',
            'transcript_or_notes' => 'Hi everyone, in week 2 we are discussing migrations, database models, and indexes. Eloquent is Laravel\'s ActiveRecord ORM. We use migrations to define database schema changes in PHP code, rather than raw SQL. For example, to add a new table, we run "php artisan make:migration". Then we use the Schema builder with columns like string, integer, text, and now vector types. Database indexes are critical for search performance. In PostgreSQL, we can use BTREE indexes for normal lookups, or HNSW (Hierarchical Navigable Small World) indexes for high-speed vector embeddings search. Eloquent makes relationships easy, like defining belongsTo or hasMany methods on your model classes.',
        ]);

        // Week 3 Content
        WeeklyContent::create([
            'course_id' => $course1->id,
            'week_number' => 3,
            'video_title' => 'Laravel AI SDK, Embeddings, and Vector Search',
            'youtube_url' => 'https://www.youtube.com/watch?v=laravel-ai-vector',
            'summary' => 'Learn how to generate vector embeddings using Gemini/OpenAI, and search database records semantically in Laravel 13.',
            'transcript_or_notes' => 'Hello students, today we are exploring Laravel 13\'s official AI SDK. This package provides unified wrappers for OpenAI, Gemini, Anthropic, and other models. We will learn how to generate text, images, audio, and most importantly, vector embeddings. Vector search lets us query text semantically rather than using direct keyword matching. With whereVectorSimilarTo, we search for closest match vectors. For example, if we search for "database structures", it should return Week 2\'s Eloquent and Indexing lecture even if it doesn\'t contain the exact search term. By default, the Gemini embedding model "gemini-embedding-001" generates vectors of 3072 dimensions, representing the underlying semantic meaning of the text.',
        ]);

        // 2. Create another course for variety
        $course2 = Course::create([
            'title' => 'Introduction to Python & Data Science',
            'description' => 'Learn python syntax, basic variables, loops, lists, pandas dataframes, and basic linear regression models.',
            'duration_weeks' => 1,
            'youtube_playlist_url' => 'https://www.youtube.com/playlist?list=PL38f_python_ds',
        ]);

        WeeklyContent::create([
            'course_id' => $course2->id,
            'week_number' => 1,
            'video_title' => 'Python Basics and Pandas DataFrames',
            'youtube_url' => 'https://www.youtube.com/watch?v=python-pandas',
            'summary' => 'Introduction to basic Python variables, loops, lists, and data manipulation using Pandas.',
            'transcript_or_notes' => 'Hello! Today we are starting with Python. We\'ll cover basic data structures: integers, floats, strings, lists, tuples, and dictionaries. Then we will move on to the Pandas library, which is the cornerstone of data science in Python. A DataFrame is a 2-dimensional labeled data structure with columns of potentially different types, similar to a spreadsheet or SQL table. We will learn how to load csv files, filter rows using boolean indexing, group data by categories, and summarize statistical values.',
        ]);
    }
}
