<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class BanWord extends Constraint
{
    public function __construct(
                                public string $message = 'The  contains a banned word "{{ banWord }}".', 
                                public array $banWords = ['spam', 'merde', 'con'],
                                ?array $groups = null,
                                mixed $payload = null
                                )
    {
        parent::__construct(null, $groups, $payload);
    }
    
}
