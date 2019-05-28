<?php
namespace WorkerMan\Lib;

class Store
{
    // 为了避免频繁读取磁盘，增加了缓存机制
    protected static $dataCache = array();
    // 上次缓存时间
    protected static $lastCacheTime = [];
    // 保存数据的文件相对与WORKERMAN_LOG_DIR目录目录
    protected static $dataFile = 'data.php';
    // 打开文件的句柄
    protected static $dataFileHandle = null;
    // 缓存过期时间
    const CACHE_EXP_TIME = 1;
    // 定义不同文件
    protected static $dataFileName = 'data';

    public static function config($data_file)
    {
        self::$dataFile = $data_file;
        self::$dataFileName = pathinfo($data_file, PATHINFO_FILENAME);
        if(!isset(self::$lastCacheTime[self::$dataFileName])) self::$lastCacheTime[self::$dataFileName]=0;
    }
    public static function set($key, $value, $ttl = 0)
    {
        self::readDataFromDisk();
        self::$dataCache[self::$dataFileName][$key] = $value;
        return self::writeToDisk();
    }
    public static function get($key, $use_cache = true)
    {
        if(time() - self::$lastCacheTime[self::$dataFileName] > self::CACHE_EXP_TIME)
        {
            self::readDataFromDisk();
        }
        return isset(self::$dataCache[self::$dataFileName][$key]) ? self::$dataCache[self::$dataFileName][$key] : null;
    }

    public static function getAll()
    {
        if(time() - self::$lastCacheTime[self::$dataFileName] > self::CACHE_EXP_TIME)
        {
            self::readDataFromDisk();
        }
        return isset(self::$dataCache[self::$dataFileName]) ? self::$dataCache[self::$dataFileName] : null;
    }

    public static function delete($key)
    {
        self::readDataFromDisk();
        unset(self::$dataCache[self::$dataFileName][$key]);
        return self::writeToDisk();
    }

    protected static function writeToDisk()
    {
        if(!self::$dataFileHandle)
        {
            if(!is_file(self::$dataFile))
            {
                touch(self::$dataFile);
            }
            self::$dataFileHandle = fopen(self::$dataFile, 'r+');
            if(!self::$dataFileHandle)
            {
                return false;
            }
        }
        flock(self::$dataFileHandle, LOCK_EX);
        $ret = file_put_contents(self::$dataFile, "<?php \n return " . var_export(self::$dataCache[self::$dataFileName], true). ';');
        flock(self::$dataFileHandle, LOCK_UN);
        return $ret;
    }

    protected static function readDataFromDisk()
    {
        if(!self::$dataFileHandle)
        {
            if(!is_file(self::$dataFile))
            {
                touch(self::$dataFile);
            }
            self::$dataFileHandle = fopen(self::$dataFile, 'r+');
            if(!self::$dataFileHandle)
            {
                return false;
            }
        }
        flock(self::$dataFileHandle, LOCK_EX);
        $cache = include self::$dataFile;
        flock(self::$dataFileHandle, LOCK_UN);
        if(is_array($cache))
        {
            self::$dataCache[self::$dataFileName] = $cache;
        }
        self::$lastCacheTime[self::$dataFileName] = time();
    }
}