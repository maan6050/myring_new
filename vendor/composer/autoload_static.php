<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit370cf246ee7a33f9fdb26dff41a05405
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Twilio\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Twilio\\' => 
        array (
            0 => __DIR__ . '/..' . '/twilio/sdk/Twilio',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit370cf246ee7a33f9fdb26dff41a05405::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit370cf246ee7a33f9fdb26dff41a05405::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
