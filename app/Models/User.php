<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'region',
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

    /**
     * Check if user is superadmin
     */
    public function isSuperAdmin()
    {
        return $this->role === 'superadmin';
    }

    /**
     * Check if user is regional admin
     */
    public function isRegionalAdmin()
    {
        return $this->role === 'regional';
    }

    /**
     * Get accessible regions for the user
     */
    public function getAccessibleRegions()
    {
        if ($this->isSuperAdmin()) {
            return \App\Models\FiberCore::select('region')->distinct()->pluck('region')->toArray();
        }

        return $this->region ? [$this->region] : [];
    }

    /**
     * Check if user can access specific region
     */
    public function canAccessRegion($region)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->region === $region;
    }
}