<?php

namespace Disjfa\MozaicBundle\Security;

use Disjfa\MozaicBundle\Entity\UnsplashSeason;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UnsplashSeasonVoter extends Voter
{
    public const VIEW = 'view';
    public const EDIT = 'edit';

    public function __construct(private readonly \Symfony\Bundle\SecurityBundle\Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::VIEW, self::EDIT])) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof UnsplashSeason) {
            return false;
        }

        return true;
    }

    /**
     * @param UnsplashSeason $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        switch ($attribute) {
            case self::VIEW:
                if ($subject->isPublic()) {
                    return true;
                }

                if ($user instanceof UserInterface) {
                    return $this->security->isGranted('ROLE_ADMIN', $user);
                }

                break;
            case self::EDIT:
                if ($user instanceof UserInterface) {
                    return $this->security->isGranted('ROLE_ADMIN', $user);
                }
                break;
        }

        return false;
    }
}
