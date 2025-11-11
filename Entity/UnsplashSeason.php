<?php

namespace Disjfa\MozaicBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: \UnsplashSeasonRepository::class)]
#[ORM\Table(name: 'unsplash_season')]
class UnsplashSeason
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    /**
     * @var string
     */
    #[ORM\Column(name: 'title', type: 'string', nullable: false)]
    private $title;

    /**
     * @var string
     */
    #[ORM\Column(name: 'description', type: 'text', nullable: true)]
    private $description;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'date_season', type: 'datetime')]
    private $dateSeason;

    /**
     * @var bool
     */
    #[ORM\Column(name: 'public', type: 'boolean')]
    private $public;

    /**
     * @var UnsplashSeasonItem[]|ArrayCollection
     */
    #[ORM\OneToMany(targetEntity: UnsplashSeasonItem::class, mappedBy: 'unsplashSeason')]
    private $items;

    public function __construct()
    {
        $this->dateSeason = new \DateTime();
        $this->public = false;
        $this->title = '';
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    public function getDateSeason(): \DateTime
    {
        return $this->dateSeason;
    }

    public function setDateSeason(\DateTime $dateSeason): void
    {
        $this->dateSeason = $dateSeason;
    }

    public function isPublic(): bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): void
    {
        $this->public = $public;
    }

    /**
     * @return UnsplashSeasonItem[]|ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }
}
