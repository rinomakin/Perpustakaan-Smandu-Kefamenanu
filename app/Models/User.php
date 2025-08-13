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
        'nama_panggilan',
        'email',
        'password',
        'peran_id',
        'nomor_telepon',
        'alamat',
        'foto',
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
     * Check if user has a specific role
     */
    public function hasRole($role)
    {
        if (!$this->role) {
            return false;
        }
        
        // Check by role code (kode_peran)
        if (is_string($role)) {
            return $this->role->kode_peran === strtoupper($role);
        }
        
        // Check by role ID
        if (is_numeric($role)) {
            return $this->role->id == $role;
        }
        
        // Check by role object
        if (is_object($role) && method_exists($role, 'getKey')) {
            return $this->role->id == $role->getKey();
        }
        
        return false;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole($roles)
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has all of the given roles
     */
    public function hasAllRoles($roles)
    {
        foreach ($roles as $role) {
            if (!$this->hasRole($role)) {
                return false;
            }
        }
        return true;
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

    /**
     * Get profile route based on user role
     */
    public function getProfileRoute()
    {
        if ($this->isAdmin()) {
            return 'admin.profil';
        } elseif ($this->isKepalaSekolah()) {
            return 'kepsek.profil';
        } elseif ($this->isPetugas()) {
            return 'petugas.profil';
        }
        
        return 'admin.profil'; // default fallback
    }

    /**
     * Get profile update route based on user role
     */
    public function getProfileUpdateRoute()
    {
        if ($this->isAdmin()) {
            return 'admin.profil.update';
        } elseif ($this->isKepalaSekolah()) {
            return 'kepsek.profil.update';
        } elseif ($this->isPetugas()) {
            return 'petugas.profil.update';
        }
        
        return 'admin.profil.update'; // default fallback
    }

    /**
     * Get profile password change route based on user role
     */
    public function getProfilePasswordRoute()
    {
        if ($this->isAdmin()) {
            return 'admin.profil.ganti-password';
        } elseif ($this->isKepalaSekolah()) {
            return 'kepsek.profil.ganti-password';
        } elseif ($this->isPetugas()) {
            return 'petugas.profil.ganti-password';
        }
        
        return 'admin.profil.ganti-password'; // default fallback
    }

    /**
     * Get profile delete photo route based on user role
     */
    public function getProfileDeletePhotoRoute()
    {
        if ($this->isAdmin()) {
            return 'admin.profil.hapus-foto';
        } elseif ($this->isKepalaSekolah()) {
            return 'kepsek.profil.hapus-foto';
        } elseif ($this->isPetugas()) {
            return 'petugas.profil.hapus-foto';
        }
        
        return 'admin.profil.hapus-foto'; // default fallback
    }
}
