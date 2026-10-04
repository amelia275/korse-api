<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MaterialItem;

class MaterialController extends Controller
{
    // GET /api/materials/{id}
    public function show($id)
    {
        return MaterialItem::with('contentBlocks', 'chapter')->findOrFail($id);
    }
}