<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIController extends Controller
{
    public function showform()
    {
        return view('sample.analyze');
    }

    public function analyze(Request $req)
    {
        $data = [
            "date" => $req->date,
            "description" => $req->description,
            "hours" => $req->hours,
            "learnings" => $req->learnings,
            "blockers" => $req->blockers,
            "links" => $req->links,
            "skills" => explode(",", $req->skills),
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post('http://127.0.0.1:8000/analyze/daily', $data);

        $result = $response->json();

        return view('sample.result', compact('result'));
    }
}
