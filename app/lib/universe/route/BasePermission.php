<?php namespace Ecdo\Universe;

use Ecdo\Universe\Cache\JsonCache;
/**
 * 权限树基础类
 * 主要用于生成或获取权限组，权限树
 * 权限组作用用于页面权限显示，选择权限
 * 权限树作用用于权限限制
 * 
 * @package Ecdo\Universe
 * @author no<no>
 * @copyright ©2012 - 2015 no. All rights reserved.
 */
class BasePermission
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
     * 权限组缓存键
     * 
     * @var string
     */
    protected $groupKey = 'cache.permission.group';
    
    /**
     * 权限树缓存键
     * 
     * @var string
     */
    protected $treeKey = 'cache.permission.tree';
    
    /**
     * 文件路径匹配模式
     * 
     * @var string
     */
    protected $pattern = '/permission*.json';
    
    /**
     * 初始构造
     */
    public function __construct()
    {
        $this->fs = app('files');
        $this->cache = new JsonCache();
    }
    
    /**
     * 创建权限树缓存文件
     */
    public function generate()
    {
        $permission = $this->scan();
        if (! empty($permission)) {
            $group = $this->makeGroup($permission);
            $this->storeGroup($group);
            $tree = $this->makeTree($permission['permission']);
            $this->storeTree($tree);
        }
    }
    
    /**
     * 获取路径下所有权限文件中的权限
     * 
     * @return array
     */
    public function scan()
    {
        $path = $this->getPath() . $this->getPattern();
        $files = $this->fs->glob($path);
        $permission = [];
        if (! empty($files)) {
            foreach ($files as $file) {
                $permission[] = $this->getFromFile($file);
            }

            $permission = $this->format($permission);
        }
        
        return $permission;
    }
    
    /**
     * 格式化权限
     * 
     * @param array $data
     * @return array
     */
    protected function format($data)
    {
        $rs = [];
        $group = [];
        $permission = [];
        foreach ($data as $row) {
            if (! empty($row['group'])) {
                foreach ($row['group'] as $key => $val) {
                    $group[$key] = $val;
                }
            }
            
            if (! empty($row['permission'])) {
                foreach ($row['permission'] as $key => $val) {
                    $permission[$key] = $val;
                }
            }
        }
        
        ksort($group);
        ksort($permission);
        $rs = ['group' => $group, 'permission' => $permission];
        
        return $rs;
    }
    
    /**
     * 按组整理权限
     * 
     * @param array $data
     * @return array
     */
    protected function makeGroup($data)
    {
        $rs = [];
        $rs['group'] = $data['group'];
        
        $group = [];
        foreach ($data['permission'] as $key => $val) {
            if (empty($val['gid'])) {
                $val['gid'] = 'other';
            }
            
            $group[$val['gid']][$key] = $val;
        }
        
        if (! empty($group['other'])) {
            $rs['group']['other'] = ['id' => 'other', 'title' => '其他', 'desc' => '其他权限'];
        }
        
        $rs['permission'] = $group;

        return $rs;
    }
    
    /**
     * 创建权限与路径关系
     * 
     * @param array $data
     * @return array
     */
    protected function makeTree($data)
    {
        $rs = [];
        foreach ($data as $key => $val) {
            if (empty($rs[$key])) {
                $rs[$key] = [];
            }
            $rs[$key] = array_merge($rs[$key], $val['path']);
        }
        
        return $rs;
    }
    
    /**
     * 根据文件获取权限
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
     * 将权限组存储到缓存中
     * 
     * @param array $data
     */
    protected function storeGroup($data)
    {
        $this->store($this->getGroupKey(), $data);
    }
    
    /**
     * 将权限树存储到缓存中
     * 
     * @param array $data
     */
    protected function storeTree($data)
    {
        $this->store($this->getTreeKey(), $data);
    }
    
    /**
     * 从缓存中获取权限组
     * 
     * @return array
     */
    public function fetchGroup()
    {
        return $this->fetch($this->getGroupKey());
    }
    
    /**
     * 从缓存中获取权限树
     * 
     * @return array
     */
    public function fetchTree()
    {
        return $this->fetch($this->getTreeKey());
    }
    
    /**
     * 将权限存入缓存
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
     * 获取权限模板
     * 
     * @return string
     */
    public function getTemplate()
    {
        $tmpl = '{
	"group":{
		"{{group_id}}":{
			"id":"{{group_id}}",
			"title":"{{title}}",
			"desc":"{{desc}}"
		}
	},
	"permission":{
		"{{id}}" : {
			"id":"{{id}}",
			"title":"{{title}}",
			"desc":"{{desc}}",
			"gid":"{{group_id}}",
			"path":["{{path}}",...]
		}
	}
}';
        return $tmpl;
    }
}