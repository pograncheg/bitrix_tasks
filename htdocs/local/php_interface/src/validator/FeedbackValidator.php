<?php
namespace Custom\Validator;

/**
 * Class MainValidator
 * 
 * @package Custom\Validator;
 **/

class FeedbackValidator
{
    private $errors = [];

    public function validate(string $fieldname, mixed $value, array $rules)
    {
        if(isset($rules['required']) && !$this->checkNotEmpty($value)) {
            $this->errors[$fieldname][] = "Поле обязательно для заполнения!";
            return;
        }
        foreach ($rules as $rulename=>$ruleValue) {
            switch ($rulename) {
                case 'min':
                    if (!$this->checkMinLength($value, $ruleValue)) {
                        $this->errors[$fieldname][] = "Введите не менее $ruleValue символов!";
                    }
                    break;
                case 'format':
                    if (!$this->checkFormat($value, $ruleValue)) {
                        $formats = implode(' ,', $ruleValue);
                        $this->errors[$fieldname][] = "Разрешены только следующие форматы: $formats";
                    }
                    break;
                case 'email':
                    if (!$this->checkEmail($value, $ruleValue)) {
                        $this->errors[$fieldname][] = "Введите email!";
                    }
                    break;
            }
        }
    }
    

    private function checkNotEmpty(mixed $value)
    {
        return !empty($value);
    }

    private function checkEmail(string $value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    private function checkMinLength(string $value, int $minLength)
    {
        return mb_strlen($value, 'UTF-8') >= $minLength;
    }

    private function checkFormat(string $filePath, array $rules)
    {
        return in_array(pathinfo($filePath)['extension'], $rules);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}