<?php

namespace Disjfa\MozaicBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Security\Core\User\UserInterface;

class UserLikeRepository extends EntityRepository
{
    /**
     * @throws NonUniqueResultException
     */
    public function findUserLike(UnsplashPhoto $unsplashPhoto, UserInterface $user)
    {
        return $this->createQueryBuilder('userLike')
            ->andWhere('userLike.unsplashPhoto = :unsplashPhoto')
            ->andWhere('userLike.userId = :userId')
            ->setParameter('unsplashPhoto', $unsplashPhoto->getId(), UuidType::NAME)
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getOneOrNullResult();
    }
}
