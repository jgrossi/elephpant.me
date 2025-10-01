<?php

namespace App;

use Creativeorange\Gravatar\Facades\Gravatar;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'country_code', 'x_handle', 'username', 'mastodon', 'bluesky',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    public function scopeNotLoggedIn(Builder $query)
    {
        return $query->where('id', '<>', auth()->id());
    }

    public function scopePublic(Builder $query)
    {
        return $query->where('is_public', true);
    }

    public function elephpants()
    {
        return $this->belongsToMany(Elephpant::class)
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function elephpantsToTrade()
    {
        return $this->elephpants()
            ->wherePivot('quantity', '>', 1);
    }

    public function elephpantsWithQuantity(): Collection
    {
        return $this->elephpants
            ->mapWithKeys(function (Elephpant $elephpant) {
                return [
                    $elephpant->id => $elephpant->pivot->quantity,
                ];
            });
    }

    public function adopt(Elephpant $elephpant, int $quantity)
    {
        $exists = $this->elephpants()
            ->whereElephpantId($elephpant->id)
            ->exists();

        if ($exists) {
            $quantity > 0 ?
                $this->elephpants()->updateExistingPivot($elephpant->id, compact('quantity'), false) :
                $this->elephpants()->detach($elephpant->id);

            return;
        }

        if ($quantity > 0) {
            $this->elephpants()->attach($elephpant->id, compact('quantity'));
        }
    }

    /**
     * Get the user avatar URL.
     */
    public function avatar(): string
    {
        if ($this->x_handle) {
            return sprintf('https://api.microlink.io/?url=https://twitter.com/%s&embed=image.url', $this->x_handle);
        }

        if (Gravatar::exists($this->email)) {
            return Gravatar::get($this->email);
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->name);
    }

    public function mastodonUrl(): ?string
    {
        if (! $this->mastodon) {
            return null;
        }

        $handle = $this->mastodon;

        if (! Str::startsWith($handle, '@')) {
            $handle = '@'.$handle;
        }

        $atCount = substr_count($handle, '@');

        if ($atCount >= 2) {
            $parts = explode('@', ltrim($handle, '@'));
            $username = '@'.$parts[0];
            $domain = $parts[1];

            return "https://{$domain}/{$username}";
        }

        return 'https://mastodon.social/'.$handle;
    }

    public function blueskyUrl(): ?string
    {
        if (! $this->bluesky) {
            return null;
        }

        $handle = ltrim($this->bluesky, '@');

        return 'https://bsky.app/profile/'.$handle;
    }

    public static function generateUsername(User $user): string
    {
        $username = $user->x_handle ?: Str::slug($user->name);
        $count = 1;

        while (User::whereUsername($username)->exists()) {
            $username = $username.$count;
            $count++;
        }

        return $username;
    }
}
