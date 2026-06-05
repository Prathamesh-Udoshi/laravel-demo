<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public function index()
    {
        // Fetch all students from SQLite database
        $students = Student::all();

        // Fallback seeding to ensure the directory is populated and readable immediately
        if ($students->isEmpty()) {
            Student::create(['name' => 'Alice Johnson', 'class' => 'Master of Computer Applications']);
            Student::create(['name' => 'Bob Miller', 'class' => 'Bachelor of Engineering (CSE)']);
            Student::create(['name' => 'Charlie Davis', 'class' => 'Postgraduate Diploma in Cloud Computing']);
            
            $students = Student::all();
        }
        
        return view('sample.index', compact('students'));
    }

    public function create()
    {
        return view('sample.create');
    }

    public function show($id)
    {
        $student = Student::findOrFail($id);
        return view('sample.show', compact('student'));
    }

    public function store(Request $request)
    {
        // Validation is an important improvement!
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'class' => 'required|string|max:100',
        ]);

        $student = new Student();
        $student->name = $validated['name'];
        $student->class = $validated['class'];
        $student->save();

        return redirect('/sample')->with('success', 'Student added successfully!');
    }
}
