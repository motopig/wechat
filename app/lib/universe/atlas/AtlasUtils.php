<?php namespace Ecdo\Atlas;

use Illuminate\Filesystem\Filesystem;

class AtlasUtils
{
    /**
     * 获取atlas目录
     * 
     * @return string
     */
    public static function getAtlasPath()
    {
        return base_path() . '/atlas';
    }
    
    /**
     * Load the atlas vendor auto-load files.
     *
     * @return void
     */
    public static function atlasAutoload()
    {
        $star = '*/*';
        static::autoload($star);
    }
    
    /**
     * 加载atlas中的autoload
     * 
     * @param array $stars
     */
    public static function starsAutoload($stars)
    {
        foreach ((array) $stars as $star) {
            static::autoload($star);
        }
    }
    
    /**
     * 根据star进行加载autoload
     * 
     * @param string $star
     */
    protected static function autoload($star)
    {
        $path = static::getAtlasPath();
        
        if (is_dir($path)) {
            $files = app('files');
            $path .= '/' . $star . '/vendor/autoload.php';
            $autoloads = $files->glob($path);
        
            foreach ((array)$autoloads as $file) {
                $files->requireOnce($file);
            }
        }
    }
    
    /**
     * 批量注册atlas内的provider
     * 
     * @param array $providers
     */
    public static function registerAtlasProviders($providers = [])
    {
        foreach ($providers as $p) {
            if (is_string($p)) {
                static::registerAtlasProvider($p);
            } else {
                throw new \Exception('Provider\'s type must be string!');
            }
        }
    }
    
    /**
     * 注册指定atlas内的provider
     * 
     * @param string $provider
     */
    public static function registerAtlasProvider($provider = '')
    {
        $app = app();
        $app->register($app->getProviderRepository()->createProvider($app, $provider));
    }

    
    /**
     * register atlas config loader.
     *
     * @param object $app
     */
    public static function registerConfigLoader()
    {
        $configLoader = new AtlasConfigLoader(app('files'), app_path() . '/config');
        app('config')->setLoader($configLoader);
    }
    
    /**
     * 查找atlas中的provider并返回
     * 
     * @return array
     */
    public static function scanAtlasProviders()
    {
        $star = '*/*';
        $providers = [];
        $providers = array_merge($providers, self::scan($star));
        
        return $providers;
    }
    
    /**
     * 批量查找provider
     * 
     * @param array $stars
     * @return array
     */
    public static function scanStarsProviders($stars)
    {
        $basePath = static::getAtlasPath();
        $stars_array = array();
        $fs = app('files');
        if( $fs->isDirectory($basePath) ){

            $starSign = $fs->directories($basePath);
            foreach($starSign as $k => $v){
                $childStarDir = $fs->directories($v);
                foreach($childStarDir as $kk => $vv){
                    $path = $vv . '/star.php';
                    include $path;
                }
            }
        }

//        $stars = 1;
        $providers = [];
        foreach ((array) $stars as $star) {
            $providers = array_merge($providers, self::scan($star));
        }
        
        return $providers;
    }
    
    /**
     * 查找指定star内的provider
     * 
     * @param string $star
     * @return array
     */
    protected static function scan($star)
    {
        $basePath = static::getAtlasPath();
        
        $providers = [];
        if (is_dir($basePath)) {
            $fs = app('files');
            $path = $basePath . '/' . $star . '/src/provider/*ServiceProvider.php';
            $files = $fs->glob($path);
            
            list($vendor, $pkg) = array_map('studly_case', explode('/', $star));
            foreach ((array) $files as $file) {
                $file = str_replace($basePath . '/', '', $file);
                $sub = str_replace('/src/provider', '', substr($file, 0, -4));
                list($vendor, $pkg, $cls) = explode('/', $sub);
                $vendor = studly_case($vendor);
                $pkg = studly_case($pkg);
                $cls = $vendor . '\\' . $pkg . '\\' . $cls;
                if (class_exists($cls)) {
                    $providers[] = $cls;
                }
            }
        }
        
        return $providers;
    }
}
