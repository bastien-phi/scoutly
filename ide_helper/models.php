<?php

declare(strict_types=1);

namespace IdeHelper\App\Models
{
    /**
     * @property int $id
     * @property int $user_id
     * @property string $name
     * @property \Carbon\CarbonImmutable $created_at
     * @property \Carbon\CarbonImmutable $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Link> $links
     * @property-read \App\Models\User $user
     *
     * @method static \Database\Factories\AuthorFactory factory($count = 1, $state = [])
     * @method static \IdeHelper\App\Models\__AuthorQuery query()
     *
     * @mixin \IdeHelper\App\Models\__AuthorQuery
     *
     * @method \IdeHelper\App\Models\Author\__Links links()
     * @method \IdeHelper\App\Models\Author\__User user()
     */
    class __Author {}

    /**
     * @method $this whereId(int|string $value)
     * @method $this whereUserId(int|string $value)
     * @method $this whereName(string $value)
     * @method $this whereCreatedAt(\Carbon\CarbonImmutable|string $value)
     * @method $this whereUpdatedAt(\Carbon\CarbonImmutable|string $value)
     * @method \App\Models\Author create(array $attributes = [])
     * @method \Illuminate\Database\Eloquent\Collection<int, \App\Models\Author>|\App\Models\Author|null find($id, array $columns = ['*'])
     * @method \Illuminate\Database\Eloquent\Collection<int, \App\Models\Author> findMany($id, array $columns = ['*'])
     * @method \Illuminate\Database\Eloquent\Collection<int, \App\Models\Author>|\App\Models\Author findOrFail($id, array $columns = ['*'])
     * @method \App\Models\Author findOrNew($id, array $columns = ['*'])
     * @method \App\Models\Author|null first(array|string $columns = ['*'])
     * @method \App\Models\Author firstOrCreate(array $attributes, array $values = [])
     * @method \App\Models\Author firstOrFail(array $columns = ['*'])
     * @method \App\Models\Author firstOrNew(array $attributes = [], array $values = [])
     * @method \App\Models\Author forceCreate(array $attributes = [])
     * @method \Illuminate\Database\Eloquent\Collection<int, \App\Models\Author> get(array|string $columns = ['*'])
     * @method \App\Models\Author getModel()
     * @method \Illuminate\Database\Eloquent\Collection<int, \App\Models\Author> getModels(array|string $columns = ['*'])
     * @method \App\Models\Author newModelInstance(array $attributes = [])
     * @method \App\Models\Author sole(array|string $columns = ['*'])
     * @method \App\Models\Author updateOrCreate(array $attributes, array $values = [])
     *
     * @template TModelClass of \App\Models\Author
     *
     * @extends \Illuminate\Database\Eloquent\Builder<TModelClass>
     */
    class __AuthorQuery extends \Illuminate\Database\Eloquent\Builder {}

    /**
     * @property int $id
     * @property int $user_id
     * @property int|null $author_id
     * @property string|null $title
     * @property string $url
     * @property string|null $description
     * @property \Carbon\CarbonImmutable|null $published_at
     * @property \Carbon\CarbonImmutable $created_at
     * @property \Carbon\CarbonImmutable $updated_at
     * @property bool $is_public
     * @property-read \App\Models\Author|null $author
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
     * @property-read \App\Models\User $user
     *
     * @method static \Database\Factories\LinkFactory factory($count = 1, $state = [])
     * @method static \IdeHelper\App\Models\__LinkQuery query()
     *
     * @mixin \IdeHelper\App\Models\__LinkQuery
     *
     * @method \IdeHelper\App\Models\Link\__Author author()
     * @method \IdeHelper\App\Models\Link\__Tags tags()
     * @method \IdeHelper\App\Models\Link\__User user()
     */
    class __Link {}

