<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    /**
     * Default queue tables are named "jobs", so this listing table is
     * "jobs_listings" to avoid any collision.
     */
    protected $table = 'jobs_listings';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'department',
        'location',
        'type',
        'form_type',
        'summary',
        'description',
        'ask_commission_question',
        'ask_outreach_question',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'ask_commission_question' => 'boolean',
            'ask_outreach_question'   => 'boolean',
            'is_active'               => 'boolean',
        ];
    }

    /**
     * Use the slug in route URLs instead of the id.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return HasMany<Application>
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Only open positions.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
