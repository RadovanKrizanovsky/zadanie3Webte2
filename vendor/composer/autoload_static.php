<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit915e5f5fc4f5cad181d4e4f77a863163
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'Workerman\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Workerman\\' => 
        array (
            0 => __DIR__ . '/..' . '/workerman/workerman',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit915e5f5fc4f5cad181d4e4f77a863163::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit915e5f5fc4f5cad181d4e4f77a863163::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit915e5f5fc4f5cad181d4e4f77a863163::$classMap;

        }, null, ClassLoader::class);
    }
}
