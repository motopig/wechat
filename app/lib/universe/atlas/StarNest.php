<?php
namespace Ecdo\Universe\Atlas;

use Ecdo\Universe\Cache\JsonCache;

/**
 * @package Ecdo\Angel\Star
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class StarNest
{
    /**
     * @var object
     */
    protected $fs = null;
    
    /**
     * @var object
     */
    protected $cache = null;
    
    /**
     * @var string
     */
    protected $pattern = '/*/*/star.json';
    
    /**
     * @var string
     */
    protected $path = '';
    
    /**
     * @var string
     */
    protected $groupKey = 'cache.star.group';
    
    /**
     * 初始构造
     */
    public function __construct()
    {
        $this->fs = app('files');
        $this->cache = new JsonCache();
        $this->setPath(base_path() . '/atlas');
    }
    
    /**
     * 获取所有应用包信息
     * 
     * @return array
     */
    public function getAllStars()
    {
        $stars = [];
        // $stars = $this->fetchGroup();
        
        // if (empty($stars)) {
        //     $stars = $this->retrieveStars();
        //     $this->storeGroup($stars);
        // }

        // 暂时屏蔽缓存获取app - no
        $stars = $this->retrieveStars();
        $this->storeGroup($stars);
        
        $fs = app('files');
        foreach ((array)$stars as $key => $star) {
            $imgUrl = asset('atlas/' . $star['star'] . '/images/icon.png');
            if ($fs->exists($imgUrl)) {
                $stars[$key]['icon'] = $imgUrl;
            }
        }
        
        return $stars;
    }

    /**
     * 获取所有应用包信息
     *
     * @return array
     */
    public function retrieveStars()
    {
        $files = $this->scan();
        $stars = [];
        if (! empty($files)) {
            foreach ($files as $file) {
                $basePath = $this->getPath();
                $str = str_replace($basePath, '', $file);
                list(, $vendor, $pkg) = explode('/', $str);
                $starName = implode('/', [$vendor, $pkg]);
                $star = $this->getFromFile($file);
                $star['star'] = $starName;
                $star['vendor'] = $vendor;
                $star['package'] = $pkg;
        
                $stars[$starName] = $star;
            }
        }
        
        return $stars;
    }
    
    /**
     * 获取基本应用包
     * 
     * @return array
     */
    public function getBaseStars()
    {
        $stars = [];
        $stars = $this->divide()['base'];
        
        return $stars;
    }
    
    /**
     * 获取可选应用包
     * 
     * @return array
     */
    public function getOptionalStars()
    {
        $stars = [];
        $stars = $this->divide()['opt'];
        
        return $stars;
    }
    
    public function storeGroup($data)
    {
        $this->store($this->groupKey, $data);
    }
    
    public function fetchGroup()
    {
        return $this->fetch($this->groupKey);
    }
    
    /**
     * 将所有应用包进行区分
     * 
     * @return array
     */
    protected function divide()
    {
        $all = $this->getAllStars();
        $base = [];
        $opt = [];
        foreach ((array)$all as $row) {
            if (strtolower($row['type']) === 'base') {
                $base[] = $row;
            } else {
                $opt[] = $row;
            }
        }
        
        return ['base' => $base, 'opt' => $opt];
    }
    
    /**
     * 获取对应应用包的信息
     * 
     * @param string $star
     * @return array
     */
    public function getStarInfo($star)
    {
        return $this->getAllStars()[$star];
    }
    
    /**
     * 根据文件获取应用信息
     * 
     * @param string $file
     * @return array | false
     */
    public function getFromFile($file)
    {
        $rs = $this->fs->get($file);
        if (! empty($rs)) {
            $rs = json_decode($rs, true);
        }
        
        return $rs;
    }
    
    /**
     * 获取路径下所有权限文件
     *
     * @return array
     */
    public function scan()
    {
        $path = $this->getPath() . $this->getPattern();
        $files = $this->fs->glob($path);
        
    
        return $files;
    }
    
    /**
     * 获取应用路径
     * 
     * @return string
     */
    protected function getPath()
    {
        return $this->path;
    }
    
    /**
     * 设置应用文件路径
     * 
     * @param string $path
     */
    protected function setPath($path)
    {
        $this->path = $path;
    }
    
    /**
     * 获取路径匹配模式
     * 
     * @return string
     */
    protected function getPattern()
    {
        return $this->pattern;
    }
    
    /**
     * 存储缓存
     * 
     * @param string $key
     * @param mixed $data
     */
    protected function store($key, $data)
    {
        $this->cache->forever($key, $data);
    }
    
    /**
     * 获取缓存并返回
     * 
     * @param string $key
     * @return mixed
     */
    protected function fetch($key)
    {
        return $this->cache->fetch($key);
    }
    
    /**
     * 获取权限模板
     *
     * @return string
     */
    public function getTemplate()
    {
        $tmpl = '{
	"title":"{{title}}",
	"desc":"{{desc}}",
	"type":"{{base|option}}",
	"conflict":"{{conflict_star, ...}}",
	"depend":"{{depend_star,...}}",
    "company":"{{company}}",
	"author":"{{author}}"
}';
        return $tmpl;
    }
}