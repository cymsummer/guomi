<?php
/**
 * Created by PhpStorm.
 * User: summer
 * Date: 2023-05-29
 * Encourage: 不忘初心，砥砺前行
 * Content: 日志类
 */

namespace Utility;

class Logger
{
    /**
     * @description 记录日志
     * @param string $desc
     * @param array $data
     * @param bool $isEncode
     * */
    public function writeLog($desc, $data, $isEncode = true)
    {
        //设置目录时间
        $time = date('Y-m-d');
        //设置路径目录信息
        $path = './' . $time . '.log';
        //取出目录路径中目录(不包括后面的文件)
        $dir_name = dirname($path);
        //如果目录不存在就创建
        if (!file_exists($dir_name)) {
            //iconv防止中文乱码
            mkdir(iconv('UTF-8', 'GBK', $dir_name), 0777, true);
        }
        if ($isEncode == true) {
            $content = json_encode($data);
        } else {
            $content = $data;
        }
        $now_time = time();
        $now_date = date('Y-m-d H:i:s', $now_time);
        $content = "\n" . $now_date . "\n" . $desc . "\n" . $content;
        file_put_contents($path, $content, FILE_APPEND);
    }

}