<?php

/**
 * Interface Registry
 *
 * Some abstract interface we want to use
 *
 */
interface Registry {

    public function getValue($key);

    public function setValue($key, $value);

    public function has($key);

}