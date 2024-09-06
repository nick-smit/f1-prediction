<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property int $number
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\DriverFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Driver newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Driver newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Driver query()
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Driver extends Model
{
    use HasFactory;
}
