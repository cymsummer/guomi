<?php

require_once 'vendor/autoload.php';


/**
 * Created by PhpStorm.
 * User: summer, Bowen Tian
 * Date: 2023-09-30 (modified), 2023-05-29 (original)
 * Encourage: 不忘初心，砥砺前行
 * Content: 使用示例
 */

use Utility\Base64Sm2;
use Utility\Signature;

$base64Sm2 = new Base64Sm2();

// 原始字符串
$document = '123456';
var_dump('原始:' . $document);


// 生成密钥对
$generateKey = $base64Sm2->generateKey();
//print_r($generateKey);

$publicKey = $generateKey[1]; // 公钥
$privateKey = $generateKey[0]; // 私钥
$publicKey = "BG55C2O6m3M1T1qCS21h1LiQdHjjUABjAPi1F8i/f0i5dNWwycPIPtDfR8mHwx6AINxU1Utx9lODbZJF+67DLzg=";
$privateKey = "2vVpupPCyBItW96mSD0TXQgQP78P07DUIsUCwOORwYE=";


var_dump('SM2私钥为: ' . $publicKey);
var_dump('SM2公钥为: ' . $privateKey);

// 加密示例

$sm2EncryptData = $base64Sm2->sm2EncryptASN1Base64($document, $publicKey);
var_dump('SM2加密后: ' . $sm2EncryptData);


// 解密示例
// $sm2EncryptData = "MG8CIFc2NGwa/EGdSKLv3twWhhxOcmMgEiAIoyc9gUiT1nApAiEAvjc6/RqoT0na5CbAAFptQKd+QxfXxoPJKVInTfwv+1sEIATc+5qtTvv0NOoWqxutx9VnpFFETHZ7vmUjl+nbvIa9BAZkWXp63H8="; // Go代码加密的数据
$sm2DecryptData = $base64Sm2->sm2DecryptASN1($sm2EncryptData, $privateKey);
var_dump('SM2解密后:' . $sm2DecryptData);


// 加签的示例
$signData = $base64Sm2->sm2SignDataBase64($document, $privateKey);
var_dump('SM2加签后:' . $signData);

// 验签的示例
// $signData = "MEUCICMX5W+M1qXLYyHCJTeVD1URpiO/CDa8Xc08wV+6l6P7AiEA2/sMTEfghpsQYEjXjojcqTYAfnIeYKdb4VohuAswOpI="; // Go代码生成的签名
$verifySign = $base64Sm2->sm2VerifySignature($document, $signData, $publicKey);
var_dump('SM2验签后:' . $verifySign ? true : false);


// 灵工优才加签的示例
$signature = new Signature();

// 灵工优才请求参数
$param = '{"crowsourceId":"","bankCardNo":"6222620910008201869","idCardNo":"220203199510271516","platformType":"4","realName":"zhangsan","mobilePhone":"13888888888"}';
//$param = '{"crowsourceId":"","orderBatchNo":"B202209130009","orderList":[{"callBackUrl":"","invoiceNum":"O202209130009","orderAmt":"0.1","orderRemark":"test用途","toBankCard":"6214830179537526","toIdCardNo":"220203199510271516","toMobilePhone":"13261081686","toName":"王潇毅"},{"callBackUrl":"","invoiceNum":"O202209130009","orderAmt":"0.1","orderRemark":"test用途","toBankCard":"6214830179537526","toIdCardNo":"220203199510271516","toMobilePhone":"13261081686","toName":"王潇毅"}],"platformType":"4"}';
// 灵工优才请求地址
$urlPath = '/api/contract/v1/applySign';
$result = $signature->signData($param, $publicKey, $urlPath);
var_dump('灵工优才加签结果: ' . $result);