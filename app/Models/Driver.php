<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\DriverFactory;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 *
 * @property int $id
 * @property int $number
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, DriverContract> $contracts
 * @property-read int|null $contracts_count
 * @method static DriverFactory factory($count = null, $state = [])
 * @method static Builder|Driver newModelQuery()
 * @method static Builder|Driver newQuery()
 * @method static Builder|Driver query()
 * @method static Builder|Driver whereCreatedAt($value)
 * @method static Builder|Driver whereId($value)
 * @method static Builder|Driver whereName($value)
 * @method static Builder|Driver whereNumber($value)
 * @method static Builder|Driver whereUpdatedAt($value)
 * @mixin Model
 */
class Driver extends Model
{
    use HasFactory;

    public function contracts(): HasMany
    {
        return $this->hasMany(DriverContract::class);
    }

    public function getCurrentContract(DateTimeInterface $onDate = null): ?DriverContract
    {
        if (!$this->relationLoaded('contracts')) {
            return DriverContract::active($onDate)
                ->whereDriverId($this->id)
                ->get()
                ->first();
        }

        return $this->contracts->first(function (DriverContract $contract) use ($onDate): ?DriverContract {
            $onDate ??= Carbon::now()->toDateTime();
            if ($contract->start_date <= $onDate && ($contract->end_date === null || $contract->end_date > $onDate)) {
                return $contract;
            }

            return null;
        });
    }
}
