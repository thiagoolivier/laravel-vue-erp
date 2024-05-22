<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];
    
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles', 'role_id', 'user_id');
    }

    public static function getRoles(): Collection
    {
        return Cache::remember('roles', 60 * 60 * 24, function () {
            return Self::where('id', '>', 1)->get();
        });
    }
}
