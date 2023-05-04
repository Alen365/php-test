<?php

namespace App\Database;

class Storage
{
    public static array $data = [
        'articles' => ArticleData::class
    ];

    /**
     * @throws \Exception
     */
    public static function get($fileName): DataInterface
    {
        if (array_key_exists($fileName, self::$data)) {
            return new self::$data[$fileName]();
        }
        throw new \Exception('File does not exist!');
    }


}