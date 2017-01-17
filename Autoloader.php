<?php

spl_autoload_register(function ($className) {
    $classFile = str_replace('\\', '/', $className) . '.php';
    if ($classFile != '' && file_exists($classFile) && is_file($classFile)) {
        include $classFile;
    }

    if (!class_exists($className, false) && !interface_exists($className, false) && !trait_exists($className, false)) {
        throw new Exception("Unable to find '$className' in file: $classFile. Namespace missing?");
    }
});
