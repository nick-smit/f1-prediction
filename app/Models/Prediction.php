<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PredictionFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

/**
 *
 *
 * @property-read Driver|null $p1
 * @property-read Driver|null $p10
 * @property-read Driver|null $p2
 * @property-read Driver|null $p3
 * @property-read Driver|null $p4
 * @property-read Driver|null $p5
 * @property-read Driver|null $p6
 * @property-read Driver|null $p7
 * @property-read Driver|null $p8
 * @property-read Driver|null $p9
 * @property-read RaceSession|null $raceSession
 * @property-read User|null $user
 * @method static PredictionFactory factory($count = null, $state = [])
 * @method static Builder|Prediction newModelQuery()
 * @method static Builder|Prediction newQuery()
 * @method static Builder|Prediction query()
 * @mixin Model
 */
class Prediction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'race_session_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function raceSession(): BelongsTo
    {
        return $this->belongsTo(RaceSession::class);
    }

    public function p1(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function p2(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function p3(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function p4(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function p5(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function p6(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function p7(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function p8(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function p9(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function p10(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function getDrivers(): Collection
    {
        $drivers = Driver::query()
            ->whereIn('id', [
                $this->p1_id,
                $this->p2_id,
                $this->p3_id,
                $this->p4_id,
                $this->p5_id,
                $this->p6_id,
                $this->p7_id,
                $this->p8_id,
                $this->p9_id,
                $this->p10_id,
            ])->get()
            ->keyBy('id');

        return new Collection([
            $drivers[$this->p1_id],
            $drivers[$this->p2_id],
            $drivers[$this->p3_id],
            $drivers[$this->p4_id],
            $drivers[$this->p5_id],
            $drivers[$this->p6_id],
            $drivers[$this->p7_id],
            $drivers[$this->p8_id],
            $drivers[$this->p9_id],
            $drivers[$this->p10_id],
        ]);
    }
}
