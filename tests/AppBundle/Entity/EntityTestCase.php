<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class EntityTestCase extends TestCase
{

    protected function assertEntityConstrain($model, $field, $value, $valid, $message=null)
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        $violations = $validator->validatePropertyValue($model, $field, $value);

        $isValid = ($violations->count() === 0);

        if(is_null($message)){
            $message = 'Validate field "{field}" with value "{value}" should be {valid}';
        }

        $message = strtr(
            $message,
            array(
                '{field}' => $field,
                '{value}' => $value,
                '{valid}' => ($valid?'valid':'invalid'),
            )
        );

        $this->assertEquals($valid, $isValid, $message);
    }
}