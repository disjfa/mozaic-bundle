<?php

namespace Disjfa\MozaicBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

class UserLikeRepository extends EntityRepository
{
    /**
     * @throws NonUniqueResultException
     */
    public function findUserLike(UnsplashPhoto $unsplashPhoto, int $userId)
    {
        return $this->createQueryBuilder('userLike')
            ->andWhere('userLike.unsplashPhoto = :unsplashPhoto')
            ->andWhere('userLike.userId = :userId')
            ->setParameter('unsplashPhoto', $unsplashPhoto)
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
