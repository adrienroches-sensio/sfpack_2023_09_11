<?php

declare(strict_types=1);

namespace App\Security\Voter\Movie;

use App\Entity\User;
use App\Model\Movie;
use App\Model\Security;
use Psr\Clock\ClockInterface;
use Symfony\Bundle\SecurityBundle\Security as SecurityUser;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class MinAgeRequiredVoter extends Voter
{
    public function __construct(
        private readonly ClockInterface $clock,
        private readonly SecurityUser $security,
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return Security::MOVIE_VIEW_DETAILS === $attribute && $subject instanceof Movie;
    }

    /**
     * @param Movie $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return $user->isOlderThanOrEqual($subject->rated->minAgeRequired(), $this->clock->now());
    }
}
