<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'peran';
    
    protected $fillable = [
        'nama_peran',
        'kode_peran',
        'deskripsi',
        'status'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    // Relationship dengan User
    public function users()
    {
        return $this->hasMany(User::class, 'peran_id');
    }

    // Scope untuk role aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Scope untuk role nonaktif
    public function scopeNonaktif($query)
    {
        return $query->where('status', 'nonaktif');
    }

    // Relationship dengan Permission melalui pivot table
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');
    }

    // Check if role has specific permission
    public function hasPermission($permission)
    {
        if (is_string($permission)) {
            return $this->permissions()->where('slug', $permission)->exists();
        }
        
        return $this->permissions()->where('id', $permission)->exists();
    }

    // Assign permission to role
    public function givePermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('slug', $permission)->first();
        }
        
        if ($permission && !$this->hasPermission($permission->id)) {
            $this->permissions()->attach($permission->id);
        }
    }

    // Remove permission from role
    public function removePermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('slug', $permission)->first();
        }
        
        if ($permission) {
            $this->permissions()->detach($permission->id);
        }
    }

    // Sync permissions (replace all permissions)
    public function syncPermissions($permissions)
    {
        $permissionIds = [];
        
        foreach ($permissions as $permission) {
            if (is_string($permission)) {
                $perm = Permission::where('slug', $permission)->first();
                if ($perm) {
                    $permissionIds[] = $perm->id;
                }
            } else {
                $permissionIds[] = $permission;
            }
        }
        
        $this->permissions()->sync($permissionIds);
    }
}
