<?php

namespace Disjfa\MozaicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: \UserPhotoRepository::class)]
#[ORM\Table(name: 'user_photos')]
class UserPhoto
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
     * @param string $userId
     */
    public function __construct(
        #[ORM\ManyToOne(targetEntity: UnsplashPhoto::class, inversedBy: 'userPhotos')]
        private readonly UnsplashPhoto $unsplashPhoto,
        #[ORM\Column(name: 'user_id', type: 'string', nullable: true)]
        private $userId = null,
        #[ORM\Column(name: 'date_started', type: 'datetime', nullable: false)]
        private readonly ?\DateTime $dateStarted = null,
        #[ORM\Column(name: 'date_finished', type: 'datetime', nullable: false)]
        private readonly ?\DateTime $dateFinished = null,
    ) {
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return UnsplashPhoto
     */
    public function getUnsplashPhoto()
    {
        return $this->unsplashPhoto;
    }

    /**
     * @return \DateTime
     */
    public function getDateStarted()
    {
        return $this->dateStarted;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return \DateTime
     */
    public function getDateFinished()
    {
        return $this->dateFinished;
    }
}
