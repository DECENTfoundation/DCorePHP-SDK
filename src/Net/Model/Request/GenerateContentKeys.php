<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\Content\ContentKeys;
use DCorePHP\Net\Model\Response\BaseResponse;

class GenerateContentKeys extends BaseRequest
{

    public function __construct(array $seeders)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'generate_content_keys',
            [$seeders]
        );
    }

    public static function responseToModel(BaseResponse $response): ContentKeys
    {
        $contentKeys = new ContentKeys();
        $rawKeys = $response->getResult();

        foreach (
            [
                '[key]' => 'key',
                // TODO: keyParts Unknown Structure
//                '[parts]' => 'keyParts',
            ] as $path => $modelPath
        ) {
            $value = self::getPropertyAccessor()->getValue($rawKeys, $path);
            self::getPropertyAccessor()->setValue($contentKeys, $modelPath, $value);
        }

        return $contentKeys;
    }
}