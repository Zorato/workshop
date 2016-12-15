<?php

$pipeline = new Pipeline(new FirstStep());
$pipeline->addStep(new SecondStep());

$another = (new Pipeline(new SecondStep()))->addStep(new ThirdStep());

$pipeline->addStep($another);
echo $pipeline->handle(100);
