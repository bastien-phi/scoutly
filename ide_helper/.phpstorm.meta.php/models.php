<?php

namespace PHPSTORM_META {

    override(\App\Models\Author::query(), map(['' => \IdeHelper\App\Models\__AuthorQuery::class]));
    override(\App\Models\Author::factory(), map(['' => \Database\Factories\AuthorFactory::class]));
    override(\App\Models\Author::links(), map(['' => \IdeHelper\App\Models\Author\__Links::class]));
    override(\App\Models\Author::user(), map(['' => \IdeHelper\App\Models\Author\__User::class]));
    override(\App\Models\Link::query(), map(['' => \IdeHelper\App\Models\__LinkQuery::class]));
    override(\App\Models\Link::factory(), map(['' => \Database\Factories\LinkFactory::class]));
    override(\App\Models\Link::author(), map(['' => \IdeHelper\App\Models\Link\__Author::class]));
    override(\App\Models\Link::tags(), map(['' => \IdeHelper\App\Models\Link\__Tags::class]));
    override(\App\Models\Link::user(), map(['' => \IdeHelper\App\Models\Link\__User::class]));
    override(\App\Models\Tag::query(), map(['' => \IdeHelper\App\Models\__TagQuery::class]));
    override(\App\Models\Tag::factory(), map(['' => \Database\Factories\TagFactory::class]));
    override(\App\Models\Tag::links(), map(['' => \IdeHelper\App\Models\Tag\__Links::class]));
    override(\App\Models\Tag::user(), map(['' => \IdeHelper\App\Models\Tag\__User::class]));
    override(\App\Models\User::query(), map(['' => \IdeHelper\App\Models\__UserQuery::class]));
    override(\App\Models\User::factory(), map(['' => \Database\Factories\UserFactory::class]));
    override(\App\Models\User::authors(), map(['' => \IdeHelper\App\Models\User\__Authors::class]));
    override(\App\Models\User::links(), map(['' => \IdeHelper\App\Models\User\__Links::class]));
    override(\App\Models\User::tags(), map(['' => \IdeHelper\App\Models\User\__Tags::class]));

}
