<?php namespace Ecdo\Universe;

use Ecdo\Universe\Cache\JsonCache;

/**
 * 菜单树基础类
 * 主要用于生成或获取菜单组，菜单树
 * 菜单组作用用于页面菜单显示
 * 菜单树作用用于菜单限制
 *
 * @package Ecdo\Universe
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class BaseMenu
{
    
    /**
     * 文件路径
     * 
     * @var string
     */
    protected $path = '';
    
    /**
     * 文件存储对象
     * 
     * @var object
     */
    protected $fs = null;

    /**
     * 缓存方式
     *
     * @var object
     */
    protected $cache = null;
    
    /**
     * 菜单组缓存键
     * 
     * @var string
     */
    protected $groupKey = 'cache.menu.group';
    
    /**
     * 菜单树缓存键
     * 
     * @var string
     */
    protected $treeKey = 'cache.menu.tree';
    
    /**
     * 文件路径匹配模式
     * 
     * @var string
     */
    protected $pattern = '/menu*.json';
    
    /**
     * 初始构造
     */
    public function __construct()
    {
        $this->fs = app('files');
        $this->cache = new JsonCache();
    }
    
    /**
     * 生成菜单
     */
    public function generate()
    {
        $data = $this->scan();
        $group = $this->makeGroup($data['menu']);
        $this->storeGroup($group);
        $tree = $this->makeTree($data['menu']);
        $this->storeTree($tree);
    }
    
    /**
     * 获取路径下所有菜单文件中的菜单
     *
     * @return array
     */
    public function scan()
    {
        $path = $this->getPath() . $this->getPattern();
        $files = $this->fs->glob($path);
        $data = [];
        if (! empty($files)) {
            foreach ($files as $file) {
                $data[] = $this->getFromFile($file);
            }
        }
    
        return $data;
    }
    
    /**
     * 整理菜单结构，返回菜单组与菜单树
     * 
     * @param array $data
     * @return array
     */
    protected function makeGroup($data)
    {
        $group = [];
        $menu = [];
        
        /*
         * 过滤菜单组与菜单树
         */
        $i = 100;
        foreach ($data as $key => $row) {
            $seq = empty($row['sequence']) ? ++ $i : $row['sequence'];
            if (empty($row['pid'])) {
                if (! empty($group[$seq][$key])) {
                    $row = $this->priorityMenu($group[$seq][$key], $row);
                }
                
                $group[$seq][$key] = $row;
            } else {
                if (! empty($menu[$row['pid']][$seq][$key])) {
                    $row = $this->priorityMenu($menu[$row['pid']][$seq][$key], $row);
                }
                
                $menu[$row['pid']][$seq][$key] = $row;
            }
        }
        
        /*
         * 菜单组进行排序
         */
        $group = array_map(function($row) {
            ksort($row);
            return $row;
        }, $group);
        ksort($group);
        
        /*
         * 简化菜单组数组
         */
        $rsGroup = [];
        foreach ($group as $row) {
            $rsGroup = array_merge($rsGroup, $row);
        }
        
        /*
         * 菜单树进行排序
         */
        foreach ($menu as &$sub) {
            ksort($sub);
        }
        unset($sub);
        $sub = null;
        
        /*
         * 简化菜单树数组
         */
        $rsMenu = [];
        foreach ($menu as $pid => $sub) {
            if (empty($rsMenu[$pid])) {
                $rsMenu[$pid] = [];
            }
            
            foreach ($sub as $row) {
                $rsMenu[$pid] = array_merge($rsMenu[$pid], $row);
            }
        }
        unset($group, $menu);
        
        return ['group' => $rsGroup, 'menu' => $rsMenu];
    }
    
    /**
     * 整理出菜单树结构
     * 
     * @param array $data
     * @return array
     */
    protected function makeTree($data)
    {
        $tree = [];
        foreach ($data as $menuId => $row) {
            if (! empty($row['perm_id'])) {
                $tree[$row['perm_id']][$menuId] = $menuId;
            }
        }
        
        return $tree;
    }
    
    /**
     * 根据优先级返回优先级小的菜单
     * 
     * @param array $first
     * @param array $second
     * @return array
     */
    protected function priorityMenu($first, $second)
    {
        if ($first['priority'] > $second['priority']) {
            return $second;
        } else {
            return $first;
        }
    }
    
    /**
     * 根据文件获取菜单
     *
     * @param string $file
     * @return array | false
     */
    public function getFromFile($file)
    {
        $rs = $this->fs->get($file);
        if (!empty($rs)) {
            $rs = json_decode($rs, true);
        }
    
        return $rs;
    }
    
    /**
     * 获取group键值
     *
     * @return string
     */
    protected function getGroupKey()
    {
        return $this->groupKey;
    }
    
    /**
     * 获取tree键值
     *
     * @return string
     */
    protected function getTreeKey()
    {
        return $this->treeKey;
    }
    
    /**
     * 将菜单组存储到缓存中
     *
     * @param array $data
     */
    protected function storeGroup($data)
    {
        $this->store($this->getGroupKey(), $data);
    }
    
    /**
     * 将菜单树存储到缓存中
     *
     * @param array $data
     */
    protected function storeTree($data)
    {
        $this->store($this->getTreeKey(), $data);
    }
    
    /**
     * 从缓存中获取菜单组
     *
     * @return array
     */
    public function fetchGroup()
    {
        $data = $this->fetch($this->getGroupKey());

        // 未取到数据则重新生成后取数据
        if (empty($data['group']) || empty($data['menu'])) {
            $this->generate();
            $data = $this->fetch($this->getGroupKey());
        }

        return $data;
    }
    
    /**
     * 从缓存中获取菜单树
     *
     * @return array
     */
    public function fetchTree()
    {
        $data = $this->fetch($this->getTreeKey());
        // 未取到数据则重新生成后取数据
        if (empty($data)) {
            $this->generate();
            $data = $this->fetch($this->getTreeKey());
        }
        
        return $data;
    }
    
    /**
     * 将菜单存入缓存
     * 
     * @param string $key
     * @param array $data
     */
    protected function store($key, $data)
    {
        $this->cache->forever($key, $data);
    }
    
    /**
     * 从缓存获取权限树
     * 
     * @param string $key
     * @return array | false
     */
    protected function fetch($key)
    {
        return $this->cache->fetch($key);
    }
    
    /**
     * 获取文件路径
     * 
     * @return string
     */
    protected function getPath()
    {
        return $this->path;
    }
    
    /**
     * 设置文件路径
     * 
     * @param string $path
     */
    protected function setPath($path)
    {
        $this->path = $path;
    }
    
    /**
     * 获取文件匹配模式
     * 
     * @return string
     */
    protected function getPattern()
    {
        return $this->pattern;
    }
    
    /**
     * 设置文件匹配模式
     * 
     * @param string $pattern
     */
    protected function setPattern($pattern)
    {
        $this->pattern = $pattern;
    }
    
    /**
     * 获取菜单模板
     * 
     * @return string
     */
    public function getTemplate()
    {
        $tmpl = '{
	"menu":{
		{{id}} : {
			"id":{{id}},
			"title":"{{title}}",
			"url":"{{url}}",
            "icon":"{{icon}}",
			"perm_id":"{{permission_id}}",
			"sequence":100,
			"priority":100,
			"pid":{{parent_id}}
		}
	}
}';
        return $tmpl;
    }
}
