<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
     * The roles that belong to the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Get the posts for the user.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Check if the user has a specific role.
     *
     * @param  string|array  $roles Role name or slug, or an array of them
     * @return bool
     */
    public function hasRole($roles): bool
    {
        if (empty($roles)) {
            return false;
        }

        $checkRole = function ($roleToCheck) {
            // Check both name and slug case-insensitively
            return $this->roles->contains(function ($role) use ($roleToCheck) {
                return strtolower($role->name) === strtolower($roleToCheck) || 
                       strtolower($role->slug) === strtolower($roleToCheck);
            });
        };

        if (is_string($roles)) {
            return $checkRole($roles);
        }

        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($checkRole($role)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Assign a role to the user.
     *
     * @param  string  $roleSlug
     * @return void
     */
    public function assignRole(string $roleSlug): void
    {
        $role = Role::where('slug', $roleSlug)->firstOrFail();
        $this->roles()->syncWithoutDetaching([$role->id]);
    }

    /**
     * Remove a role from the user.
     *
     * @param  string  $roleSlug
     * @return void
     */
    public function removeRole(string $roleSlug): void
    {
        $role = Role::where('slug', $roleSlug)->firstOrFail();
        $this->roles()->detach($role->id);
    }
}
