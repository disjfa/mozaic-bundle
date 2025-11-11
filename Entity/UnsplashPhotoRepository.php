<?php

namespace Disjfa\MozaicBundle\Entity;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;

class UnsplashPhotoRepository extends EntityRepository
{
    /**
     * @param string $id
     * @param null   $lockMode
     * @param null   $lockVersion
     */
    public function find(mixed $id, LockMode|int|null $lockMode = null, ?int $lockVersion = null): ?object
    {
        return $this->findOneBy(['unsplashId' => $id]);
    }

    public function findAllPaginated(PaginatorInterface $paginator, $page = 1, $limit = 12)
    {
        $qb = $this->createQueryBuilder('u');

        return $paginator->paginate($qb, $page, $limit);
    }
}
