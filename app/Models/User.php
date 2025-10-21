<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\HasName;        
use Filament\Models\Contracts\HasAvatar;    
use Filament\Models\Contracts\FilamentUser;

class User extends Authenticatable implements FilamentUser, HasName, HasAvatar
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

    public function canAccessPanel(Panel $panel): bool
    {
        $allowed = config('app.admin_emails', []);
        return in_array($this->email, $allowed, true);
    }

    /**
     * (Opsional) Nama yang tampil di header Filament
     */
    public function getFilamentName(): string
    {
        return $this->name ?: $this->email;
    }

    /**
     * (Opsional) Avatar di header Filament (null = default)
     */
    public function getFilamentAvatarUrl(): ?string
    {
        return null;
    }
}
