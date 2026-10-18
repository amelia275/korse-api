<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialItem extends Model
{
    protected $table = 'materials';
    protected $guarded = [];
    protected $appends = ['status'];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class, 'chapter_id');
    }

    public function contentBlocks()
    {
        return $this->hasMany(ContentBlock::class, 'material_id')->orderBy('order');
    }

    public function progress()
    {
        return $this->hasOne(UserProgress::class, 'material_id')
        ->where('user_id', auth()->id());
    }

    public function userProgress()
    {
        return $this->hasMany(UserProgress::class, 'material_id');
    }
    
    public function getStatusAttribute()
    {
        return $this->progress?->status ?? 'locked';
    }
}