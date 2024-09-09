<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
 * @property int $score
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Driver $p1
 * @property-read \App\Models\Driver $p10
 * @property-read \App\Models\Driver $p2
 * @property-read \App\Models\Driver $p3
 * @property-read \App\Models\Driver $p4
 * @property-read \App\Models\Driver $p5
 * @property-read \App\Models\Driver $p6
 * @property-read \App\Models\Driver $p7
 * @property-read \App\Models\Driver $p8
 * @property-read \App\Models\Driver $p9
 * @property-read \App\Models\RaceSession $raceSession
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\GuessFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Guess newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Guess newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Guess query()
 * @method static \Illuminate\Database\Eloquent\Builder|Guess whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guess whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guess whereP10Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guess whereP1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guess whereP2Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guess whereP3Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guess whereP4Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guess whereP5Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guess whereP6Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guess whereP7Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guess whereP8Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guess whereP9Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guess whereRaceSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guess whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guess whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guess whereUserId($value)
 * @mixin \Illuminate\Database\Eloquent\Model
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
