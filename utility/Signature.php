<?php
/**
 * Created by PhpStorm.
 * User: summer
 * Date: 2023-05-29
 * Encourage: 不忘初心，砥砺前行
 * Content: 签名类
 */

namespace Utility;


class Signature
{
    protected $logger = null;

    public function __construct()
    {
        $this->logger = new Logger();
    }

    /**
     * @description 数据签名
     * @param string $param
     * @param string $publicKey
     * @param string $urlPath
     * @return string
     * */
    public function signData($param, $publicKey, $urlPath)
    {
        try {
            $param_array = json_decode($param, true);
            if (!$param_array) {
                $ret = json_last_error_msg();
                $exception = new \Exception($ret, 500);
                return $exception;
            }
            $new_array = [];
            foreach ($param_array as $key => $value) {
                if (!empty($value)) {
                    if (is_array($value)) {
                        $new_str = '[';
                        $check = $this->checkArray($value);
                        if ($check) {
                            array_walk($value, function ($v) use (&$new_str) {
                                $new_str .= $this->handleArray($v);
                            });
                        } else {
                            $new_str .= $this->handleArray($value);
                        }
                        $new_str .= ']';
                        $value = $new_str;
                    }
                    $new_array [$key] = $value;
                }
            }
            $str = $this->arrayToStr($new_array);
            $data = $this->sencryptData($str, $urlPath, $publicKey);
            return $data;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * @description 加密数据
     * @param string $str
     * @param string $path
     * @param string $publicKey
     * @return string
     * */
    public function sencryptData($str, $path, $publicKey)
    {
        $value = $str . $path;
        $this->logger->writeLog('md5加密前的原始数据', ['data' => $value]);
        $md5 = md5($value) . $publicKey;
        // 日志记录
        $this->logger->writeLog('md5加密后的数据结果', ['data' => $md5]);
        $sha1 = sha1($md5);
        // 日志记录
        $this->logger->writeLog('sha1加密后的数据结果', ['data' => $sha1]);
        return $sha1;
    }

    /**
     * @description 检查是是否是多维数组
     * @param array $items
     * @return bool
     * */
    public function checkArray($items)
    {
        foreach ($items as $value) {
            if (is_array($value)) {
                return true;
            }
        }
        return false;
    }


    /**
     * @description 处理数组
     * @param array $items
     * @return string
     * */
    public function handleArray($items)
    {
        $data = [];
        foreach ($items as $key => $value) {
//            if ($value == '') {
//                $itemsData[$key] = $value;
//                continue;
//            }
//            if (!isset($value)) {
//                continue;
//            }
//            if (empty($value)) {
//                continue;
//            }
            if (!empty($value)) {
                $data[$key] = $value;
            }
        }
        return $this->arrayToStr($data);
    }


    /**
     * @description 将数组转为字符串
     * @param array $data
     * @return string
     * */
    public function arrayToStr($data)
    {
        ksort($data);
        // 日志记录
        $this->logger->writeLog('进行ksort排序的结果', ['data' => $data]);
        $str = '';
        array_walk($data, function ($value, $key) use (&$str) {
            $str .= $key . '=' . $value;
        });
        // 日志记录
        $this->logger->writeLog('ksort后的结果生成的字符串', ['data' => $str]);
        return $str;
    }


}