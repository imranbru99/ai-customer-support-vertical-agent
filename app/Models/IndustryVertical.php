<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IndustryVertical extends Model
{
    protected $fillable = [
        'name', 'slug', 'sector', 'tone', 'sla_minutes', 'deflection_target',
        'knowledge_pack', 'is_active', 'description',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sla_minutes' => 'integer',
            'deflection_target' => 'float',
        ];
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(KnowledgeArticle::class);
    }

    public function macros(): HasMany
    {
        return $this->hasMany(Macro::class);
    }

    public function escalationRules(): HasMany
    {
        return $this->hasMany(EscalationRule::class);
    }
}
