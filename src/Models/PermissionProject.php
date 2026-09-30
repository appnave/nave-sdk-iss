<?php

namespace BildVitta\Hub\Models;

use BildVitta\Hub\Traits\UsesHubDB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class PermissionProject extends Model
{
    use SoftDeletes;
    use UsesHubDB;

    protected $connection = 'iss-sdk';

    protected $table = 'permissions_project';

    protected $guard_name = 'web';

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = (string) Uuid::uuid4();
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    //

    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class, 'project_id', 'id');
    }
}
