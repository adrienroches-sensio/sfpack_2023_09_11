<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use App\Entity\Movie;
use Attribute;
use Symfony\Component\Validator\Constraints\Compound;
use Symfony\Component\Validator\Constraints\Regex;

#[Attribute(Attribute::TARGET_METHOD, Attribute::TARGET_PROPERTY)]
final class MovieSlugFormat extends Compound
{
    protected function getConstraints(array $options): array
    {
        return [
            new Regex('#' . Movie::SLUG_FORMAT . '#'),
        ];
    }
}
