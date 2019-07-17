# DCore SDK for PHP

Set of APIs for accessing the DCore Blockchain.<br>
If you are looking for other platforms you can find info [below](#official-dcore-sdks-for-other-platforms).

## Requirements

- [composer](https://getcomposer.org)
- [php ~7.1](http://php.net)
- [php json](http://php.net/manual/en/book.json.php)
- [php bcmath](http://php.net/manual/en/book.bc.php)
- [php gmp](http://php.net/manual/en/book.gmp.php)
- [php openssl](http://php.net/manual/en/book.openssl.php)
- [symfony PropertyAccess component](https://symfony.com/doc/current/components/property_access.html)
- [websocket-php - websocket library](https://github.com/Textalk/websocket-php)
- [stephen-hill/base58php - base58 conversion library](https://github.com/stephen-hill/base58php)
- [kornrunner/php-secp256k1 - secp256k1 library](https://github.com/kornrunner/php-secp256k1)
- [BitcoinPHP/BitcoinECDSA.php - ecdsa library](https://github.com/BitcoinPHP/BitcoinECDSA.php)

## Instalation

composer.json
```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/decentfoundation/dcorephp-sdk"
        }
    ],
    "require": {
        "decentfoundation/dcorephp-sdk": "dev-master"
    }
}
```

```bash
composer require decentfoundation/dcorephp-sdk
```

## Usage

### DCore API initialization

```php
$dcoreApi = new \DCorePHP\DCoreApi(
    'https://testnet-api.dcore.io/',
    'wss://testnet-api.dcore.io'
);
```

Look at ./src/DCoreApi.php and ./src/Sdk/*Interface.php to see all available methods and their return values.

### Get account

```php
$account = $dcoreApi->getAccountApi()->get(new ChainObject('1.2.34'));
$account = $dcoreApi->getAccountApi()->getByName('Your test account name');
```

### Create account

There are two ways to create account in DCore network: `$dcoreApi->getAccountApi()->registerAccount()` and `$dcoreApi->getAccountApi()->createAccountWithBrainKey()`. 
Recommended way to create account is using `$dcoreApi->getAccountApi()->registerAccount()` method, because it has an option to specify keys. You can use `$dcoreApi->getAccountApi()->createAccountWithBrainKey()`, but keys generated from `$brainkey` for `$publicOwnerKeyWif`, `$publicActiveKeyWif` and `$publicMemoKeyWif` will be the same, which is not recommended for security reasons.

```php
$dcoreApi->getAccountApi()->registerAccount(
    'Your test account name',
    'DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz',
    'DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz',
    'DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz',
    new ChainObject('1.2.34'),
    '5Jd7zdvxXYNdUfnEXt5XokrE3zwJSs734yQ36a1YaqioRTGGLtn'
);
```

### Create transfer

```php
$dcoreApi->getAccountApi()->transfer(
    new Credentials(new ChainObject('1.2.34'), '5Jd7zdvxXYNdUfnEXt5XokrE3zwJSs734yQ36a1YaqioRTGGLtn'),
    '1.2.35',
    (new AssetAmount())->setAmount(1500000),
    'your secret message',
    false
);
```

### Create content

```php
$content = new SubmitContent();
$content
    ->setUri($randomUri)
    ->setCoauthors([])
    ->setCustodyData(null)
    ->setHash('2222222222222222222222222222222222222222')
    ->setKeyParts([])
    ->setSeeders([])
    ->setQuorum(0)
    ->setSize(10000)
    ->setSynopsis(json_encode([
        'title' => 'Your content title',
        'description' => 'Your content description',
        'content_type_id' => '1.2.3'
    ]))
    ->setExpiration('2019-05-28T13:32:34+00:00')
    ->setPrice([(new RegionalPrice)->setPrice((new AssetAmount())->setAmount(1000))->setRegion(1)]);

$credentials = new Credentials(
    new ChainObject('1.2.34'),
    ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1)
);

$dcoreApi->getContentApi()->create(
    $content,
    $credentials,
    (new AssetAmount())->setAmount(1000)->setAssetId('1.3.0'),
    (new AssetAmount())->setAmount(1000)->setAssetId('1.3.0')
);
```

### Search content

```php
$contents = $dcoreApi->getContentApi()->findAll(
    'search term',
    '-rating'
);
```

### NFT
NftModels **require** `@Type("type")` annotation for correct functioning. [GMP library](https://www.php.net/manual/en/intro.gmp.php) is also **necessary** when working with integers.
#### NftModel 
```php
class NftApple extends NftModel
{
    /**
     * @Type("integer")
     */
    public $size;
    /**
     * @Type("string")
     * @Unique
     */
    public $color;
    /**
     * @Type("boolean")
     * @Modifiable("both")
     */
    public $eaten;

    public function __construct($size, $color, $eaten)
    {
        $this->size = gmp_init($size);
        $this->color = $color;
        $this->eaten = $eaten;
    }
}
```

#### Create NFT
```php
$credentials = new Credentials(new ChainObject('1.2.27'), ECKeyPair::fromBase58('DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz'));
$dcoreApi->getNftApi()->create($credentials, 'APPLE', 100, false, 'an apple', NftApple::class, true);
```
More examples can be found in ./tests/Sdk/NftApiTest.php.

### Development requirements & recommendations

- [docker](https://docs.docker.com/install/)
- [docker-compose](https://docs.docker.com/compose/install/)
- [phpunit](https://phpunit.de/)
- [symfony VarDumper component](https://symfony.com/doc/current/components/var_dumper.html)
- [php code sniffer](https://github.com/squizlabs/PHP_CodeSniffer)
- [php code sniffer fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer)
- [php mess detector](https://github.com/phpmd/phpmd)

### PHPStorm configuration

- https://www.jetbrains.com/help/phpstorm/using-php-code-sniffer.html
- https://www.jetbrains.com/help/phpstorm/using-php-cs-fixer.html
- https://www.jetbrains.com/help/phpstorm/using-php-mess-detector.html

## Development & testing

```bash
git clone git@github.com:decentfoundation/dcorephp-sdk.git
cd dcorephp-sdk
docker-compose up -d
docker-compose exec php composer install --dev --prefer-dist --optimize-autoloader
docker-compose exec php ./vendor/bin/phpunit --bootstrap vendor/autoload.php tests
```

## Official DCore SDKs for other platforms

- [iOS/Swift](https://github.com/DECENTfoundation/DCoreSwift-SDK)
- [Android/Java/Kotlin](https://github.com/DECENTfoundation/DCoreKt-SDK)
- [JavaScript/TypeScript/Node.js](https://github.com/DECENTfoundation/DCoreJS-SDK)
