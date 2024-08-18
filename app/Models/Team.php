<?php

namespace App\Models;

use App\Enums\Role as RoleEnum;
use Spatie\Permission\Models\Role as RoleModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected static function booted(): void
    {
        static::created(function (Team $team) {
            RoleModel::create(['name' => RoleEnum::SuperAdmin->value, 'team_id' => $team->id]);
            RoleModel::create(['name' => RoleEnum::Admin->value, 'team_id' => $team->id]);
            RoleModel::create(['name' => RoleEnum::Patient->value, 'team_id' => $team->id]);
            RoleModel::create(['name' => RoleEnum::Doctor->value, 'team_id' => $team->id]);
            RoleModel::create(['name' => RoleEnum::Staff->value, 'team_id' => $team->id]);
        });
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('is_owner');
    }
}
