<?php

namespace App\Http\Controllers;

use App\Models\UserProgress;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
   
    public function markAsComplete(Request $request)
    {
        $request->validate([
            'material_id' => 'required|exists:materials,id'
        ]);

        $userId = $request->user()->id; 

        $progress = UserProgress::updateOrCreate(
            [
                'user_id' => $userId,
                'material_id' => $request->material_id,
            ],
            [
                'is_completed' => true
            ]
        );

        return response()->json([
            'message' => 'Progres berhasil diperbarui',
            'data' => $progress
        ]);
    }
}