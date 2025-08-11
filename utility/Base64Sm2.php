<?php
namespace Utility;

use Libs\StacSm2;
use Rtgm\sm\RtSm2;

class Base64Sm2
{
    protected $logger = null;

    protected $sm2 = null;

    public function __construct()
    {
        $this->logger = new Logger();
        $this->sm2 = new StacSm2();
    }

    /**
     * @description 生成密钥对
     * @return array
     * */
    public function generateKey()
    {
        $data = $this->sm2->generatekey();
        $this->logger->writeLog('generateKey生成密钥对的数据', ['data' => $data]);
        return [base64_encode(hex2bin($data[0])), base64_encode(hex2bin($data[1]))];
    }

    /**
     * @description sm2根据公钥加密
     * @param string $key
     * @param string $publicKey
     * @return string
     */
    public function sm2Encrypt($key, $publicKey)
    {

        $data = $this->sm2->doEncrypt($key, bin2hex(base64_decode($publicKey)));
        $this->logger->writeLog('sm2Encrypt加密后的数据', ['data' => $data]);
        return $data;
    }

    /**
     * @description 加密值根据私钥解密
     * @param string $value
     * @param string $privateKey
     * @return string
     */
    public function sm2Decrypt($value, $privateKey)
    {
        $sm2 = new RtSm2();
        $data = $this->sm2->doDecrypt($value, bin2hex(base64_decode($privateKey)));
        $this->logger->writeLog('sm2Decrypt解密后的数据', ['data' => $data]);
        return $data;
    }


    /**
     * @description sm2根据公钥加密
     * @param string $content
     * @param string $publicKey
     * @return string
     */
    public function sm2EncryptASN1Base64($content, $publicKey)
    {

        $data = $this->sm2->doEncryptASN1($content, bin2hex(base64_decode($publicKey)));
        $this->logger->writeLog('sm2Encrypt加密后的数据', ['data' => $data]);
        return base64_encode($data);
    }

    /**
     * @description 加密值根据私钥解密
     * @param string $value
     * @param string $privateKey
     * @return string
     */
    public function sm2DecryptASN1($value, $privateKey)
    {
        $sm2 = new RtSm2();
        $data = $this->sm2->doDecryptASN1($value, bin2hex(base64_decode($privateKey)));
        $this->logger->writeLog('sm2Decrypt解密后的数据', ['data' => $data]);
        return $data;
    }

    /**
     * @description sm2根据私钥签名
     * @param string $content
     * @param string $privateKey
     * @return string
     */
    public function sm2SignDataBase64($content, $privateKey)
    {
        $data = $this->sm2->doSign($content, bin2hex(base64_decode($privateKey)));
        $this->logger->writeLog('sm2SignData加签后的数据', ['data' => $data]);
        return $data;
    }

    /**
     * @description sm2根据公钥验签
     * @param string $content
     * @param string $sign
     * @param string $publicKey
     * @return bool
     */
    public function sm2VerifySignature($content, $sign, $publicKey)
    {
        $data = $this->sm2->verifySign($content, $sign, bin2hex(base64_decode($publicKey)));
        $this->logger->writeLog('sm2VerifySign验签后的数据', ['data' => $data]);
        return $data;
    }

}
