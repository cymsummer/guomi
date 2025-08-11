<?php

namespace Utility;


use PHPUnit\Framework\TestCase;

class Base64Sm2Test extends TestCase
{
    protected $publicKey = 'BBP1gMIMFTfUcaqwg8IJRFo6XzHFJVYO7PIWGA5pcu3VCEA3TqmnvL+g/vlPtVjvBJY/SfWL9ZHCA1jeAQrEd8o='; // 公钥
    protected $privateKey = 'Ds0kAq+4OxuZKKXM3XQSX4VrO2yNpoWz4fZuPwcI720='; // 私钥

    public function testEncrypt()
    {
        $base64Sm2 = new Base64Sm2();
        $document = '123456';

        $m2EncryptData = $base64Sm2->sm2Encrypt($document, $this->publicKey);
        $this->assertNotNull($m2EncryptData);

        $m2DecryptData = $base64Sm2->sm2Decrypt($m2EncryptData, $this->privateKey);
        $this->assertEquals($document, $m2DecryptData);
    }
}
