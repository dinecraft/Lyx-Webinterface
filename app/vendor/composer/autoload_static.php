<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit38d956c1b74ddc841976dfd45bf9e4e6
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/plugins',
        ),
    );

    public static $classMap = array (
        'App\\Plugins\\Native\\SetupPlugin\\autoDestroy' => __DIR__ . '/../..' . '/plugins/Native/SetupPlugin/autoDestroy.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit38d956c1b74ddc841976dfd45bf9e4e6::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit38d956c1b74ddc841976dfd45bf9e4e6::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit38d956c1b74ddc841976dfd45bf9e4e6::$classMap;

        }, null, ClassLoader::class);
    }
}
