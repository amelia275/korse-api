<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chapter;

class ChapterController extends Controller
{
    // GET /api/chapters/{id}
    public function show($id)
    {
        return Chapter::with('materials')->findOrFail($id);
    }
}