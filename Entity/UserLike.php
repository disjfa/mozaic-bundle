<?php

namespace Disjfa\MozaicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: \UserLikeRepository::class)]
#[ORM\Table(name: 'unsplash_user_likes')]
#[UniqueConstraint(name: 'user_like', columns: ['unsplash_photo_id', 'user_id'])]
class UserLike
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
        #[ORM\ManyToOne(targetEntity: UnsplashPhoto::class, inversedBy: 'userLikes')]
        private readonly UnsplashPhoto $unsplashPhoto,
        #[ORM\Column(name: 'user_id', type: 'string', nullable: true)]
        private $userId,
        #[ORM\Column(name: 'liked', type: 'boolean')]
        private bool $liked,
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
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    public function isLiked(): bool
    {
        return $this->liked;
    }

    public function setLiked(bool $liked): void
    {
        $this->liked = $liked;
    }
}
