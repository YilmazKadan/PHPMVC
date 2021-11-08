<?php

namespace app\core;

abstract class Model
{
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';

    // Bu  metot controllerden gelen post datalarını sınıftaki propertylere atar .
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
                    $this->addError($attribute, self::RULE_REQUIRED);
                }
                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($attribute, self::RULE_EMAIL);
                }
                if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
                    $this->addError($attribute, self::RULE_MIN, $rule);
                }
                if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
                    $this->addError($attribute, self::RULE_MAX, $rule);
                }
                if($ruleName == self::RULE_MATCH && $value !== $this->{$rule['match']}){
                    $this->addError($attribute, self::RULE_MATCH, $rule);
                }
            }
        }

        return empty($this->errors);
    }

    public function addError(string $attribute, string $rule, $params = [])
    {
        $message = $this->errorMessages()[$rule] ?? '';

        foreach ($params as $key => $value) {
            $message = str_replace("$key", $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }

    public function errorMessages()
    {

        return [
            self::RULE_REQUIRED => 'Bu alan boş geçilemez ',
            self::RULE_EMAIL => 'Girdiğiniz email adresi geçerli değildir',
            self::RULE_MIN => "Minumum {min} karakter girilmeli",
            self::RULE_MAX => "Maksimum {max} karakter girilmeli",
            self::RULE_MATCH => 'Bu alan {match} alanı ile eşleşmelidir '
        ];
    }


    public function hasError($attribute){

        return $this->errors[$attribute] ?? false;
    }

    public function getFirstError($attribute){
        return $this->errors[$attribute][0] ?? false;
    }
}
