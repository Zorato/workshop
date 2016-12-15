<?php

/**
 * Class RegistryConsumer
 *
 * Some service class that uses our registry interface.
 * It is not aware of concrete implementation,
 * only uses contact for type-hinting and method calls.
 *
 */
class RegistryConsumer {

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @param Registry $registry
     * @return void
     */
    public function setRegistry(Registry $registry)
    {
        $this->registry = $registry;
    }

    public function run($parameter)
    {
        if ($this->registry->has('parameter')) {
            $parameter = $this->registry->getValue('parameter');
        } else {
            $this->registry->setValue('parameter', $parameter);
        }
        // some more logic here...
    }

}