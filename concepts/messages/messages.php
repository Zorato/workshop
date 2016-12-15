<?php

class Foo
{

    public function bar()
    {
        echo 5;
    }

    public function __call($name, $args)
    {
        echo "'$name' called with " . implode(', ', $args). ' arguments';
    }

}

$foo = new Foo();
$foo->bar();
echo PHP_EOL;

// Message (=method name and signature) is defined in the runtime, object still can respond/react on it.
$foo->method(1, 2, 3);
echo PHP_EOL;