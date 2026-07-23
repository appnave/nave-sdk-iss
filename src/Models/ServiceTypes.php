<?php

namespace BildVitta\Hub\Models;

use BildVitta\Hub\Traits\UsesHubDB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $label
 * @property string $slug
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTypes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTypes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTypes query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTypes whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTypes whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTypes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTypes whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTypes whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTypes whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTypes whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTypes whereUuid($value)
 *
 * @mixin \Eloquent
 */
class ServiceTypes extends Model
{
    use SoftDeletes;
    use UsesHubDB;

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

        static::creating(function (ServiceTypes $model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
}
