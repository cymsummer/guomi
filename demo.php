<?php
/**
 * Created by PhpStorm.
 * User: summer
 * Date: 2023-05-29
 * Encourage: 不忘初心，砥砺前行
 * Content: 使用示例
 */
require_once 'vendor/autoload.php';

use summer\guomi\Base64Sm2;
use summer\guomi\Signature;

$base64Sm2 = new Base64Sm2();

// 原始字符串
$document = '123456';
var_dump('原始:' . $document);


// 生成密钥对
$generateKey = $base64Sm2->generateKey();
var_dump('私钥为: ' . $generateKey[0]);
var_dump('公钥为: ' . $generateKey[1]);

// 私钥加签
$sign = $base64Sm2->doSign($document, bin2hex(base64_decode($generateKey[0])));
var_dump('私钥加签结果为: ' . $sign);

// 公钥验签
$verify = $base64Sm2->verifySign($document, $sign, bin2hex(base64_decode($generateKey[1])));
var_dump('公钥验签结果为: ' . $verify);

// 加密示例
$publicKey = 'BBP1gMIMFTfUcaqwg8IJRFo6XzHFJVYO7PIWGA5pcu3VCEA3TqmnvL+g/vlPtVjvBJY/SfWL9ZHCA1jeAQrEd8o='; // 公钥
$m2EncryptData = $base64Sm2->sm2Encrypt($document, $publicKey);
var_dump('加密后: ' . $m2EncryptData);

// 解密示例
$privateKey = 'Ds0kAq+4OxuZKKXM3XQSX4VrO2yNpoWz4fZuPwcI720='; // 私钥
$m2DecryptData = $base64Sm2->sm2Decrypt($m2EncryptData, $privateKey);
var_dump('解密后:' . $m2DecryptData);


// 加签的示例
$signature = new Signature();

// 请求参数
$param = '{"crowsourceId":"","bankCardNo":"6222620910008201869","idCardNo":"220203199510271516","platformType":"4","realName":"zhangsan","mobilePhone":"13888888888"}';
// publicKey
$publicKey = 'BO6NBihKkEeojnLjyoL7fWUrlpSBvWWBrcvjpE/OlvFDZYeeNI7zWjrwxMn4KuaLyhEM1moZxwq1LtrQpZizes4=';
// 请求地址
$urlPath = '/api/contract/v1/applySign';

$signData = $signature->signData($param, $publicKey, $urlPath);
var_dump('加签后:' . $signData);

