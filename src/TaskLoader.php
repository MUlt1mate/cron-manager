<?php
namespace mult1mate\crontab;

/**
 * Class TaskLoader
 * Loads classes and provides list of available methods
 * @author mult1mate
 * @package mult1mate\crontab
 * Date: 07.02.16
 * Time: 12:53
 */
class TaskLoader
{
    /**
     * Contains array of directories from which TaskLoader will try to load classes
     * @var array
     */
    protected static $class_folders = array();

    /**
     * Looks for and loads required class via require_once
     * @param $class_name
     * @return bool
     * @throws TaskManagerException
     */
    public static function loadController($class_name)
    {
        foreach (self::$class_folders as $f) {
            $f = rtrim($f, '/');
            $filename = $f . '/' . $class_name . '.php';
            if (file_exists($filename)) {
                require_once $filename;
                if (class_exists($class_name)) {
                    return true;
                } else {
                    throw new TaskManagerException('file found but class ' . $class_name . ' not loaded');
                }
            }
        }

        throw new TaskManagerException('class ' . $class_name . ' not found');
    }

    /**
     * Returns all public methods for requested class
     * @param string $class
     * @return array
     * @throws TaskManagerException
     */
    public static function getControllerMethods($class)
    {
        if (!class_exists($class)) {
            throw new TaskManagerException('class ' . $class . ' not found');
        }
        $class_methods = get_class_methods($class);
        if ($parent_class = get_parent_class($class)) {
            $parent_class_methods = get_class_methods($parent_class);
            return array_diff($class_methods, $parent_class_methods);
        }
        return $class_methods;
    }

    /**
     * Returns names of all php files in directories
     * @param array $paths
     * @param $namespaces_list
     * @return array
     * @throws TaskManagerException
     */
    protected static function getControllersList($paths, $namespaces_list)
    {
        $controllers = array();
        foreach ($paths as $p_index => $p) {
            if (!file_exists($p)) {
                throw new TaskManagerException('folder ' . $p . ' does not exist');
            }
            $files = scandir($p);
            foreach ($files as $f) {
                if (preg_match('/^([A-Z]\w+)\.php$/', $f, $match)) {
                    $namespace = isset($namespaces_list[$p_index]) ? $namespaces_list[$p_index] : '';
                    $controllers[] = $namespace . $match[1];
                }
            }
        }
        return $controllers;
    }

    /**
     * Scan folders for classes and return all their public methods
     * @param string|array $folder
     * @param string|array $namespace
     * @return array
     * @throws TaskManagerException
     */
    public static function getAllMethods($folder, $namespace = array())
    {
        self::setClassFolder($folder);
        $namespaces_list = is_array($namespace) ? $namespace : array($namespace);
        $methods = array();

        $controllers = self::getControllersList(self::$class_folders, $namespaces_list);
        foreach ($controllers as $c) {
            if (!class_exists($c)) {
                self::loadController($c);
            }
            $methods[$c] = self::getControllerMethods($c);
        }

        return $methods;
    }

    /**
     * Sets folders which contain needed classes
     * @param $folder
     * @return array
     */
    public static function setClassFolder($folder)
    {
        return self::$class_folders = is_array($folder) ? $folder : array($folder);
    }
}
