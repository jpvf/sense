<?php

class Autoloader
{
    private static $_namespace;
    private static $_includePath;
    private static $_namespaceSeparator = '\\';

    /**
     * Creates a new <tt>SplClassLoader</tt> that loads classes of the
     * specified namespace.
     * 
     * @param string $ns The namespace to use.
     */
    public static function init($ns = null, $includePath = null)
    {
        static::$_namespace = $ns;
        static::$_includePath = $includePath;
    }

    /**
     * Sets the namespace separator used by classes in the namespace of this class loader.
     * 
     * @param string $sep The separator to use.
     */
    public function setNamespaceSeparator($sep)
    {
        $this->_namespaceSeparator = $sep;
    }

    /**
     * Gets the namespace seperator used by classes in the namespace of this class loader.
     *
     * @return void
     */
    public function getNamespaceSeparator()
    {
        return $this->_namespaceSeparator;
    }

    /**
     * Sets the base include path for all class files in the namespace of this class loader.
     * 
     * @param string $includePath
     */
    public function setIncludePath($includePath)
    {
        $this->_includePath = $includePath;
    }

    /**
     * Gets the base include path for all class files in the namespace of this class loader.
     *
     * @return string $includePath
     */
    public function getIncludePath()
    {
        return $this->_includePath;
    }

    /**
     * Installs this class loader on the SPL autoload stack.
     */
    public static function register()
    {
        spl_autoload_register('static::loadClass');
    }

    /**
     * Uninstalls this class loader from the SPL autoloader stack.
     */
    public function unregister()
    {
        spl_autoload_unregister('static::loadClass');
    }

    /**
     * Loads the given class or interface.
     *
     * @param string $className The name of the class to load.
     * @return void
     */
    public static function loadClass($className)
    {
        if (null === static::$_namespace || static::$_namespace.static::$_namespaceSeparator === substr($className, 0, strlen(static::$_namespace.static::$_namespaceSeparator)))
        {
            $fileName = '';
            $namespace = '';

            if (false !== ($lastNsPos = strripos($className, static::$_namespaceSeparator))) 
            {
                $namespace = substr($className, 0, $lastNsPos);
                $className = substr($className, $lastNsPos + 1);
                $fileName = str_replace(static::$_namespaceSeparator, DIRECTORY_SEPARATOR, $namespace).DIRECTORY_SEPARATOR;
            }

            $fileName .= $className.EXT;

            $file = (static::$_includePath !== null ? static::$_includePath.DIRECTORY_SEPARATOR : '').$fileName;

            if (in_array($className, get_declared_classes()))
            {
                return;
            }

            require strtolower($file);
            
            $extended = APP_PATH.'core/'.'app_'.$className.EXT;

            if (is_file($extended))
            {
                require strtolower($extended);                
                class_alias('\\Application\\Core\\App_'.$className, $className);
            }
            else
            {
                class_alias($namespace.'\\'.$className, $className);
            }
        }
    }
}