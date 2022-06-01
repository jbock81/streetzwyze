<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PackageScanner
 *
 * This class loads all the classes in  a specified baseDir
 *
 * @author intelWorX
 */
class PackageScanner extends IdeoObject
{

    private $baseDir;
    private $baseNamespace;
    private $parentClass;
    private $extenstion;
    private $recursive;

    /**
     *
     * @var ReflectionClass[]
     */
    private $allClassNames = array();

    /**
     *
     * @param string $baseDir path to base directory
     * @param string $baseNamespace namespace of classes in base directory defaults
     * to root namespace
     * @param string $parentClass class name of parent class defaults to null
     * @param bool $recursive
     * @param string $extenstion defaults to php
     */
    public function __construct($baseDir, $baseNamespace = '\\', $parentClass = null, $recursive = false, $extenstion = 'php')
    {
        $this->setBaseDir($baseDir);
        $this->setParentClass($parentClass);
        $this->setBaseNamespace($baseNamespace);
        $this->extenstion = $extenstion;
        $this->recursive = $recursive;
        $this->loadAll();
    }

    public function reload()
    {
        $this->allClassNames = [];
        $this->loadAll();
        return $this;
    }

    private function loadAll()
    {
        if ($this->recursive) {
            $recurser = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->baseDir), RecursiveIteratorIterator::SELF_FIRST);
        } else {
            $recurser = new DirectoryIterator($this->baseDir);
        }

        //$fileClassMap = array();
        $pathToNs = array();
        foreach ($recurser as $fileFound) {
            if ($fileFound->isFile() && $fileFound->isReadable() && (!$this->extenstion || GeneralUtils::endsWith($fileFound->getFilename(), $this->extenstion))) {
                $directory = $fileFound->getPath();

                if (!array_key_exists($directory, $pathToNs)) {
                    $pathToNs[$directory] = $this->_pathNamespace($directory);
                }

                $namespace = $pathToNs[$directory];

                $className = $namespace . '\\' . $fileFound->getBasename($this->extenstion ? '.' . $this->extenstion : null);

                include_once $fileFound->getRealPath();
                //register class
                $this->addClass($fileFound->getRealPath(), $className);
            }
        }
    }

    protected function addClass($filePath, $className)
    {
        if (class_exists($className) || interface_exists($className) || trait_exists($className)) {
            $classReflection = new ReflectionClass($className);
            if (!$this->parentClass || $classReflection->isSubclassOf($this->parentClass)) {
                $this->allClassNames[$filePath] = $classReflection;
            }
        }

        return false;
    }

    private function _pathNamespace($path)
    {
        $path = realpath($path);
        if (strstr($path, $this->baseDir) !== false) {
            return $this->baseNamespace . str_replace(array($this->baseDir, '/'), array('', '\\'), $path);
        }
        return null;
    }

    public function getBaseDir()
    {
        return $this->baseDir;
    }

    public function getBaseNamespace()
    {
        return $this->baseNamespace;
    }

    public function getParentClass()
    {
        return $this->parentClass;
    }

    public function getExtenstion()
    {
        return $this->extenstion;
    }

    public function isRecursive()
    {
        return $this->recursive;
    }

    final public function setBaseDir($baseDir)
    {
        if (!file_exists($baseDir) || !is_dir($baseDir) || !is_readable($baseDir)) {
            throw new PackageScannerException("Base Directory ({$baseDir}) must be a readable directory with valid path.");
        }

        $this->baseDir = realpath($baseDir);
        return $this;
    }

    final public function setBaseNamespace($baseNamespace)
    {
        $this->baseNamespace = $baseNamespace === '\\' ? $baseNamespace : rtrim($baseNamespace, '\\');
        return $this;
    }

    final public function setParentClass($parentClass = null)
    {
        if ($parentClass !== null && !class_exists($parentClass) && !interface_exists($parentClass)) {
            throw new PackageScannerException("The specified parent class: {$parentClass} was not found.");
        }

        $this->parentClass = $parentClass;
        return $this;
    }

    public function setExtenstion($extenstion)
    {
        $this->extenstion = $extenstion;
        return $this;
    }

    public function setRecursive($recursive)
    {
        $this->recursive = $recursive;
        return $this;
    }

    /**
     * @param bool|null $instantiable if true, returns all instantiable,
     * if false, returns non-instantiable, null returns all classes
     * @return ReflectionClass[]
     */
    public function getAll($instantiable = null)
    {
        if ($instantiable === null) {
            return $this->allClassNames;
        }

        $classList = $this->getAllInstantiable();
        if ($instantiable) {
            return $classList;
        } else {
            return array_diff_assoc($this->allClassNames, $classList);
        }
    }

    /**
     * @return ReflectionClass[] Description
     */
    public function getAllInstantiable()
    {
        return $this->_filter_all('isInstantiable');
    }

    /**
     * @return ReflectionClass[] Description
     */
    public function getAllInNamespace()
    {
        return $this->_filter_all('inNamespace');
    }

    /**
     * @return ReflectionClass[] Description
     */
    public function getAllIterable()
    {
        return $this->_filter_all('isIterateable');
    }

    /**
     * @return ReflectionClass[] Description
     */
    public function getAllSubclassOf($className, $instantiable = null)
    {
        return $this->_filter_all('isSubclassOf', [$className], $this->getAll($instantiable));
    }

    /**
     * @return ReflectionClass[] Description
     */
    public function getAllImplementing($interfaceName, $instantiable = null)
    {
        return $this->_filter_all('implementsInterface', [$interfaceName], $this->getAll($instantiable));
    }

    /**
     * @return ReflectionClass[] Description
     */
    public function getAllTraits()
    {
        return $this->_filter_all('isTrait');
    }

    /**
     * @return ReflectionClass[] Description
     */
    public function getAllInterfaces()
    {
        return $this->_filter_all('isInterface');
    }

    /**
     * @return ReflectionClass[] Description
     */
    private function _filter_all($method, $params = array(), $classList = null)
    {
        return array_filter($classList === null ? $this->allClassNames : $classList, function ($className) use ($method, $params) {
            if (method_exists($className, $method)) {
                return call_user_func_array(array($className, $method), $params);
            }
            return false;
        });
    }

}
