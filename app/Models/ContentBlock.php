<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentBlock extends Model
{
    protected $guarded = [];

    public function material()
    {
        return $this->belongsTo(MaterialItem::class, 'material_id');
    }
}