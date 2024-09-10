<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\GuessFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property int $race_session_id
 * @property int $user_id
 * @property int $p1_id
 * @property int $p2_id
 * @property int $p3_id
 * @property int $p4_id
 * @property int $p5_id
 * @property int $p6_id
 * @property int $p7_id
 * @property int $p8_id
 * @property int $p9_id
 * @property int $p10_id
 * @property int|null $score
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Driver $p1
 * @property-read Driver $p10
 * @property-read Driver $p2
 * @property-read Driver $p3
 * @property-read Driver $p4
 * @property-read Driver $p5
 * @property-read Driver $p6
 * @property-read Driver $p7
 * @property-read Driver $p8
 * @property-read Driver $p9
 * @property-read RaceSession $raceSession
 * @property-read User $user
 * @method static GuessFactory factory($count = null, $state = [])
 * @method static Builder|Guess newModelQuery()
 * @method static Builder|Guess newQuery()
 * @method static Builder|Guess query()
 * @method static Builder|Guess whereCreatedAt($value)
 * @method static Builder|Guess whereId($value)
 * @method static Builder|Guess whereP10Id($value)
 * @method static Builder|Guess whereP1Id($value)
 * @method static Builder|Guess whereP2Id($value)
 * @method static Builder|Guess whereP3Id($value)
 * @method static Builder|Guess whereP4Id($value)
 * @method static Builder|Guess whereP5Id($value)
 * @method static Builder|Guess whereP6Id($value)
 * @method static Builder|Guess whereP7Id($value)
 * @method static Builder|Guess whereP8Id($value)
 * @method static Builder|Guess whereP9Id($value)
 * @method static Builder|Guess whereRaceSessionId($value)
 * @method static Builder|Guess whereScore($value)
 * @method static Builder|Guess whereUpdatedAt($value)
 * @method static Builder|Guess whereUserId($value)
 * @mixin Model
 */
class Guess extends Model
{
    use HasFactory;

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
}
