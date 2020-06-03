<?php

namespace VCComponent\Laravel\Tag\Validators;

use Illuminate\Contracts\Validation\Rule;
use VCComponent\Laravel\Vicoders\Core\Validators\AbstractValidator;

class TestValidator extends AbstractValidator
{
    protected $rules = [
        'EXAMPLE_RULE' => [
            'email' => ['required'],
        ],
    ];
}
