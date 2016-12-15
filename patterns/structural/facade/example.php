<?php

$d = new Discriminant(new Subtractor(), new Multiplier());
assert($d->calculate(2, 5, 3) == 1);