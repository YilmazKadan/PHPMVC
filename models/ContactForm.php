<?php


namespace app\models;

use app\core\db\DbModel;
use app\core\Model;

class ContactForm extends DbModel
{
    public string $subject = '';
    public string $body = '';
    public string $email = '';

    public function rules(): array
    {
        return [
            'subject' => [self::RULE_REQUIRED],
            'body' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
        ];
    }

    public function labels():array{
        return [
            'subject' => 'Konu giriniz',
            'body' => 'Mesajınızı giriniz',
            'email' => 'Email adresinizi giriniz'
        ];
    }
    
    public static function tableName(): string
    {
        return 'iletisim';
    }
    
    public function attributes(): array
    {
        return ['subject', 'body', 'email'];
    }

    public static function primaryKey(): string
    {
        return 'id';
    }
}
