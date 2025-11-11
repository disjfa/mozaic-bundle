<?php

namespace Disjfa\MozaicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \DailyRepository::class)]
#[ORM\Table(name: 'mozaic_daily')]
class Daily
{
    #[ORM\Id]
    #[ORM\Column(name: 'date_daily', type: 'string', nullable: false)]
    private $dateDaily;

    public function __construct(#[ORM\ManyToOne(targetEntity: UnsplashPhoto::class)]
        private readonly UnsplashPhoto $unsplashPhoto, DailyDateTime $dateDaily)
    {
        $this->dateDaily = (string) $dateDaily;
    }

    public function getDateDaily()
    {
        return $this->dateDaily;
    }

    /**
     * @return UnsplashPhoto
     */
    public function getUnsplashPhoto()
    {
        return $this->unsplashPhoto;
    }
}
