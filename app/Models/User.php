<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_lengkap',
        'email',
        'password',
        'peran_id',
        'nomor_telepon',
        'alamat',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relationship dengan Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'peran_id');
    }

    /**
     * Check if user has specific permission
     */
    public function hasPermission($permission)
    {
        if (!$this->role) {
            return false;
        }
        
        return $this->role->hasPermission($permission);
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission($permissions)
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has all of the given permissions
     */
    public function hasAllPermissions($permissions)
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get all user permissions through role
     */
    public function getAllPermissions()
    {
        if (!$this->role) {
            return collect();
        }
        
        return $this->role->permissions;
    }

    /**
     * Check if user is admin (has admin role)
     */
    public function isAdmin()
    {
        return $this->role && $this->role->kode_peran === 'ADMIN';
    }

    /**
     * Check if user is kepala sekolah
     */
    public function isKepalaSekolah()
    {
        return $this->role && $this->role->kode_peran === 'KEPALA_SEKOLAH';
    }

    /**
     * Check if user is petugas
     */
    public function isPetugas()
    {
        return $this->role && $this->role->kode_peran === 'PETUGAS';
    }
}
