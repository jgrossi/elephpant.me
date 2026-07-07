<?php

namespace App;

use Creativeorange\Gravatar\Facades\Gravatar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @property-read EloquentCollection<int, Elephpant> $elephpants
 * @property EloquentCollection<int, Elephpant>|null $elephpantsInterested
 * @property \Carbon\Carbon|null                     $last_update
 */
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    #[\Override]
    protected $fillable = [
        'name', 'email', 'password', 'country_code', 'x_handle', 'username', 'mastodon', 'bluesky',
    ];

    #[\Override]
    protected $hidden = [
        'password', 'remember_token',
    ];

    #[\Override]
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeNotLoggedIn(Builder $query)
    {
        return $query->where('id', '<>', auth()->id());
    }

    public function scopePublic(Builder $query)
    {
        return $query->where('is_public', true);
    }

    public function elephpants(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Elephpant::class)
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function elephpantsToTrade(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->elephpants()
            ->wherePivot('quantity', '>', 1);
    }

    public function elephpantsWithQuantity(): Collection
    {
        return $this->elephpants()
            ->pluck('elephpant_user.quantity', 'elephpants.id');
    }

    public function adopt(Elephpant $elephpant, int $quantity): void
    {
        $exists = $this->elephpants()
            ->whereElephpantId($elephpant->id)
            ->exists();

        if ($exists) {
            $quantity > 0 ?
                $this->elephpants()->updateExistingPivot($elephpant->id, ['quantity' => $quantity], false) :
                $this->elephpants()->detach($elephpant->id);

            return;
        }

        if ($quantity > 0) {
            $this->elephpants()->attach($elephpant->id, ['quantity' => $quantity]);
        }
    }

    /**
     * Whether the user has an image-based avatar (Gravatar or X).
     * When false, use Flux avatar with initials and color="auto" instead.
     */
    public function hasAvatarImage(): bool
    {
        return $this->x_handle || Gravatar::exists($this->email);
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

    /**
     * Build the canonical profile URL for the user's Mastodon handle.
     *
     * Full `@user@instance` handles resolve to that instance; bare handles
     * fall back to mastodon.social.
     */
    public function mastodonUrl(): ?string
    {
        if (! $this->mastodon) {
            return null;
        }

        $handle = ltrim($this->mastodon, '@');

        if (str_contains($handle, '@')) {
            [$user, $instance] = explode('@', $handle, 2);

            if ($user !== '' && $instance !== '') {
                return sprintf('https://%s/@%s', $instance, $user);
            }
        }

        return 'https://mastodon.social/@'.$handle;
    }

    /**
     * Build the profile URL for the user's Bluesky handle.
     */
    public function blueskyUrl(): ?string
    {
        if (! $this->bluesky) {
            return null;
        }

        return 'https://bsky.app/profile/'.ltrim($this->bluesky, '@');
    }

    public static function generateUsername(User $user): string
    {
        $username = $user->x_handle ?: Str::slug($user->name);
        $count = 1;

        while (User::whereUsername($username)->exists()) {
            $username .= $count;
            $count++;
        }

        return $username;
    }
}
