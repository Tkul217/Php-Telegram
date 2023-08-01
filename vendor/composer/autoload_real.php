<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitbbfc08fc00cec38ff90670644f32574e
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInitbbfc08fc00cec38ff90670644f32574e', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitbbfc08fc00cec38ff90670644f32574e', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitbbfc08fc00cec38ff90670644f32574e::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}