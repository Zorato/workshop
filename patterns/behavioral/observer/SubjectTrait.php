<?php

trait SubjectTrait
{

    /**
     * @var SplObserver[]
     */
    private $observers = [];

    public function attach(SplObserver $observer)
    {
        $this->observers[] = $observer;
    }

    public function detach(SplObserver $observer)
    {
        foreach ($this->observers as $index => $o) {
            if ($o === $observer) {
                unset($this->observers[$index]);
                break;
            }
        }
    }

    public function notify()
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

}