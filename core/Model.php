<?php

namespace app\core;

abstract class Model
{
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';

    /**
     * Tüm post verilerini var olan property'lere atar.
     *
     * @param [array] $data [Post verileri]
     * @return void
     */
    public function loadData($data)
    {
        /*
            Property_exist function checks if the 
            given property exists in the class
        */
        foreach ($data as $key => $value) {
            // Gelen değerleri burada property'lere akatarıyoruz.
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    abstract public function rules(): array;

    public array $errors = [];

    public function validate()
    {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};

            foreach ($rules as $rule) {

                $ruleName = $rule;
                if (!is_string($ruleName)) {
                    $ruleName = $rule[0];
                }
                if ($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addErrorForRule($attribute, self::RULE_REQUIRED);
                }
                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addErrorForRule($attribute, self::RULE_EMAIL);
                }
                if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
                    $this->addErrorForRule($attribute, self::RULE_MIN, $rule);
                }
                if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
                    $this->addErrorForRule($attribute, self::RULE_MAX, $rule);
                }
                if ($ruleName == self::RULE_MATCH && $value !== $this->{$rule['match']}) {
                    $rule['match'] = $this->getLabel($rule['match']);
                    $this->addErrorForRule($attribute, self::RULE_MATCH, $rule);
                }
                if ($ruleName === self::RULE_UNIQUE) {
                    $className = $rule['class'];
                    $uniqueAttribute = $rule['attribute'] ?? $attribute;
                    $tableName = $className::tableName();
                    $statement =   Application::$app->db->prepare("SELECT * FROM $tableName WHERE $uniqueAttribute = :attr");
                    $statement->bindValue(":attr", $value);
                    $statement->execute();
                    $record = $statement->fetchObject();
                    if ($record) {
                        $this->addErrorForRule($attribute, self::RULE_UNIQUE, ['field' => $this->getLabel($attribute)]);
                    }
                }
            }
        }

        return empty($this->errors);
    }

    private function addErrorForRule(string $attribute, string $rule, $params = [])
    {
        $message = $this->errorMessages()[$rule] ?? '';

        foreach ($params as $key => $value) {
            $message = str_replace("$key", $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }

    public function addError(string $attribute, string $message)
    {
       
        $this->errors[$attribute][] = $message;
    }

    public function errorMessages()
    {

        return [
            self::RULE_REQUIRED => 'Bu alan boş geçilemez ',
            self::RULE_EMAIL => 'Girdiğiniz email adresi geçerli değildir',
            self::RULE_MIN => "Minumum {min} karakter girilmeli",
            self::RULE_MAX => "Maksimum {max} karakter girilmeli",
            self::RULE_MATCH => 'Bu alan {match} alanı ile eşleşmelidir ',
            self::RULE_UNIQUE => 'Girmiş olduğunuz {field} halihazırda veritabanında var . '
        ];
    }


    public function hasError($attribute)
    {

        return $this->errors[$attribute] ?? false;
    }

    public function getFirstError($attribute)
    {
        return $this->errors[$attribute][0] ?? false;
    }
    /**
     * Bu metot paramatre olarak aldığı attribute ile ilgili label değerini döndürür .
     *
     * @param [string] $attribute
     * @return void
     */
    public function getLabel($attribute)
    {
        return $this->labels()[$attribute] ?? $attribute;
    }
    /**
     * Bu metot form field sınıfının labelleri ne isimle yazdıracağını belirttiğimiz metot
     *
     * @return array
     */
    public function labels(): array
    {
        return [];
    }
}
