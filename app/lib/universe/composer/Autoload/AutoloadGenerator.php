<?php

/*
 * This file is part of Composer.
 *
 * (c) Nils Adermann <naderman@naderman.de>
 * Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Composer\Autoload;

use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;
use Composer\Util\Filesystem;

/**
 *
 * @author Igor Wiedler <igor@wiedler.ch>
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
class AutoloadGenerator
{

    private $config = array();

    public function __construct()
    {
        $this->config = [
            'use-include-path' => false,
            'vendor-dir' => 'vendor',
            'autoloader-suffix' => null,
            'prepend-autoloader' => true,
        ];
    }

    /**
     *
     * @param array $config            
     */
    public function setConfig(array $config = array())
    {
        $this->config = array_merge($this->config, $config);
    }

    /**
     *
     * @param string $key            
     * @return multitype|boolean
     */
    public function getConfig($key)
    {
        if (! empty($this->config[$key])) {
            return $this->config[$key];
        }
        
        return false;
    }

    public function dump($vendor, $package, $isTitan = false, $targetDir = 'composer', $scanPsr0Packages = false, $suffix = '')
    {
        $basePath = base_path() . '/atlas/' . $vendor . '/' . $package;
        $filesystem = new Filesystem();
        $filesystem->ensureDirectoryExists($this->getConfig('vendor-dir'));
        $basePath = $filesystem->normalizePath(realpath($basePath));
        $vendorPath = $filesystem->normalizePath($basePath . '/' . $this->getConfig('vendor-dir'));
        $useGlobalIncludePath = (bool) $this->getConfig('use-include-path');
        $prependAutoloader = $this->getConfig('prepend-autoloader') === false ? 'false' : 'true';
        $targetDir = $vendorPath . '/' . $targetDir;
        $filesystem->ensureDirectoryExists($targetDir);
        
        $vendorPathCode = $filesystem->findShortestPathCode(realpath($targetDir), $vendorPath, true);
        $vendorPathCode52 = str_replace('__DIR__', 'dirname(__FILE__)', $vendorPathCode);
        $vendorPathToTargetDirCode = $filesystem->findShortestPathCode($vendorPath, realpath($targetDir), true);
        
        $appBaseDirCode = $filesystem->findShortestPathCode($vendorPath, $basePath, true);
        $appBaseDirCode = str_replace('__DIR__', '$vendorDir', $appBaseDirCode);
        
        $namespacesFile = <<<EOF
<?php

// autoload_namespaces.php @generated by Composer

\$vendorDir = $vendorPathCode52;
\$baseDir = $appBaseDirCode;

return array(

EOF;
        
        $psr4File = <<<EOF
<?php

// autoload_psr4.php @generated by Composer

\$vendorDir = $vendorPathCode52;
\$baseDir = $appBaseDirCode;

return array(

EOF;
        
        // Collect information from all packages.
        $namespace = studly_case($vendor) . '\\' . studly_case($package) . '\\';
        $autoloads['psr-0'] = [$namespace => [$basePath . '/src/']];
        $autoloads['psr-4'] = [];//[$namespace => [$basePath . '/src/migrations', $basePath . '/src/controllers', $basePath . '/src/lib', $basePath . '/src/models', $basePath . '/src/migrations']];
        $autoloads['classmap'] = [$basePath . '/src/migrations', $basePath . '/src/controllers', $basePath . '/src/lib', $basePath . '/src/models', $basePath . '/src/migrations', $basePath . '/src/provider'];
        
        // Process the 'psr-0' base directories.
        foreach ($autoloads['psr-0'] as $namespace => $paths) {
            $exportedPaths = array();
            foreach ($paths as $path) {
                $exportedPaths[] = $this->getPathCode($filesystem, $basePath, $vendorPath, $path);
            }
            $exportedPrefix = var_export($namespace, true);
            $namespacesFile .= "    $exportedPrefix => ";
            $namespacesFile .= "array(" . implode(', ', $exportedPaths) . "),\n";
        }
        $namespacesFile .= ");\n";
        
        // Process the 'psr-4' base directories.
        foreach ($autoloads['psr-4'] as $namespace => $paths) {
            $exportedPaths = array();
            foreach ($paths as $path) {
                $exportedPaths[] = $this->getPathCode($filesystem, $basePath, $vendorPath, $path);
            }
            $exportedPrefix = var_export($namespace, true);
            $psr4File .= "    $exportedPrefix => ";
            $psr4File .= "array(" . implode(', ', $exportedPaths) . "),\n";
        }
        $psr4File .= ");\n";
        $classmapFile = <<<EOF
<?php

// autoload_classmap.php @generated by Composer

\$vendorDir = $vendorPathCode52;
\$baseDir = $appBaseDirCode;

return array(

EOF;
        
        // add custom psr-0 autoloading if the root package has a target dir
        $targetDirLoader = null;
        
        // flatten array
        $classMap = array();
        if ($scanPsr0Packages) {
            // Scan the PSR-0/4 directories for class files, and add them to the class map
            foreach (array(
                'psr-0',
                'psr-4'
            ) as $psrType) {
                foreach ($autoloads[$psrType] as $namespace => $paths) {
                    foreach ($paths as $dir) {
                        $dir = $filesystem->normalizePath($filesystem->isAbsolutePath($dir) ? $dir : $basePath . '/' . $dir);
                        if (! is_dir($dir)) {
                            continue;
                        }
                        $whitelist = sprintf('{%s/%s.+(?<!(?<!/)Test\.php)$}', preg_quote($dir), ($psrType === 'psr-0' && strpos($namespace, '_') === false) ? preg_quote(strtr($namespace, '\\', '/')) : '');
                        
                        $namespaceFilter = $namespace === '' ? null : $namespace;
                        foreach (ClassMapGenerator::createMap($dir, $whitelist, $namespaceFilter) as $class => $path) {
                            if (! isset($classMap[$class])) {
                                $path = $this->getPathCode($filesystem, $basePath, $vendorPath, $path);
                                $classMap[$class] = $path . ",\n";
                            }
                        }
                    }
                }
            }
        }
        
        foreach ($autoloads['classmap'] as $dir) {
            foreach (ClassMapGenerator::createMap($dir, null) as $class => $path) {
                $path = $this->getPathCode($filesystem, $basePath, $vendorPath, $path);
                $classMap[$class] = $path . ",\n";
            }
        }
        
        ksort($classMap);
        foreach ($classMap as $class => $code) {
            $classmapFile .= '    ' . var_export($class, true) . ' => ' . $code;
        }
        $classmapFile .= ");\n";
        
        if (! $suffix) {
            $suffix = $this->getConfig('autoloader-suffix') ?  : md5(uniqid('', true));
        }
        
        file_put_contents($targetDir . '/autoload_namespaces.php', $namespacesFile);
        file_put_contents($targetDir . '/autoload_psr4.php', $psr4File);
        file_put_contents($targetDir . '/autoload_classmap.php', $classmapFile);
        file_put_contents($vendorPath . '/autoload.php', $this->getAutoloadFile($vendorPathToTargetDirCode, $suffix));
        file_put_contents($targetDir . '/autoload_real.php', $this->getAutoloadRealFile(true, false, $targetDirLoader, false, $vendorPathCode, $appBaseDirCode, $suffix, $useGlobalIncludePath, $prependAutoloader));
        
        // use stream_copy_to_stream instead of copy
        // to work around https://bugs.php.net/bug.php?id=64634
        $sourceLoader = fopen(__DIR__ . '/ClassLoader.php', 'r');
        $targetLoader = fopen($targetDir . '/ClassLoader.php', 'w+');
        stream_copy_to_stream($sourceLoader, $targetLoader);
        fclose($sourceLoader);
        fclose($targetLoader);
        unset($sourceLoader, $targetLoader);
    }

    /**
     * Registers an autoloader based on an autoload map returned by parseAutoloads
     *
     * @param array $autoloads
     *            see parseAutoloads return value
     * @return ClassLoader
     */
    public function createLoader(array $autoloads)
    {
        $loader = new ClassLoader();
        
        if (isset($autoloads['psr-0'])) {
            foreach ($autoloads['psr-0'] as $namespace => $path) {
                $loader->add($namespace, $path);
            }
        }
        
        if (isset($autoloads['psr-4'])) {
            foreach ($autoloads['psr-4'] as $namespace => $path) {
                $loader->addPsr4($namespace, $path);
            }
        }
        
        return $loader;
    }

    protected function getPathCode(Filesystem $filesystem, $basePath, $vendorPath, $path)
    {
        if (! $filesystem->isAbsolutePath($path)) {
            $path = $basePath . '/' . $path;
        }
        $path = $filesystem->normalizePath($path);
        
        $baseDir = '';
        if (strpos($path . '/', $vendorPath . '/') === 0) {
            $path = substr($path, strlen($vendorPath));
            $baseDir = '$vendorDir';
            
            if ($path !== false) {
                $baseDir .= " . ";
            }
        } else {
            $path = $filesystem->normalizePath($filesystem->findShortestPath($basePath, $path, true));
            if (! $filesystem->isAbsolutePath($path)) {
                $baseDir = '$baseDir . ';
                $path = '/' . $path;
            }
        }
        
        if (preg_match('/\.phar$/', $path)) {
            $baseDir = "'phar://' . " . $baseDir;
        }
        
        return $baseDir . (($path !== false) ? var_export($path, true) : "");
    }

    protected function getAutoloadFile($vendorPathToTargetDirCode, $suffix)
    {
        return <<<AUTOLOAD
<?php

// autoload.php @generated by Composer

require_once $vendorPathToTargetDirCode . '/autoload_real.php';

return ComposerAutoloaderInit$suffix::getLoader();

AUTOLOAD;
    }

    protected function getAutoloadRealFile($useClassMap, $useIncludePath, $targetDirLoader, $useIncludeFiles, $vendorPathCode, $appBaseDirCode, $suffix, $useGlobalIncludePath, $prependAutoloader)
    {
        // TODO the class ComposerAutoloaderInit should be revert to a closure
        // when APC has been fixed:
        // - https://github.com/composer/composer/issues/959
        // - https://bugs.php.net/bug.php?id=52144
        // - https://bugs.php.net/bug.php?id=61576
        // - https://bugs.php.net/bug.php?id=59298
        $file = <<<HEADER
<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit$suffix
{
    private static \$loader;

    public static function loadClassLoader(\$class)
    {
        if ('Composer\\Autoload\\ClassLoader' === \$class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    public static function getLoader()
    {
        if (null !== self::\$loader) {
            return self::\$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInit$suffix', 'loadClassLoader'), true, $prependAutoloader);
        self::\$loader = \$loader = new \\Composer\\Autoload\\ClassLoader();
        spl_autoload_unregister(array('ComposerAutoloaderInit$suffix', 'loadClassLoader'));


HEADER;
        
        if ($useIncludePath) {
            $file .= <<<'INCLUDE_PATH'
        $includePaths = require __DIR__ . '/include_paths.php';
        array_push($includePaths, get_include_path());
        set_include_path(join(PATH_SEPARATOR, $includePaths));


INCLUDE_PATH;
        }
        
        $file .= <<<'PSR0'
        $map = require __DIR__ . '/autoload_namespaces.php';
        foreach ($map as $namespace => $path) {
            $loader->set($namespace, $path);
        }


PSR0;
        
        $file .= <<<'PSR4'
        $map = require __DIR__ . '/autoload_psr4.php';
        foreach ($map as $namespace => $path) {
            $loader->setPsr4($namespace, $path);
        }


PSR4;
        
        if ($useClassMap) {
            $file .= <<<'CLASSMAP'
        $classMap = require __DIR__ . '/autoload_classmap.php';
        if ($classMap) {
            $loader->addClassMap($classMap);
        }


CLASSMAP;
        }
        
        if ($useGlobalIncludePath) {
            $file .= <<<'INCLUDEPATH'
        $loader->setUseIncludePath(true);

INCLUDEPATH;
        }
        
        if ($targetDirLoader) {
            $file .= <<<REGISTER_AUTOLOAD
        spl_autoload_register(array('ComposerAutoloaderInit$suffix', 'autoload'), true, true);


REGISTER_AUTOLOAD;
        }
        
        $file .= <<<REGISTER_LOADER
        \$loader->register($prependAutoloader);


REGISTER_LOADER;
        
        if ($useIncludeFiles) {
            $file .= <<<INCLUDE_FILES
        \$includeFiles = require __DIR__ . '/autoload_files.php';
        foreach (\$includeFiles as \$file) {
            composerRequire$suffix(\$file);
        }


INCLUDE_FILES;
        }
        
        $file .= <<<METHOD_FOOTER
        return \$loader;
    }

METHOD_FOOTER;
        
        $file .= $targetDirLoader;
        
        return $file . <<<FOOTER
}

function composerRequire$suffix(\$file)
{
    require \$file;
}

FOOTER;
    }

}