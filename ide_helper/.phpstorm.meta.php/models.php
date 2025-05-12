<?php

namespace PHPSTORM_META {

    override(\App\Models\User::query(), map(['' => \IdeHelper\App\Models\__UserQuery::class]));
    override(\App\Models\User::factory(), map(['' => \Database\Factories\UserFactory::class]));

}
