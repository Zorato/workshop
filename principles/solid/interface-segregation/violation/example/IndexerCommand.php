<?php

class IndexerCommand
{

    public function run($arguments)
    {
        if (!isset($arguments['table'])) {
            return false;
        }

        // indexing logic

        return 'Done!';
    }

}