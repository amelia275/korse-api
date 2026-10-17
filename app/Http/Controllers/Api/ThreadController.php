<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    // GET /api/threads?course_id=1
    public function index(Request $request)
    {
        $query = Thread::with('user')->latest();

        if ($request->has('course_id')) {
            $query->where('course_id', $request->query('course_id'));
        }

        return $query->get();
    }

    // GET /api/threads/{id}
    public function show($id)
    {
        return Thread::with('user', 'replies.user')->findOrFail($id);
    }
}
