<?php

namespace PanduputragmailCom\PhpNative\lib;

class Validator
{
    public array $errors = [];
    protected array $data = [];
    protected array $rules = [];

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
                $this->applyRule($field, $rule);
            }
        }
    }

    private function applyRule(string $field, string $rule): void
    {
        $param = null;
        if (str_contains($rule, ':')) {
            [$ruleName, $param] = explode(':', $rule, 2);
        } else {
            $ruleName = $rule;
        }

        $methodName = 'validate' . ucfirst($ruleName);

        if (!method_exists($this, $methodName)) {
            throw new \InvalidArgumentException("Validation rule [{$ruleName}] is not supported.");
        }

        $this->$methodName($field, $param);
    }

    private function validateRequired(string $field, ?string $param = null): void
    {
        $value = $this->data[$field] ?? null;
        if ($value === null || (is_string($value) && trim($value) === '')) {
            $this->errors[$field][] = "The {$field} field is required.";
        }
    }

    private function validateString(string $field, ?string $param = null): void
    {
        if (isset($this->data[$field]) && !is_string($this->data[$field])) {
            $this->errors[$field][] = "The {$field} field must be a string.";
        }
    }

    private function validateInteger(string $field, ?string $param = null): void
    {
        $value = $this->data[$field] ?? null;
        if ($value !== null && !is_int($value) && !ctype_digit((string)$value)) {
            $this->errors[$field][] = "The {$field} field must be an integer.";
        }
    }

    private function validateBoolean(string $field, ?string $param = null): void
    {
        $value = $this->data[$field] ?? null;
        $acceptable = [true, false, 1, 0, '1', '0'];
        if ($value !== null && !in_array($value, $acceptable, true)) {
            $this->errors[$field][] = "The {$field} field must be a boolean.";
        }
    }

    private function validateNumeric(string $field, ?string $param = null): void
    {
        $value = $this->data[$field] ?? null;
        if ($value !== null && !is_numeric($value)) {
            $this->errors[$field][] = "The {$field} field must be a number.";
        }
    }

    private function validateEmail(string $field, ?string $param = null): void
    {
        $value = $this->data[$field] ?? null;
        if ($value !== null && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = "The {$field} field must be a valid email address.";
        }
    }

    private function validateUrl(string $field, ?string $param = null): void
    {
        $value = $this->data[$field] ?? null;
        if ($value !== null && !filter_var($value, FILTER_VALIDATE_URL)) {
            $this->errors[$field][] = "The {$field} field must be a valid URL.";
        }
    }

    private function validateDate(string $field, ?string $param = null): void
    {
        $value = $this->data[$field] ?? null;
        if ($value !== null && !date_create($value)) {
            $this->errors[$field][] = "The {$field} field must be a valid date.";
        }
    }

    private function validateArray(string $field, ?string $param = null): void
    {
        $value = $this->data[$field] ?? null;
        if ($value !== null && !is_array($value)) {
            $this->errors[$field][] = "The {$field} field must be an array.";
        }
    }

    private function validateMax(string $field, ?string $param = null): void
    {
        if ($param === null) return;

        $max = (int)$param;
        $value = $this->data[$field] ?? null;
        if ($value === null) return;

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

    private function validateMin(string $field, ?string $param = null): void
    {
        if ($param === null) return;

        $min = (int)$param;
        $value = $this->data[$field] ?? null;
        if ($value === null) return;

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

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function messages(): array
    {
        return $this->errors;
    }
}