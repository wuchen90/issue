<?php

declare(strict_types=1);

\passthru(\sprintf(
    'APP_ENV=%s php "%s/bin/console" cache:clear --no-warmup',
    $_ENV['APP_ENV'],
    __DIR__
));

require __DIR__ . '/config/bootstrap.php';
