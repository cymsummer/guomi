# 国投人力SM2及灵工优才加签PHP示例

## Base64Sm2类

### (1) generateKey 生成密钥对函数

1. **函数说明**

   `generateKey` 是`Base64Sm2`类的成员方法，在需要生成SM2密钥对时使用

2. **函数调用示例**

   首先需要确保引入依赖

    ```php
    use Utility\Base64Sm2;
    ```

    调用时实例化类，并调用类中的对应函数

    ```php
    $base64Sm2 = new Base64Sm2();

    // 生成密钥对
    $generateKey = $base64Sm2->generateKey();
    var_dump('私钥为: ' . $generateKey[0]);
    var_dump('公钥为: ' . $generateKey[1]);
    ```

3. **函数入参**

    该函数没有入参。

4. **函数返回结果**

    返回结果为数组，数组的第一个元素为`公钥`，第二个元素为`私钥`，以PHP格式解释，可参考下面示例：

    ```php
    Array ( 
    [0] => 7ZpJLUPjVvzgS1PuAwtM+ZOMv7e95rhZqxcW+/RsuGs=
    [1] => BP2fuM+MeRVRM2ybcW/+Zh30D9nwKIfxi28TZu265Yq4YjLEdyZa245NszKwfMnQUd1aariIAyEBP/X0+/eEuhM= 
    )
    ```

5. **注意事项**
   1. SM2具备多种加解密方式，国投人力使用的是C1C3C2，ASN1哈希+Base64转码的方法。
   2. 公钥和私钥不应每次动态生成，而是应该提前生成后作为加解密、加签验签双方的约定。

---

### (2) sm2EncryptASN1Base64 加密函数

1. **函数说明**

    `sm2EncryptASN1Base64` 是`Base64Sm2`类的成员方法，在需要加密数据时使用

2. **函数调用示例**

    首先需要确保引入依赖

    ```php
    use Utility\Base64Sm2;
    ```

    调用时实例化类，并调用类中的对应函数

    ```php
    $base64Sm2 = new Base64Sm2();

    // 原始字符串
    $document = '123456';
    var_dump("原始: $document");

    // publicKey
    $publicKey = 'BBP1gMIMFTfUcaqwg8IJRFo6XzHFJVYO7PIWGA5pcu3VCEA3TqmnvL+g/vlPtVjvBJY/SfWL9ZHCA1jeAQrEd8o='; // 公钥
    $m2EncryptData = $base64Sm2->sm2EncryptASN1Base64($document, $publicKey);
    var_dump("\n加密后: " . $m2EncryptData);
    ```

3. **函数入参**

    | 参数名       | 必传  | 类型     | 说明      |
    |:----------|:----|:-------|---------|
    | content       | 是   | string | 需要加密的数据 |
    | publicKey | 是   | string | 密钥对中的公钥 |

4. **函数返回结果**

    返回结果为base64格式的文本，参考格式如下：

    ```text
    e27c3780e7069bda7082a23a489d77587ce309583ed99253f66e1d9833ed1a1d0MG8CIFc2NGwa/EGdSKLv3twWhhxOcmMgEiAIoyc9gUiT1nApAiEAvjc6/RqoT0na5CbAAFptQKd+QxfXxoPJKVInTfwv+1sEIATc+5qtTvv0NOoWqxutx9VnpFFETHZ7vmUjl+nbvIa9BAZkWXp63H8=
    ```

5. **注意事项**
   1. SM2具备多种加解密方式，国投人力使用的是C1C3C2，ASN1哈希+Base64转码的方法。
   2. 公钥和私钥不应每次动态生成，而是应该提前生成后作为加解密、加签验签双方的约定。
   3. 加密的结果是动态的，每次都不一样，但均可以被解密成同一个原文。

---

### (3) sm2DecryptASN1 解密函数

1. **函数说明**

    `sm2DecryptASN1` 是`Base64Sm2`类的成员方法，在需要解密数据时使用

2. **函数调用示例**

    首先需要确保引入依赖

    ```php
    use Utility\Base64Sm2;
    ```

    调用时实例化类，并调用类中的对应函数

    ```php
    $base64Sm2 = new Base64Sm2();

    // 提前加密好的数据
    $sm2EncryptData = "MG8CIFc2NGwa/EGdSKLv3twWhhxOcmMgEiAIoyc9gUiT1nApAiEAvjc6/RqoT0na5CbAAFptQKd+QxfXxoPJKVInTfwv+1sEIATc+5qtTvv0NOoWqxutx9VnpFFETHZ7vmUjl+nbvIa9BAZkWXp63H8="; 
    $sm2DecryptData = $base64Sm2->sm2DecryptASN1($sm2EncryptData, $privateKey);
    var_dump('SM2解密后:' . $sm2DecryptData);
    ```

