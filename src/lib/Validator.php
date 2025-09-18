<?php

namespace PanduputragmailCom\PhpNative\lib;

class Validator
{
    //forgot to add these variable
    public array $errors = [];
    protected array $data = [];
    protected array $rules = [];

    // constructor
    public function __construct(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;

        $this->validate();
    }

    protected function validate(): void
    {
        foreach ($this->rules as $field => $ruleString) {
            $rules = explode('|', $ruleString);

            foreach ($rules as $rule) {                
                if ($rule === 'required') {
                    if (!isset($this->data[$field]) || trim((string)$this->data[$field]) === '') {
                        $this->errors[$field][] = "The {$field} field is required.";
                    }
                }

                if ($rule === 'string' && isset($this->data[$field])) {
                    if (!is_string($this->data[$field])) {
                        $this->errors[$field][] = "The {$field} field must be a string.";
                    }
                }

                if (str_starts_with($rule, 'max:') && isset($this->data[$field])) {
                    $max = (int)substr($rule, 4);
                    if (strlen($this->data[$field]) > $max) {
                        $this->errors[$field][] = "The {$field} field may not be greater than {$max} characters.";
                    }
                }
            }
        }
    }

    //if validator fails
    public function fails(): bool
    {
        return !empty($this->errors);
    }

    //messages
    public function messages(): array
    {
        return $this->errors;
    }
}
