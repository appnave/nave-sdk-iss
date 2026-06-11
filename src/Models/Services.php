<?php

namespace BildVitta\Hub\Models;

use BildVitta\Hub\Traits\UsesHubDB;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $uuid
 * @property int $company_id
 * @property string $name
 * @property string|null $description
 * @property string $type
 * @property string $adapter
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Company|null $company
 * @property-read SupportCollection<int, EnvironmentVariable> $environment_variables
 * @property-read int|null $environment_variables_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Services newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Services newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Services onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Services query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Services whereAdapter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Services whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Services whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Services whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Services whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Services whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Services whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Services whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Services whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Services whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Services withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Services withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Services extends Model
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

        static::creating(function (Services $model) {
            $model->uuid = Str::uuid()->toString();
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function environment_variables(): BelongsToMany
    {
        return $this->belongsToMany(EnvironmentVariable::class);
    }

    public function getCredentials(): SupportCollection
    {
        return $this->environment_variables
            ->load('environment_variable_data')
            ->groupBy('type')
            ->map(function (EloquentCollection $environmentVariable) {
                return [
                    'type' => $environmentVariable->first()->type,
                    'credentials' => $environmentVariable->first()->environment_variable_data->pluck('value', 'key')->toArray(),
                ];
            })
            ->merge([
                'adapter' => $this->adapter,
            ]);
    }
}
