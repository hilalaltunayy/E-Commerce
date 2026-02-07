<?php

namespace App\Models;

use CodeIgniter\Model;

class BaseUuidModel extends Model
{
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;

    protected $beforeInsert = ['addUuid'];

    protected function addUuid(array $data)
    {
        if (empty($data['data']['id'])) {
            $data['data']['id'] = self::uuidV4();
        }
        return $data;
    }

    public static function uuidV4(): string
    {
        $bytes = random_bytes(16);
        $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
        $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
    }
}