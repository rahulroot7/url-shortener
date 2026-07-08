<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'role_id',
        'parent_id',
        'name',
        'email',
        'password',
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
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * User belongs to a Company.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * User belongs to a Role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function shortUrls()
    {
        return $this->hasMany(ShortUrl::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class,'parent_id');
    }

    public function members()
    {
        return $this->hasMany(User::class,'parent_id');
    }

    // Helper For role 

    public function isSuperAdmin(): bool
    {
        return $this->role?->slug === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return $this->role?->slug === 'admin';
    }

    public function isMember(): bool
    {
        return $this->role?->slug === 'member';
    }
    public function isSales(): bool
    {
        return $this->role?->slug === 'sales';
    }
    public function isManager(): bool
    {
        return $this->role?->slug === 'manager';
    }
}