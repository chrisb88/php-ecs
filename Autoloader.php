<?php

spl_autoload_register(function ($className) {
    $classFile = str_replace('\\', '/', $className) . '.php';
//
//    if ($classFile === false || !is_file($classFile)) {
//        return;
//    }

    include $classFile;

    if (!class_exists($className, false) && !interface_exists($className, false) && !trait_exists($className, false)) {
        var_dump($className);
        throw new Exception("Unable to find '$className' in file: $classFile. Namespace missing?");
    }
});
