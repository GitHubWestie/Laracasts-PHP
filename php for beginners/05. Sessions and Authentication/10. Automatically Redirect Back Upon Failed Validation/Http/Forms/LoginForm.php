<?php

namespace Http\Forms;

use Core\ValidationException;
use Core\Validator;

class LoginForm {

    // Store validation errors
    protected $errors = [];

    // Constructor takes in user-submitted attributes (email, password)
    public function __construct(public array $attributes)
    {
        // Validate email format with Validator class method
        if (!Validator::email($attributes['email'])) {
            $this->errors['email'] = 'Please provide a valid email address.';
        }

        // Validate password with Validator class method
        if (!Validator::string($attributes['password'])) {
            $this->errors['password'] = 'Please provide a valid password.';
        }
    }

    // Validate attributes and either return the instance or throw errors
    public static function validate($attributes)
    {
        $instance = new static($attributes);

        // If validation failed, throw an exception; otherwise return the instance
        return $instance->failed() ? $instance->throw() : $instance;
    }

    // Throws a validation exception using collected errors
    public function throw()
    {
        ValidationException::throw($this->errors, $this->attributes);
    }

    // Returns true if there are any validation errors
    public function failed()
    {
        return count($this->errors);
    }

    // Returns all validation errors
    public function errors()
    {
        return $this->errors;
    }

    // Adds a custom error for a specific field
    public function error($field, $message) {
        $this->errors[$field] = $message;
        return $this;
    }
}