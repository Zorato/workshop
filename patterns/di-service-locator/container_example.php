<?php

$container = new Container();
$container->bind(Repository::class, PDORepository::class);
$container->singleton(Connection::class, function() {
    return new DBConnection(Config::get());
});

/** @var Service $service */
$service = $container->make(Service::class);
$service->act($_POST);