<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsSiret extends Constraint
{
    public $message = 'Siret ou Siren invalide';
}
