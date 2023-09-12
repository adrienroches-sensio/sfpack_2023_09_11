<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use function file_exists;

final class MoviePosterExistsValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof MoviePosterExists) {
            throw new UnexpectedTypeException($constraint, MoviePosterExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $fullFilePath = __DIR__ . '/../../../assets/images/movies/' . $value;
        if (!file_exists($fullFilePath)) {
            $this->context->buildViolation($constraint->message)
                          ->setParameter('{{ filename }}', $value)
                          ->addViolation();
        }
    }
}
