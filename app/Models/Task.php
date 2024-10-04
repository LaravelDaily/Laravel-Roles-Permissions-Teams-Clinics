<?php

namespace App\Models;

use App\Enums\Role;
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
        'assigned_to_user_id',
        'patient_id',
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

                if (auth()->user()->hasRole(Role::Patient)) {
                    $query->where('patient_id', auth()->id());
                }
            }
        });
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}
