<?php

namespace BildVitta\Hub\Models;

use BildVitta\Hub\Casts\EnvironmentVariableEncryptCast;
use BildVitta\Hub\Traits\UsesHubDB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $environment_variable_id
 * @property string $key
 * @property $value
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read EnvironmentVariable|null $provider
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnvironmentVariableData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnvironmentVariableData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnvironmentVariableData query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnvironmentVariableData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnvironmentVariableData whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnvironmentVariableData whereEnvironmentVariableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnvironmentVariableData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnvironmentVariableData whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnvironmentVariableData whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnvironmentVariableData whereValue($value)
 *
 * @mixin \Eloquent
 */
class EnvironmentVariableData extends Model
{
    use SoftDeletes;
    use UsesHubDB;

    protected $connection = 'iss-sdk';

    protected $guarded = [
        'id',
        '$environment_variable_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'value' => EnvironmentVariableEncryptCast::class,
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(EnvironmentVariable::class);
    }
}
