<?php

namespace App\Validator;

use App\Validator\ContainsSiret;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ContainsSiretValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ContainsSiret) {
            throw new UnexpectedTypeException($constraint, ContainsSiret::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) to take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'string');

            // separate multiple types using pipes
            // throw new UnexpectedValueException($value, 'string|int');
        }
        $siret = trim($value);
        $siret = str_replace(' ', '', $siret);
        if (empty($siret) || strlen($siret) != 14) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
        $sum = 0;
        for ($i = 0; $i < 14; $i++) {
            if ($i % 2 == 0) {
                $tmp = $siret[$i] * 2;
                $tmp = $tmp > 9 ? $tmp - 9 : $tmp;
            } else {
                $tmp = $siret[$i];
            }
            $sum += $tmp;
        }
        if ($sum % 10 !== 0) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}
