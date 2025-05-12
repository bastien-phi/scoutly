<?php

declare(strict_types=1);

arch()->preset()->php();

arch()->preset()->security();

arch()->preset()->laravel();

arch()->expect('App')->toUseStrictTypes();

arch()->expect('App')->toUseStrictEquality();

arch()->expect(['sleep', 'usleep'])->not()->toBeUsed();
