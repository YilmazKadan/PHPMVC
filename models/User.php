<?php


namespace app\models;

use app\core\UserModel;

class User extends UserModel
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    public string $firstname = '';
    public string $lastname = '';
    public string $email = '';
    public string $password = '';
    public int    $status = self::STATUS_INACTIVE;
    public string $confirmPassword = '';


    public static function tableName(): string
    {
        return 'users';
    }
    /**
     * @return array
     * Bu metot override edilmiştir.
     */
    public function save()
    {
        $this->status = self::STATUS_ACTIVE;
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::save();
    }

    public function rules(): array
    {
        return [
            'firstname' => [self::RULE_REQUIRED],
            'lastname' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [
                self::RULE_UNIQUE, 'class' => self::class

            ]],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8], [self::RULE_MAX, 'max' => 15]],
            'confirmPassword' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']],
        ];
    }

    public function labels(): array
    {
        return [
            'firstname' => 'Adınız',
            'lastname' => 'Soyadınız',
            'email' => 'Email',
            'password' => 'Şifre',
            'confirmPassword' => 'Şifre tekrarı',
        ];
    }


    public function attributes(): array
    {

        return ['firstname', 'lastname', 'email', 'password', 'status'];
    }

    public static function primaryKey(): string
    {
        return 'id';
    }

    public function getDisplayname():string
    {
        return $this->firstname . " " . $this->lastname;
    }
}
