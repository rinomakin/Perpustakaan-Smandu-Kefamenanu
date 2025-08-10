<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'group_name',
        'status'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    // Relationship dengan Role melalui pivot table
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions', 'permission_id', 'role_id');
    }

    // Scope untuk permission aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Group permissions berdasarkan group_name
    public static function getGroupedPermissions()
    {
        return self::aktif()->get()->groupBy('group_name');
    }

    // Get all permission groups
    public static function getPermissionGroups()
    {
        return self::select('group_name')
                   ->distinct()
                   ->orderBy('group_name')
                   ->pluck('group_name');
    }
}
