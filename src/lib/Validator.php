<?php

namespace PanduputragmailCom\PhpNative\lib;

class Validator
{
    public function Validator(array $data, array $rules){
        $errors = [];

        foreach($rules as $rulesField => $rulesString){
            //pembatas nya itu dah
            $rulesArray = explode('|', $rulesString);
            foreach($rulesArray as $ruleCondition){
                if($ruleCondition === 'required'){
                    if (!isset($data[$rulesField]) || trim($data[$rulesField]) === '') {
                        $errors[$rulesField][] = "The {$rulesField} field is required.";
                    }
                }
                if ($ruleCondition === 'string' && isset($data[$rulesField]) && !is_string($data[$rulesField])) {
                    $errors[$rulesField][] = "The {$rulesField} field must be a string.";
                }
            }
        }

        return $errors;
    }
}