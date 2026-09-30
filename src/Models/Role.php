<?php

namespace BildVitta\Hub\Models;

use BildVitta\Hub\Traits\UsesHubDB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Ramsey\Uuid\Uuid;

class Role extends Model
{
    use UsesHubDB;

    protected $connection = 'iss-sdk';

    protected $table = 'roles';

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

    public function user_companies(): BelongsToMany
    {
        return $this->belongsToMany(
            UserCompany::class,
            'model_has_roles',
            'role_id',
            'model_id'
        )->wherePivot('model_type', 'App\Models\UserCompany');
    }
}
