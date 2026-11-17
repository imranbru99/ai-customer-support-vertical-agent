<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KnowledgeArticle extends Model
{
    protected $fillable = [
        'industry_vertical_id', 'title', 'slug', 'category', 'locale',
        'excerpt', 'body', 'status', 'view_count', 'helpful_votes', 'is_public',
    ];

    protected function casts(): array
    {
        return [
            'view_count' => 'integer',
            'helpful_votes' => 'integer',
            'is_public' => 'boolean',
        ];
    }

    public function industry(): BelongsTo
    {
        return $this->belongsTo(IndustryVertical::class, 'industry_vertical_id');
    }
}
