<?php

$object = new stdClass();
$object->property = 'value';
$another = new stdClass();
$another->property = 'value';

// Though their properties are the same,
assert($object == $another);

// these are not the same instances,
assert($object !== $another);

// because each of the has unique PHP internal object identifier
var_dump($object, $another);