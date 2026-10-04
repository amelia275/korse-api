<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;

class CourseController extends Controller
{
    // GET /api/courses
    public function index()
    {
        return Course::with('chapters.materials.contentBlocks', 'chapters.materials.progress')->get();
    }

    // GET /api/courses/{id}
    public function show($id)
    {
        $course = Course::with('chapters.materials')->findOrFail($id);
        return $course;
    }
}