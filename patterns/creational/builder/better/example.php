<?php

// Instantiate
$builder = new ShipmentRequestBuilder();

// Configure
$builder->setAddress('Some address');
$builder->setRecipientName('Test Test');

// Get result
$request = $builder->build();

$builder->setTotalWeight(2.5);
$request2 = $builder->build();

$builder->setPackages(5);
$request3 = $builder->build();
