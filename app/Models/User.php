<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nom',
        'email',
        'password',
        'role',  // Legacy field for backward compatibility
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
     * Relations
     */
    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function client()
    {
        return $this->hasOne(Client::class);
    }

    /**
     * Checks if user is an admin (backward compatibility)
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is a manager
     */
    public function isManager(): bool
    {
        return $this->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Check if user is a customer
     */
    public function isCustomer(): bool
    {
        return $this->hasRole('customer');
    }

    /**
     * Give multiple roles to user
     */
    public function giveRoles(...$roles): self
    {
        foreach ($roles as $role) {
            $this->assignRole($role);
        }

        return $this;
    }

    /**
     * Revoke multiple roles from user
     */
    public function revokeRoles(...$roles): self
    {
        foreach ($roles as $role) {
            $this->removeRole($role);
        }

        return $this;
    }

    /**
     * Get the display name
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->nom ?? $this->name ?? $this->email;
    }
}
