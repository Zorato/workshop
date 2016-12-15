<?php

$storage = new Storage(5);

// Add some dummy objects
$storage->addObject(new stdClass());
$storage->addObject(new stdClass());
$storage->addObject(new stdClass());

// Apply some logic for checking limits
if ($storage->getLimit() >= count($storage->getObjects())) {
    throw new RuntimeException('Storage limit reached');
} else {
    $storage->addObject(new stdClass());
}