    /**
     * @method $this whereId(int|string $value)
     * @method $this whereUserId(int|string $value)
     * @method $this whereAuthorId(int|string|null $value)
     * @method $this whereTitle(string|null $value)
     * @method $this whereUrl(string $value)
     * @method $this whereDescription(string|null $value)
     * @method $this wherePublishedAt(\Carbon\CarbonImmutable|string|null $value)
     * @method $this whereCreatedAt(\Carbon\CarbonImmutable|string $value)
     * @method $this whereUpdatedAt(\Carbon\CarbonImmutable|string $value)
     * @method $this whereIsPublic(bool|string $value)
     * @method $this whereDraft()
     *
     * @see project://app/Models/Link.php L94
     *
     * @method $this wherePublic()
     *
     * @see project://app/Models/Link.php L103
     *
     * @method $this wherePublished()
     *
     * @see project://app/Models/Link.php L112
     *
     * @method \App\Models\Link create(array $attributes = [])
     * @method \Illuminate\Database\Eloquent\Collection<int, \App\Models\Link>|\App\Models\Link|null find($id, array $columns = ['*'])
     * @method \Illuminate\Database\Eloquent\Collection<int, \App\Models\Link> findMany($id, array $columns = ['*'])
     * @method \Illuminate\Database\Eloquent\Collection<int, \App\Models\Link>|\App\Models\Link findOrFail($id, array $columns = ['*'])
     * @method \App\Models\Link findOrNew($id, array $columns = ['*'])
     * @method \App\Models\Link|null first(array|string $columns = ['*'])
     * @method \App\Models\Link firstOrCreate(array $attributes, array $values = [])
     * @method \App\Models\Link firstOrFail(array $columns = ['*'])
     * @method \App\Models\Link firstOrNew(array $attributes = [], array $values = [])
     * @method \App\Models\Link forceCreate(array $attributes = [])
     * @method \Illuminate\Database\Eloquent\Collection<int, \App\Models\Link> get(array|string $columns = ['*'])
     * @method \App\Models\Link getModel()
     * @method \Illuminate\Database\Eloquent\Collection<int, \App\Models\Link> getModels(array|string $columns = ['*'])
     * @method \App\Models\Link newModelInstance(array $attributes = [])
     * @method \App\Models\Link sole(array|string $columns = ['*'])
     * @method \App\Models\Link updateOrCreate(array $attributes, array $values = [])
     *
     * @template TModelClass of \App\Models\Link
     *
     * @extends \Illuminate\Database\Eloquent\Builder<TModelClass>
     */
    class __LinkQuery extends \Illuminate\Database\Eloquent\Builder {}

    /**
     * @property int $id
     * @property int $user_id
     * @property string $label
     * @property \Carbon\CarbonImmutable $created_at
     * @property \Carbon\CarbonImmutable $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Link> $links
     * @property-read \App\Models\User $user
     *
     * @method static \Database\Factories\TagFactory factory($count = 1, $state = [])
     * @method static \IdeHelper\App\Models\__TagQuery query()
     *
     * @mixin \IdeHelper\App\Models\__TagQuery
     *
     * @method \IdeHelper\App\Models\Tag\__Links links()
     * @method \IdeHelper\App\Models\Tag\__User user()
     */
    class __Tag {}

