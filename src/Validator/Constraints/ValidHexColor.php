<?php

namespace PrestaShop\Module\ProductLabel\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ValidHexColor extends Constraint
{
    public $message = 'The color "{{ value }}" is not a valid hex color (e.g., #FF0000).';

}
