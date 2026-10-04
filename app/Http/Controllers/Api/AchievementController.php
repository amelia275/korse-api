<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    // GET /api/achievements?user_id=1
    public function index(Request $request)
    {
        $query = Achievement::with('course');

        if ($request->has('user_id')) {
            $query->where('user_id', $request->query('user_id'));
        }

        return $query->get();
    }
}