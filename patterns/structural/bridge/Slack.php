<?php

/**
 * Class Slack
 */
class Slack implements MessengerInterface
{
    /**
     * @inheritDoc
     */
    public function send($message)
    {
        file_get_contents('https://slack.com/send?message='.$message);
    }
}