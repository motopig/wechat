<?php namespace Ecdo\Atlas;

use Illuminate\Config\FileLoader;

/**
 * Atlas配置文件路径设置类
 * 
 * @package Ecdo\Atlas
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class AtlasConfigLoader extends FileLoader
{
    /**
     * Apply any cascades to an array of package options.
     *
     * @param  string  $env
     * @param  string  $package
     * @param  string  $group
     * @param  array   $items
     * @return array
     */
    public function cascadePackage($env, $package, $group, $items)
    {
        // First we will look for a configuration file in the atlas configuration
        // folder. If it exists, we will load it and merge it with these original
        // options so that we will easily "cascade" a package's configurations.
        $file = "atlas/{$package}/{$group}.php";
    
        if ($this->files->exists($path = $this->defaultPath.'/'.$file))
        {
            $items = array_merge(
                $items, $this->getRequire($path)
            );
        }
    
        // Once we have merged the regular package configuration we need to look for
        // an environment specific configuration file. If one exists, we will get
        // the contents and merge them on top of this array of options we have.
        $path = $this->getPackagePath($env, $package, $group);
    
        if ($this->files->exists($path))
        {
            $items = array_merge(
                $items, $this->getRequire($path)
            );
        }
    
        return $items;
    }
    
    /**
     * Get the package path for an environment and group.
     *
     * @param  string  $env
     * @param  string  $package
     * @param  string  $group
     * @return string
     */
    protected function getPackagePath($env, $package, $group)
    {
        $file = "atlas/{$package}/{$env}/{$group}.php";
    
        return $this->defaultPath.'/'.$file;
    }
}