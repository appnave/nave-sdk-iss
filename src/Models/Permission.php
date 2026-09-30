<?php

namespace BildVitta\Hub\Models;

use BildVitta\Hub\Traits\UsesHubDB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

class Permission extends Model
{
    use UsesHubDB;

    protected $connection = 'iss-sdk';

    protected $table = 'permissions';

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

    public function project(): BelongsTo
    {
        return $this->belongsTo(PermissionProject::class, 'project_id', 'id');
    }

    public function user_companies(): BelongsToMany
    {
        return $this->belongsToMany(
            UserCompany::class,
            'model_has_permissions',
            'permission_id',
            'model_id'
        )->wherePivot('model_type', 'App\Models\UserCompany');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'role_has_permissions',
            'permission_id',
            'role_id'
        );
    }

    public function getUsers(array|null $attributes = null): Collection
    {
        $userColumns = $attributes
            ? array_values(array_unique(array_merge(['id', 'name'], $attributes)))
            : ['*'];

        $directUsers = $this->user_companies()
            ->select(['user_companies.id', 'user_companies.user_id'])
            ->with(['user' => fn ($query) => $query->select($userColumns)])
            ->get()
            ->pluck('user');

        $roleIds = $this->roles()->pluck('roles.id');
        $roleUsers = UserCompany::query()
            ->whereHas('roles', fn ($query) => 
                $query->whereIn('roles.id', $roleIds)
            )
            ->select(['user_companies.id', 'user_companies.user_id'])
            ->with(['user' => fn ($query) => $query->select($userColumns)])
            ->get()
            ->pluck('user');

        return $directUsers
            ->merge($roleUsers)
            ->unique('id')
            ->sortBy('name')
            ->values();
    }
}
