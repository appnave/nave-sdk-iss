<?php

namespace BildVitta\Hub\Models;

use BildVitta\Hub\Traits\UsesHubDB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $uuid
 * @property int $company_id
 * @property string $name
 * @property string|null $description
 * @property string $type
 * @property string $slug
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Company|null $company
 * @property-read Collection<int, EnvironmentVariableData> $environment_variable_data
 * @property-read int|null $environment_variable_data_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnvironmentVariable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnvironmentVariable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnvironmentVariable onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnvironmentVariable query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnvironmentVariable whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnvironmentVariable whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnvironmentVariable whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnvironmentVariable whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnvironmentVariable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnvironmentVariable whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnvironmentVariable whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnvironmentVariable whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnvironmentVariable whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnvironmentVariable whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnvironmentVariable withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnvironmentVariable withoutTrashed()
 *
 * @mixin \Eloquent
 */
class EnvironmentVariable extends Model
{
    use SoftDeletes;
    use UsesHubDB;

    protected $connection = 'iss-sdk';

    protected $guarded = [
        'id',
        'uuid',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (EnvironmentVariable $model) {
            $model->uuid = Str::uuid()->toString();
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function environment_variable_data(): HasMany
    {
        return $this->hasMany(EnvironmentVariableData::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Services::class);
    }
}
