<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

/**
 * @property string $code
 * @property string $code_2
 * @property string $name
 * @property string $flag
 */
class Country extends Model
{
    use Sushi;

    protected $primaryKey = 'code';

    public $incrementing = false;

    public function getRows(): array
    {
        $path = database_path('data/countries.json');

        if (!file_exists($path)) {
            return [];
        }

        return json_decode(file_get_contents($path), true) ?? [];
    }

    /**
     * Flag emoji from ISO 3166-1 alpha-2 code (e.g. "GB" → 🇬🇧).
     */
    public function getFlagAttribute(): string
    {
        $code = $this->code_2 ?? '';

        if (strlen($code) !== 2) {
            return '';
        }

        return mb_chr(0x1F1E6 + ord($code[0]) - 65)
            .mb_chr(0x1F1E6 + ord($code[1]) - 65);
    }

    /**
     * Return the same shape as the old CountriesQuery: [code => ['name' => ..., 'flag' => ...]].
     */
    public static function forDropdown(?array $filterCodes = null): array
    {
        $query = static::query()->orderBy('name');

        if ($filterCodes !== null) {
            $query->whereIn('code', $filterCodes);
        }

        return $query->get()
            ->keyBy('code')
            ->map(fn (Country $c): array => [
                'name' => $c->name,
                'flag' => $c->flag,
            ])
            ->all();
    }
}
