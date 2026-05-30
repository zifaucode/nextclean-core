<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'outlet_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id');
    }

    /**
     * Check if user has a specific role by slug.
     */
    public function hasRole(string $role): bool
    {
        return $this->role && $this->role->slug === $role;
    }

    public function getTenantBrandNameAttribute()
    {
        if ($this->hasRole('super-admin')) return 'NextClean';
        if ($this->hasRole('admin-outlet')) return $this->brand_name ?? 'NextClean';
        
        // For Kasir / Supervisor
        if ($this->outlet && $this->outlet->owner) {
            return $this->outlet->owner->brand_name ?? 'NextClean';
        }
        return 'NextClean';
    }

    public function getTenantBrandLogoAttribute()
    {
        if ($this->hasRole('super-admin')) return null;
        if ($this->hasRole('admin-outlet')) return $this->brand_logo;
        
        // For Kasir / Supervisor
        if ($this->outlet && $this->outlet->owner) {
            return $this->outlet->owner->brand_logo;
        }
        return null;
    }
}