3. **函数入参**

    | 参数名       | 必传  | 类型     | 说明      |
    |:----------|:----|:-------|---------|
    | value      | 是   | string | 加密后的数据  |
    | privateKey | 是   | string | 密钥对中的私钥 |

4. **函数返回结果**

    返回结果为原数据格式，默认为文本，参考格式如下：

    ```text
    123456
    ```

5. **注意事项**
   1. SM2具备多种加解密方式，国投人力使用的是C1C3C2，ASN1哈希+Base64转码的方法。
   2. 公钥和私钥不应每次动态生成，而是应该提前生成后作为加解密、加签验签双方的约定。

---

### (4) sm2SignDataBase64 加签函数

1. **函数说明**

    `sm2SignDataBase64` 是`Base64Sm2`类的成员方法，在需要加签数据时使用

2. **函数调用示例**

    首先需要确保引入依赖

    ```php
    use Utility\Base64Sm2;
    ```

    调用时实例化类，并调用类中的对应函数

    ```php
    $base64Sm2 = new Base64Sm2();

    // 需要加签的数据
    $document = '123456';

    $signData = $base64Sm2->sm2SignDataBase64($document, $privateKey);
    var_dump('SM2加签后:' . $signData);
    ```

3. **函数入参**

    | 参数名       | 必传  | 类型     | 说明      |
    |:----------|:----|:-------|---------|
    | content       | 是   | string | 需要加签的数据 |
    | privateKey | 是   | string | 密钥对中的私钥 |

4. **函数返回结果**

    返回结果为base64格式的文本，参考格式如下：

    ```text
    MEUCICMX5W+M1qXLYyHCJTeVD1URpiO/CDa8Xc08wV+6l6P7AiEA2/sMTEfghpsQYEjXjojcqTYAfnIeYKdb4VohuAswOpI=
    ```

5. **注意事项**
   1. 加签的结果是动态的，每次都不一样，但均可以被成功验签。

---

### (5) sm2VerifySignature 验签函数

1. **函数说明**

    `sm2VerifySignature` 是`Base64Sm2`类的成员方法，在需要验签数据时使用

2. **函数调用示例**

    首先需要确保引入依赖

    ```php
    use Utility\Base64Sm2;
    ```

    调用时实例化类，并调用类中的对应函数

    ```php
    $base64Sm2 = new Base64Sm2();

    // 需要验签的数据
    $signData = "MEUCICMX5W+M1qXLYyHCJTeVD1URpiO/CDa8Xc08wV+6l6P7AiEA2/sMTEfghpsQYEjXjojcqTYAfnIeYKdb4VohuAswOpI="; 

    $verifySign = $base64Sm2->sm2VerifySignature($document, $signData, $publicKey);
    var_dump('SM2验签后:' . $verifySign? true : false);
    ```

3. **函数入参**

    | 参数名       | 必传  | 类型     | 说明      |
    |:----------|:----|:-------|---------|
    | content       | 是   | string | 需要加签的数据 |
    | privateKey | 是   | string | 密钥对中的私钥 |

4. **函数返回结果**

    返回结果为布尔值，参考格式如下：

    ```text
    true
    ```

---

5. **注意事项**

    暂无

## Signature类

### (1) signData 灵工优才加签函数

1. **函数说明**

    `signData` 是`Signature`类的成员方法，在需要加签数据时使用

2. **函数调用示例**

    首先需要确保引入依赖

    ```php
    use Utility\Signature;
    ```

    调用时实例化类，并调用类中的对应函数

    ```php
    $signature = new Signature();

    // 灵工优才请求参数
    $param = '{"crowsourceId":"","bankCardNo":"6222620910008201869","idCardNo":"220203199510271516","platformType":"4","realName":"zhangsan","mobilePhone":"13888888888"}';
    // 灵工优才请求地址
    $urlPath = '/api/contract/v1/applySign';
    $result = $signature->signData($param, $publicKey, $urlPath);
    var_dump('灵工优才加签结果: ' . $result);
    ```

3. **函数入参**

    | 参数名       | 必选  | 类型     | 说明     |
    |:----------|:----|:-------|--------|
    | param     | 是   | string | 请求加签参数 |
    | publicKey | 是   | string | SM2公钥     |
    | urlPath   | 是   | string | 请求接口路径 |

4. **函数返回结果**

    返回结果为Hex格式的文本，参考格式如下：

    ```text
    084ff854f46dfee73ae6ffcdad3c7d08daed047e
    ```

5. **注意事项**

    暂无
