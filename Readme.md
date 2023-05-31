## Base64Sm2类使用说明

### 生成密钥对使用generateKey

**方法返回结果为数组**

**返回参数示例**

```php
Array ( 
[0] => 7ZpJLUPjVvzgS1PuAwtM+ZOMv7e95rhZqxcW+/RsuGs=
[1] => BP2fuM+MeRVRM2ybcW/+Zh30D9nwKIfxi28TZu265Yq4YjLEdyZa245NszKwfMnQUd1aariIAyEBP/X0+/eEuhM= 
)
```

**调用方法代码示例**

```php
$base64Sm2 = new Base64Sm2();

// 生成密钥对
$generateKey = $base64Sm2->generateKey();
var_dump('私钥为: ' . $generateKey[0]);
var_dump('公钥为: ' . $generateKey[1]);
```

### 私钥加签使用doSign

**方法参数释义如下：**

| 参数名        | 必选  | 类型     | 说明      |
|:-----------|:----|:-------|---------|
| key        | 是   | string | 需要加密的数据 |
| privateKey | 是   | string | 密钥对中的私钥 |

**方法返回结果为字符串**

**返回参数示例**

```php
304402200d45c5eb1b6c9a7c21f3710d86fc1efe057b3d5c0f4a51706d454f232b9664d002202a570a21d09d6aeaa6cf2ed28d340f23fe655a4993330a4ba5ccac7fd51c8408
```

**调用方法代码示例**

```php
$base64Sm2 = new Base64Sm2();

// 原始字符串
$document = '123456';
var_dump("原始: $document");

// 生成密钥对
$generateKey = $base64Sm2->generateKey();

// 私钥加签
$sign = $base64Sm2->doSign($document, bin2hex(base64_decode($generateKey[0])));
var_dump('私钥加签结果为: ' . $sign);
```

### 公钥验签使用verifySign

**方法参数释义如下：**

| 参数名       | 必选  | 类型     | 说明      |
|:----------|:----|:-------|---------|
| key       | 是   | string | 需要加密的数据 |
| publicKey | 是   | string | 密钥对中的公钥 |

**方法返回结果为布尔**

**返回参数示例**

```php
true
```

**调用方法代码示例**

```php
$base64Sm2 = new Base64Sm2();

// 原始字符串
$document = '123456';
var_dump("原始: $document");

// 生成密钥对
$generateKey = $base64Sm2->generateKey();

// 私钥加签
$sign = $base64Sm2->doSign($document, bin2hex(base64_decode($generateKey[0])));
var_dump('私钥加签结果为: ' . $sign);

// 公钥验签
$verify = $base64Sm2->verifySign($document, $sign, bin2hex(base64_decode($generateKey[1])));
var_dump('公钥验签结果为: ' . $verify);
```

### 加密使用sm2Encrypt

**方法参数释义如下：**

| 参数名       | 必选  | 类型     | 说明      |
|:----------|:----|:-------|---------|
| key       | 是   | string | 需要加密的数据 |
| publicKey | 是   | string | 密钥对中的公钥 |

**方法返回结果为字符串**

**返回参数示例**

```php
e27c3780e7069bda7082a23a489d77587ce309583ed99253f66e1d9833ed1a1d0b5ce86dc6714e9974cf258589139d7b1855e8c9fa2f2c1175ee123a95a23e9bb18c3049021c1baad18068bcead198f9ed0b85221c8dee127d626759ed0e46cf6afdbadf8efc
```

**调用方法代码示例**

```php
$base64Sm2 = new Base64Sm2();

// 原始字符串
$document = '123456';
var_dump("原始: $document");

// publicKey
$publicKey = 'BBP1gMIMFTfUcaqwg8IJRFo6XzHFJVYO7PIWGA5pcu3VCEA3TqmnvL+g/vlPtVjvBJY/SfWL9ZHCA1jeAQrEd8o='; // 公钥
$m2EncryptData = $base64Sm2->sm2Encrypt($document, $publicKey);
var_dump("\n加密后: " . $m2EncryptData);
```

### 解密使用sm2Decrypt

**方法参数释义如下：**

| 参数名        | 必选  | 类型     | 说明      |
|:-----------|:----|:-------|---------|
| value      | 是   | string | 加密后的数据  |
| privateKey | 是   | string | 密钥对中的私钥 |

**方法返回结果为字符串**

**返回参数示例**

```php
08422
```

**调用方法代码示例**

```php
$base64Sm2 = new Base64Sm2();

// 加密后的字符串
$m2EncryptData ='e27c3780e7069bda7082a23a489d77587ce309583ed99253f66e1d9833ed1a1d0b5ce86dc6714e9974cf258589139d7b1855e8c9fa2f2c1175ee123a95a23e9bb18c3049021c1baad18068bcead198f9ed0b85221c8dee127d626759ed0e46cf6afdbadf8efc';

// privateKey
$privateKey = 'Ds0kAq+4OxuZKKXM3XQSX4VrO2yNpoWz4fZuPwcI720='; // 私钥
$m2DecryptData = $base64Sm2->sm2Decrypt($m2EncryptData, $privateKey);
var_dump("\n解密后:" . $m2DecryptData);
```

## 自己公司业务场景

## Signature类使用说明

### 加签使用signData

**方法参数释义如下：**

| 参数名       | 必选  | 类型     | 说明     |
|:----------|:----|:-------|--------|
| param     | 是   | string | 请求加签参数 |
| publicKey | 是   | string | 公钥     |
| urlPath   | 是   | string | 请求接口路径 |

**方法返回结果为字符串**

**返回参数示例**

```php
084ff854f46dfee73ae6ffcdad3c7d08daed047e
```

**调用方法代码示例**

```php
// 加签的示例
$signature = new Signature();

// 请求参数
$param = '{"crowsourceId":"","bankCardNo":"6222620910008201869","idCardNo":"220203199510271516","platformType":"4","realName":"zhangsan","mobilePhone":"13888888888"}';
// publicKey
$publicKey = 'BO6NBihKkEeojnLjyoL7fWUrlpSBvWWBrcvjpE/OlvFDZYeeNI7zWjrwxMn4KuaLyhEM1moZxwq1LtrQpZizes4=';
// 请求地址
$urlPath = '/api/contract/v1/applySign';

$signData = $signature->signData($param, $publicKey, $urlPath);
var_dump("\n加签后:" . $signData);
```