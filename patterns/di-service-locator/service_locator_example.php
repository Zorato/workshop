<?php

$serviceLocator = new ServiceLocator();
$serviceLocator->set('qwerty', function () {
    return
        new Service(
            new PDORepository(
                new DBConnection(
                    Config::get('database')
                )
            )
    );
});

/** @var Service $service */
$service = $serviceLocator->get('qwerty');
$service->act($_POST);

// see Pimple at http://pimple.sensiolabs.org
