<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;

/**
 * @property-read \Illuminate\Database\Eloquent\Relations\Pivot&object{quantity: int, updated_at: \Carbon\Carbon|null} $pivot
 * @property string|null $possible_senders
 * @property string|null $possible_receivers
 * @property int|null    $users_count
 * @property int|null    $copies
 * @property float|null  $ownership_percentage
 * @property Format      $format
 */
class Elephpant extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    #[\Override]
    protected $fillable = [
        'id',
        'format',
        'name',
        'description',
        'sponsor',
        'year',
        'image',
    ];

    #[\Override]
    protected function casts(): array
    {
        return [
            'format' => Format::class,
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function scopeFilter(Builder $query, Request $request): Builder
    {
        return $query->where('name', 'LIKE', '%'.$request->input('q').'%')
            ->orWhere('description', 'LIKE', '%'.$request->input('q').'%')
            ->orWhere('sponsor', 'LIKE', '%'.$request->input('q').'%')
            ->orWhere('year', 'LIKE', '%'.$request->input('q').'%');
    }

    public function formattedName(): string
    {
        if ($this->format == Format::Small) {
            return $this->name;
        }

        return $this->name . ' ('.ucfirst($this->format->value).')';
    }
}
