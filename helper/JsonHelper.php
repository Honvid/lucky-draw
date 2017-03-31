<?php

/**
* Json文件处理类
*/
class JsonHelper
{

    protected static $baseDir = './data/';

    /**
     * 把数据写入文件
     * @param  array  $data     [description]
     * @param  string $fileName [description]
     * @return [type]           [description]
     */
    public static function write($data, $fileName)
    {
        // 把PHP数组转成JSON字符串
        $json_string = json_encode($data);
        // 写入文件
        file_put_contents(self::$baseDir . $fileName . '.json', $json_string);
    }

    public static function writePhone($data, $fileName)
    {
        file_put_contents(self::$baseDir . $fileName . '.json', $data);
    }

    /**
     * 从文件中读取数据
     * @param  string $fileName [description]
     * @return [type]           [description]
     */
    public static function read($fileName)
    {
        // 从文件中读取数据到PHP变量
        $json_string = file_get_contents(self::$baseDir . $fileName . '.json');
        // 把JSON字符串转成PHP数组
        return json_decode($json_string, true);
    }

    public static function readPhone($fileName)
    {
        return file_get_contents(self::$baseDir . $fileName . '.json');
    }

    /**
     * 保存属性
     * @param  int    $key      [description]
     * @param  string $name     [description]
     * @param  string $fileName [description]
     * @return [type]           [description]
     */
    public static function save($key, $name, $fileName)
    {
        $data = self::read($fileName);
        $data[$key] = $name;
        self::write($data, $fileName);
    }

    /**
     * 保存属性
     * @param $key
     * @param $title
     * @param $num
     * @param $fileName
     */
    public static function saveBase($key, $title, $num, $fileName)
    {
        $data = self::read($fileName);
        $data[$key] = [
            'title' => $title,
            'number' => $num
        ];
        self::write($data, $fileName);
    }
}