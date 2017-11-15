<?php

\spl_autoload_register(function($class)
{
    if (\strpos($class, 'Prove\\') === 0)
    {
        $dir = \strcasecmp(\substr($class, -4), 'Test') ? 'src/' : 'tests/';
        $name = \substr($class, \strlen('Prove'));
        require __DIR__ . '/../' . $dir . strtr($name, '\\', DIRECTORY_SEPARATOR) . '.php';
    }
});


