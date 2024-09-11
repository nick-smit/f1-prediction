<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\SessionResultFactory;
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
 * @method static SessionResultFactory factory($count = null, $state = [])
 * @method static Builder|SessionResult newModelQuery()
 * @method static Builder|SessionResult newQuery()
 * @method static Builder|SessionResult query()
 * @method static Builder|SessionResult whereCreatedAt($value)
 * @method static Builder|SessionResult whereId($value)
 * @method static Builder|SessionResult whereP10Id($value)
 * @method static Builder|SessionResult whereP1Id($value)
 * @method static Builder|SessionResult whereP2Id($value)
 * @method static Builder|SessionResult whereP3Id($value)
 * @method static Builder|SessionResult whereP4Id($value)
 * @method static Builder|SessionResult whereP5Id($value)
 * @method static Builder|SessionResult whereP6Id($value)
 * @method static Builder|SessionResult whereP7Id($value)
 * @method static Builder|SessionResult whereP8Id($value)
 * @method static Builder|SessionResult whereP9Id($value)
 * @method static Builder|SessionResult whereRaceSessionId($value)
 * @method static Builder|SessionResult whereUpdatedAt($value)
 * @mixin Model
 */
class SessionResult extends Model
{
    use HasFactory;

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
