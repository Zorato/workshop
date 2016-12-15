<?php

class Pipeline implements Step
{
    /**
     * @var Step[]
     */
    private $steps = [];

    public function __construct(Step $initialStep)
    {
        $this->addStep($initialStep);
    }

    public function addStep(Step $step)
    {
        $this->steps[] = $step;
        return $this;
    }

    public function handle($payload)
    {
        foreach ($this->steps as $step) {
            $payload = $step->handle($payload);
        }

        return $payload;
    }


}