    /**
     * @method $this whereId(int|string $value)
     * @method $this whereUserId(int|string $value)
     * @method $this whereLabel(string $value)
     * @method $this whereCreatedAt(\Carbon\CarbonImmutable|string $value)
     * @method $this whereUpdatedAt(\Carbon\CarbonImmutable|string $value)
     * @method \App\Models\Tag create(array $attributes = [])
     * @method \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag>|\App\Models\Tag|null find($id, array $columns = ['*'])
     * @method \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> findMany($id, array $columns = ['*'])
     * @method \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag>|\App\Models\Tag findOrFail($id, array $columns = ['*'])
     * @method \App\Models\Tag findOrNew($id, array $columns = ['*'])
     * @method \App\Models\Tag|null first(array|string $columns = ['*'])
     * @method \App\Models\Tag firstOrCreate(array $attributes, array $values = [])
     * @method \App\Models\Tag firstOrFail(array $columns = ['*'])
     * @method \App\Models\Tag firstOrNew(array $attributes = [], array $values = [])
     * @method \App\Models\Tag forceCreate(array $attributes = [])
     * @method \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> get(array|string $columns = ['*'])
     * @method \App\Models\Tag getModel()
     * @method \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> getModels(array|string $columns = ['*'])
     * @method \App\Models\Tag newModelInstance(array $attributes = [])
     * @method \App\Models\Tag sole(array|string $columns = ['*'])
     * @method \App\Models\Tag updateOrCreate(array $attributes, array $values = [])
     *
     * @template TModelClass of \App\Models\Tag
     *
     * @extends \Illuminate\Database\Eloquent\Builder<TModelClass>
     */
    class __TagQuery extends \Illuminate\Database\Eloquent\Builder {}

    /**
     * @property int $id
     * @property string $name
     * @property string $username
     * @property string $email
     * @property \Carbon\CarbonImmutable|null $email_verified_at
     * @property string $password
     * @property string|null $remember_token
     * @property \Carbon\CarbonImmutable $created_at
     * @property \Carbon\CarbonImmutable $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Author> $authors
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Link> $links
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
     *
     * @method static \Database\Factories\UserFactory factory($count = 1, $state = [])
     * @method static \IdeHelper\App\Models\__UserQuery query()
     *
     * @mixin \IdeHelper\App\Models\__UserQuery
     *
     * @method \IdeHelper\App\Models\User\__Authors authors()
     * @method \IdeHelper\App\Models\User\__Links links()
     * @method \IdeHelper\App\Models\User\__Tags tags()
     */
    class __User {}

    /**
     * @method $this whereId(int|string $value)
     * @method $this whereName(string $value)
     * @method $this whereUsername(string $value)
     * @method $this whereEmail(string $value)
     * @method $this whereEmailVerifiedAt(\Carbon\CarbonImmutable|string|null $value)
     * @method $this wherePassword(string $value)
     * @method $this whereRememberToken(string|null $value)
     * @method $this whereCreatedAt(\Carbon\CarbonImmutable|string $value)
     * @method $this whereUpdatedAt(\Carbon\CarbonImmutable|string $value)
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

namespace IdeHelper\App\Models\Author
{
    /**
     * @mixin \IdeHelper\App\Models\__LinkQuery
     * @mixin \Illuminate\Database\Eloquent\Relations\HasMany
     */
    class __Links {}

    /**
     * @mixin \IdeHelper\App\Models\__UserQuery
     * @mixin \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    class __User {}
}

namespace IdeHelper\App\Models\Link
{
    /**
     * @mixin \IdeHelper\App\Models\__AuthorQuery
     * @mixin \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    class __Author {}

    /**
     * @mixin \IdeHelper\App\Models\__TagQuery
     * @mixin \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    class __Tags {}

    /**
     * @mixin \IdeHelper\App\Models\__UserQuery
     * @mixin \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    class __User {}
}

namespace IdeHelper\App\Models\Tag
{
    /**
     * @mixin \IdeHelper\App\Models\__LinkQuery
     * @mixin \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    class __Links {}

    /**
     * @mixin \IdeHelper\App\Models\__UserQuery
     * @mixin \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    class __User {}
}

namespace IdeHelper\App\Models\User
{
    /**
     * @mixin \IdeHelper\App\Models\__AuthorQuery
     * @mixin \Illuminate\Database\Eloquent\Relations\HasMany
     */
    class __Authors {}

    /**
     * @mixin \IdeHelper\App\Models\__LinkQuery
     * @mixin \Illuminate\Database\Eloquent\Relations\HasMany
     */
    class __Links {}

    /**
     * @mixin \IdeHelper\App\Models\__TagQuery
     * @mixin \Illuminate\Database\Eloquent\Relations\HasMany
     */
    class __Tags {}
}
