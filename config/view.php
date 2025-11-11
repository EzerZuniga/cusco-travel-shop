<?php

$base = dirname(__DIR__);

return [
    /*
    |-----------------------------------------------------------------------
    | View Storage Paths
    |-----------------------------------------------------------------------
    |
    | Most templating systems load templates from disk. Here you may specify
    | an array of paths where your templates will be stored. Of course
    | the usual Laravel view path has already been registered for you.
    |
    */
    'paths' => [
        $base . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views',
    ],

    /*
    |-----------------------------------------------------------------------
    | Compiled View Path
    |-----------------------------------------------------------------------
    |
    | This option determines where all the compiled Blade templates will be
    | stored for your application. Typically, this is within the storage
    | directory. You are free to change this value to any place you like.
    |
    */
    'compiled' => $base . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'views',
];
