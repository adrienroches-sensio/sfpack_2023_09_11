<?php

declare(strict_types=1);

namespace App\Security\Voter\Movie;

use App\Model\Movie;
use App\Model\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use function in_array;

final class NoAgeRequirementVoter implements VoterInterface
{
    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        if (!$subject instanceof Movie) {
            return self::ACCESS_ABSTAIN;
        }

        if (!in_array(Security::MOVIE_VIEW_DETAILS, $attributes, true)) {
            return self::ACCESS_ABSTAIN;
        }

        return $subject->rated->minAgeRequired() === 0 ? self::ACCESS_GRANTED : self::ACCESS_ABSTAIN;
    }
}
