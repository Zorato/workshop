<?php

$command = new IndexerCommand();
$result = $command->run(['args']);

/** @var ResponseEmitter $emitter */
$emitter = $container->make(ResponseEmitter::class);

$emitter->sendHeader();
$emitter->sendBody();
