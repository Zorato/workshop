<?php

$service = new FileReadingService(new RestrictingFileReader(new CachingFileReader(new SimpleFileReader())));
echo $service->process('file');