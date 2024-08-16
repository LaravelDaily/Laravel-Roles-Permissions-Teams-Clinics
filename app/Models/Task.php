<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'due_date',
        'user_id',
        'team_id',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Task $task) {
            if (auth()->check()) {
                $task->team_id = auth()->user()->current_team_id;
            }
        });

        static::addGlobalScope('team-tasks', function (Builder $query) {
            if (auth()->check()) {
                $query->where('team_id', auth()->user()->current_team_id);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
