<?php

namespace Libs;

use FG\ASN1\Universal\Integer;
use FG\ASN1\Universal\OctetString;
use FG\ASN1\Universal\Sequence;
use Mdanter\Ecc\Primitives\Point;
use Rtgm\sm\RtSm2;
use Rtgm\smecc\SM2\Hex2ByteBuf;
use Rtgm\util\MyAsn1;

class StacSm2 extends RtSm2
{
    /**
     * SM2 公钥加密算法，ASN1编码
     *
     * @param string $document
     * @param string $publicKey 如提供的base64的，可使用 bin2hex(base64_decode($publicKey))
     * @return string
     */
    public function doEncryptASN1($document, $publicKey, $model = C1C3C2)
    {
        $adapter = $this->adapter;
        $generator = $this->generator;
        $this->cipher = new \Rtgm\smecc\SM2\Cipher();
        $arrMsg = Hex2ByteBuf::HexStringToByteArray2(bin2hex($document));

        list($pubKeyX, $pubKeyY) = $this->_getKeyXY($publicKey);
        // $key = $this->_getPubKeyObject( $pubKeyX, $pubKeyY );
        $point = new Point($adapter, $generator->getCurve(), gmp_init($pubKeyX, 16), gmp_init($pubKeyY, 16));
        // 是否使用固定的中间椭圆加密，

        $c1 = $this->cipher->initEncipher($point, null);

        $arrMsg = $this->cipher->encryptBlock($arrMsg);

        $c2 = Hex2ByteBuf::ByteArrayToHexString($arrMsg);
        $c3 = Hex2ByteBuf::ByteArrayToHexString($this->cipher->Dofinal());

        // 先将C1C3C2拼接
        $enc = $c1.$c3.$c2;

        // 再按照位置将X,y,derDig,derEnc拼接
        $x =  substr($c1, 0, 64);
        $y =  substr($enc, 64, 64);
        // 这里将x,y转换成10进制
        $decX = gmp_strval(gmp_init($x, 16), 10);
        $decY = gmp_strval(gmp_init($y, 16), 10);

        $derDig = substr($enc, 128, 64);
        $derEnc = substr($enc, 192);

        // 使用asn1编码
        $asn1 = new Sequence(
            new Integer($decX),
            new Integer($decY),
            new OctetString($derDig),
            new OctetString($derEnc)
        );

        return $asn1->getBinary();
    }

    /**
     * SM2 私钥解密算法，ASN1编码
     *
     * @param string $document
     * @param string $privateKey 如提供的base64的，可使用 bin2hex(base64_decode($privateKey))
     * @param bool $trim 是否做04开头的去除，看业务返回
     * @return string
     */
    public function doDecryptASN1($encryptData, $privateKey, $trim = true, $model = C1C3C2)
    {

        $encryptBinaryData = base64_decode($encryptData);

        $encryptData =  MyAsn1::decode($encryptBinaryData);
        if (is_array($encryptData)) {
            $encryptData = implode('', $encryptData);
        }

        $adapter = $this->adapter;
        $generator = $this->generator;
        $this->cipher = new \Rtgm\smecc\SM2\Cipher();
        $c1X = substr($encryptData, 0, 64);
        $c1Y = substr($encryptData, strlen($c1X), 64);
        $c1Length = strlen($c1X) + strlen($c1Y);
        if ($model == C1C3C2) {
            $c3 = substr($encryptData, $c1Length, 64);
            $c2 = substr($encryptData, $c1Length + strlen($c3));
        } else {
            $c3 = substr($encryptData, -64);
            $c2 = substr($encryptData, $c1Length, strlen($encryptData) - $c1Length - 64);
        }

        $p1 = new Point($adapter, $generator->getCurve(), gmp_init($c1X, 16), gmp_init($c1Y, 16));
        $this->cipher->initDecipher($p1, $privateKey);

        $arrMsg = Hex2ByteBuf::HexStringToByteArray2($c2);
        $arrMsg = $this->cipher->decryptBlock($arrMsg);
        $document = hex2bin(Hex2ByteBuf::ByteArrayToHexString($arrMsg));

        $c3_ = strtolower(Hex2ByteBuf::ByteArrayToHexString($this->cipher->Dofinal()));
        $c3 = strtolower($c3);
        if ($c3 == $c3_) { //hash签名相同，
            return $document;
        } else {
            return '';
        }
    }
}