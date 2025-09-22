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

                if ($rule === 'integer' && isset($this->data[$field])) {
                    if (!is_int($this->data[$field]) && !ctype_digit((string)$this->data[$field])) {
                        $this->errors[$field][] = "The {$field} field must be an integer.";
                    }
                }

                if ($rule === 'boolean' && isset($this->data[$field])) {
                    $acceptable = [true, false, 1, 0, '1', '0'];
                    if (!in_array($this->data[$field], $acceptable, true)) {
                        $this->errors[$field][] = "The {$field} field must be a boolean.";
                    }
                }

                if ($rule === 'numeric' && isset($this->data[$field])) {
                    if (!is_numeric($this->data[$field])) {
                        $this->errors[$field][] = "The {$field} field must be a number.";
                    }
                }

                if ($rule === 'email' && isset($this->data[$field])) {
                    if (!filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
                        $this->errors[$field][] = "The {$field} field must be a valid email address.";
                    }
                }

                if ($rule === 'url' && isset($this->data[$field])) {
                    if (!filter_var($this->data[$field], FILTER_VALIDATE_URL)) {
                        $this->errors[$field][] = "The {$field} field must be a valid URL.";
                    }
                }

                // Date (YYYY-MM-DD or valid date format)
                if ($rule === 'date' && isset($this->data[$field])) {
                    $date = date_create($this->data[$field]);
                    if (!$date) {
                        $this->errors[$field][] = "The {$field} field must be a valid date.";
                    }
                }

                if ($rule === 'array' && isset($this->data[$field])) {
                    if (!is_array($this->data[$field])) {
                        $this->errors[$field][] = "The {$field} field must be an array.";
                    }
                }

                if (str_starts_with($rule, 'max:') && isset($this->data[$field])) {
                    $max = (int)substr($rule, 4);
                    $value = $this->data[$field];

                    if (is_string($value)) {
                        if (strlen($value) > $max) {
                            $this->errors[$field][] = "The {$field} field may not be greater than {$max} characters.";
                        }
                    } elseif (is_numeric($value)) {
                        if ((float)$value > $max) {
                            $this->errors[$field][] = "The {$field} field may not be greater than {$max}.";
                        }
                    }
                }

                if (str_starts_with($rule, 'min:') && isset($this->data[$field])) {
                    $min = (int)substr($rule, 4);
                    $value = $this->data[$field];

                    if (is_string($value)) {
                        if (strlen($value) < $min) {
                            $this->errors[$field][] = "The {$field} field must be at least {$min} characters.";
                        }
                    } elseif (is_numeric($value)) {
                        if ((float)$value < $min) {
                            $this->errors[$field][] = "The {$field} field must be at least {$min}.";
                        }
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
