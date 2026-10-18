<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{

    // Hitung persen progres keseluruhan course
    public function getCourseProgress(Request $request, $courseId)
    {
        $userId = $request->user()->id;

        // Hitung total materi di course ini
        $totalMaterials = \App\Models\Material::whereHas('chapter', function($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->count();

        if ($totalMaterials === 0) {
            return response()->json(['progress_percentage' => 0]);
        }

        // Hitung total materi yang sudah diselesaikan oleh user
        $completedMaterials = \App\Models\UserProgress::where('user_id', $userId)
            ->where('is_completed', true)
            ->whereHas('material.chapter', function($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })->count();

        $percentage = round(($completedMaterials / $totalMaterials) * 100);

        return response()->json([
            'course_id' => (int)$courseId,
            'total_materials' => $totalMaterials,
            'completed_materials' => $completedMaterials,
            'progress_percentage' => $percentage
        ]);
    }

    // Bikin thread diskusi baru
    public function createThread(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        $thread = \App\Models\Thread::create([
            'user_id' => $request->user()->id,
            'course_id' => $request->course_id,
            'title' => $request->title,
            'body' => $request->body,
        ]);

        return response()->json([
            'message' => 'Thread berhasil dibuat',
            'data' => $thread
        ], 201);
    }
    // GET /api/courses
    public function index()
    {
        $userId = auth()->id();

        return Course::with('chapters.materials.contentBlocks', 'chapters.materials.progress')
            ->withCount($this->progressCounts($userId))
            ->get()
            ->map(fn ($course) => $this->withProgress($course));
    }


    public function show($id)
    {
        $userId = auth()->id();

         $course = Course::with('chapters.materials.progress')
            ->withCount($this->progressCounts($userId))
            ->findOrFail($id);

        return $this->withProgress($course);
    }

        private function progressCounts(int $userId): array
    {
        return [
            'materials',
            'materials as completed_materials_count' => fn ($q) =>
                $q->whereHas('userProgress', fn ($p) => $p
                ->where('user_id', $userId)
                ->where('status', 'completed')
            ),
        ];
    }

    private function withProgress(Course $course): Course
    {
    $course->progress_percentage = $course->materials_count > 0
        ? round($course->completed_materials_count / $course->materials_count * 100, 2)
        : 0;

    return $course;
    }
}
