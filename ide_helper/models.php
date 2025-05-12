<?php

namespace IdeHelper\App\Models
{
    /**
     * @property int $id
     * @property string $name
     * @property string $email
     * @property \Illuminate\Support\Carbon|null $email_verified_at
     * @property string $password
     * @property string|null $remember_token
     * @property \Illuminate\Support\Carbon $created_at
     * @property \Illuminate\Support\Carbon $updated_at
     *
     * @method static \Database\Factories\UserFactory factory($count = 1, $state = [])
     * @method static \IdeHelper\App\Models\__UserQuery query()
     *
     * @mixin \IdeHelper\App\Models\__UserQuery
     */
    class __User {}

    /**
     * @method $this whereId(int|string $value)
     * @method $this whereName(string $value)
     * @method $this whereEmail(string $value)
     * @method $this whereEmailVerifiedAt(\Illuminate\Support\Carbon|string|null $value)
     * @method $this wherePassword(string $value)
     * @method $this whereRememberToken(string|null $value)
     * @method $this whereCreatedAt(\Illuminate\Support\Carbon|string $value)
     * @method $this whereUpdatedAt(\Illuminate\Support\Carbon|string $value)
     * @method \App\Models\User create(array $attributes = [])
     * @method \Illuminate\Database\Eloquent\Collection<int, \App\Models\User>|\App\Models\User|null find($id, array $columns = ['*'])
     * @method \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> findMany($id, array $columns = ['*'])
     * @method \Illuminate\Database\Eloquent\Collection<int, \App\Models\User>|\App\Models\User findOrFail($id, array $columns = ['*'])
     * @method \App\Models\User findOrNew($id, array $columns = ['*'])
     * @method \App\Models\User|null first(array|string $columns = ['*'])
     * @method \App\Models\User firstOrCreate(array $attributes, array $values = [])
     * @method \App\Models\User firstOrFail(array $columns = ['*'])
     * @method \App\Models\User firstOrNew(array $attributes = [], array $values = [])
     * @method \App\Models\User forceCreate(array $attributes = [])
     * @method \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> get(array|string $columns = ['*'])
     * @method \App\Models\User getModel()
     * @method \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> getModels(array|string $columns = ['*'])
     * @method \App\Models\User newModelInstance(array $attributes = [])
     * @method \App\Models\User sole(array|string $columns = ['*'])
     * @method \App\Models\User updateOrCreate(array $attributes, array $values = [])
     *
     * @template TModelClass of \App\Models\User
     *
     * @extends \Illuminate\Database\Eloquent\Builder<TModelClass>
     */
    class __UserQuery extends \Illuminate\Database\Eloquent\Builder {}
}
