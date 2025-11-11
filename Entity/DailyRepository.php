<?php

namespace Disjfa\MozaicBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

/**
 * DailyRepository.
 */
class DailyRepository extends EntityRepository
{
    /**
     * @param int $maxResults
     *
     * @return Daily[]
     */
    public function findLatest($maxResults = 16)
    {
        $qb = $this->createQueryBuilder('daily');
        $qb->orderBy('daily.dateDaily', 'DESC');
        $qb->setMaxResults($maxResults);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Daily|null
     *
     * @throws NonUniqueResultException
     */
    public function findDailyByDate(DailyDateTime $dateTime)
    {
        $qb = $this->createQueryBuilder('daily');
        $qb->where('daily.dateDaily = :date');
        $qb->setParameter('date', (string) $dateTime);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @return Daily[]
     */
    public function findByMonthAndYear(\DateTime $dateTime)
    {
        $qb = $this->createQueryBuilder('daily');
        $qb->where('daily.dateDaily > :mindate');
        $qb->andWhere('daily.dateDaily < :maxdate');

        $dateTime->modify('first day of this month');
        $dateTime->setTime(0, 0, 0);
        $qb->setParameter('mindate', $dateTime);

        $maxDate = clone $dateTime;
        $maxDate->modify('first day of next month');
        $qb->setParameter('maxdate', $maxDate);

        $qb->orderBy('daily.dateDaily', 'desc');

        return $qb->getQuery()->getResult();
    }
}
