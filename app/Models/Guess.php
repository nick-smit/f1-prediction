<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
