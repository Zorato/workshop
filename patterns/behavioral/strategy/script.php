<?php

$sorter = new Sorter(new LowToHigh());
$sorter->sort([1, 3, 7, 2, 4]);

$otherwise = new Sorter(new HighToLow());
$otherwise->sort([1, 3, 7, 2, 4]);