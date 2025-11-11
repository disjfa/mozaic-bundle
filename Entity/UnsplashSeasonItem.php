<?php

namespace Disjfa\MozaicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'unsplash_season_item')]
class UnsplashSeasonItem
{
    /**
     * @var string
     */
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
     * @var int
     */
    #[ORM\Column(name: 'seqnr', type: 'integer')]
    private $seqnr;

    public function __construct(#[ORM\ManyToOne(targetEntity: UnsplashSeason::class, inversedBy: 'items')]
        private readonly UnsplashSeason $unsplashSeason, #[ORM\ManyToOne(targetEntity: UnsplashPhoto::class)]
        private UnsplashPhoto $unsplashPhoto)
    {
        $this->seqnr = 50;
        $this->title = '';
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle(): string
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

    public function getSeqnr(): int
    {
        return $this->seqnr;
    }

    public function setSeqnr(int $seqnr): void
    {
        $this->seqnr = $seqnr;
    }

    /**
     * @return UnsplashPhoto
     */
    public function getUnsplashPhoto()
    {
        return $this->unsplashPhoto;
    }

    public function setUnsplashPhoto(UnsplashPhoto $unsplashPhoto): void
    {
        $this->unsplashPhoto = $unsplashPhoto;
    }

    public function getUnsplashSeason(): UnsplashSeason
    {
        return $this->unsplashSeason;
    }
}
