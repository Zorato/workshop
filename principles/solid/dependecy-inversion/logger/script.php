<?php

if (getenv('ENVIRONMENT') == 'dev') {
    $container->bind(Logger::class, NullLogger::class);
} else {
    $container->bind(Logger::class, EchoLogger::class);
}

new SomeApplicationService($container->make(Logger::class));