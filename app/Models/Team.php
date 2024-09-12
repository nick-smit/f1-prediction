<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TeamFactory;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, DriverContract> $contracts
 * @property-read int|null $contracts_count
 * @method static TeamFactory factory($count = null, $state = [])
 * @method static Builder|Team newModelQuery()
 * @method static Builder|Team newQuery()
 * @method static Builder|Team query()
 * @method static Builder|Team whereCreatedAt($value)
 * @method static Builder|Team whereId($value)
 * @method static Builder|Team whereName($value)
 * @method static Builder|Team whereUpdatedAt($value)
 * @mixin Model
 */
class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function contracts(): HasMany
    {
        return $this->hasMany(DriverContract::class);
    }

    public function getCurrentContracts(DateTimeInterface $onDate = null): Collection
    {
        if (!$this->relationLoaded('contracts')) {
            return $this->contracts()->active($onDate)->get();
        }

        return $this->contracts->filter(function (DriverContract $contract) use ($onDate): ?DriverContract {
            $onDate ??= Carbon::now()->toDateTime();
            if ($contract->start_date <= $onDate && ($contract->end_date === null || $contract->end_date > $onDate)) {
                return $contract;
            }

            return null;
        });
    }
}
