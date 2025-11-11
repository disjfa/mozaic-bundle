<?php

namespace Disjfa\MozaicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: \UnsplashUserRepository::class)]
#[ORM\Table(name: 'unsplash_users')]
class UnsplashUser
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    /**
     * @var string
     */
    #[ORM\Column(name: 'unsplash_id', type: 'string', nullable: false, unique: true)]
    private $unsplashId;

    /**
     * @var string
     */
    #[ORM\Column(name: 'username', type: 'string')]
    private $username;

    /**
     * @var string
     */
    #[ORM\Column(name: 'name', type: 'string')]
    private $name;

    /**
     * @var string
     */
    #[ORM\Column(name: 'first_name', type: 'string', nullable: true)]
    private $firstName;

    /**
     * @var string
     */
    #[ORM\Column(name: 'last_name', type: 'string', nullable: true)]
    private $lastName;

    /**
     * @var string
     */
    #[ORM\Column(name: 'portfolio_url', type: 'string', nullable: true)]
    private $portfolioUrl;

    /**
     * @var string
     */
    #[ORM\Column(name: 'bio', type: 'text', nullable: true)]
    private $bio;

    /**
     * @var string
     */
    #[ORM\Column(name: 'location', type: 'string', nullable: true)]
    private $location;

    /**
     * @var int
     */
    #[ORM\Column(name: 'total_likes', type: 'integer', nullable: true)]
    private $totalLikes;

    /**
     * @var int
     */
    #[ORM\Column(name: 'total_photos', type: 'integer', nullable: true)]
    private $totalPhotos;

    /**
     * @var int
     */
    #[ORM\Column(name: 'total_collections', type: 'integer', nullable: true)]
    private $totalCollections;

    /**
     * @var string
     */
    #[ORM\Column(name: 'profile_image', type: 'string', nullable: true)]
    private $profileImage;

    /**
     * @var string
     */
    #[ORM\Column(name: 'link_html', type: 'string', nullable: true)]
    private $linkHtml;

    public function __construct(array $user)
    {
        if (array_key_exists('id', $user)) {
            $this->unsplashId = $user['id'];
        }
        if (array_key_exists('username', $user)) {
            $this->username = $user['username'];
        }
        if (array_key_exists('name', $user)) {
            $this->name = $user['name'];
        }
        if (array_key_exists('first_name', $user)) {
            $this->firstName = $user['first_name'];
        }
        if (array_key_exists('last_name', $user)) {
            $this->lastName = $user['last_name'];
        }
        if (array_key_exists('portfolio_url', $user)) {
            $this->portfolioUrl = $user['portfolio_url'];
        }
        if (array_key_exists('bio', $user)) {
            $this->bio = $user['bio'];
        }
        if (array_key_exists('location', $user)) {
            $this->location = $user['location'];
        }
        if (array_key_exists('total_likes', $user)) {
            $this->totalLikes = $user['total_likes'];
        }
        if (array_key_exists('total_photos', $user)) {
            $this->totalPhotos = $user['total_photos'];
        }
        if (array_key_exists('total_collections', $user)) {
            $this->totalCollections = $user['total_collections'];
        }
        if (array_key_exists('profile_image', $user) && array_key_exists('large', $user['profile_image'])) {
            $this->profileImage = $user['profile_image']['large'];
        }
        if (array_key_exists('links', $user) && array_key_exists('html', $user['links'])) {
            $this->linkHtml = $user['links']['html'];
        }
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUnsplashId()
    {
        return $this->unsplashId;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getPortfolioUrl()
    {
        return $this->portfolioUrl;
    }

    /**
     * @return string
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return int
     */
    public function getTotalLikes()
    {
        return $this->totalLikes;
    }

    /**
     * @return int
     */
    public function getTotalPhotos()
    {
        return $this->totalPhotos;
    }

    /**
     * @return int
     */
    public function getTotalCollections()
    {
        return $this->totalCollections;
    }

    /**
     * @return string
     */
    public function getProfileImage()
    {
        return $this->profileImage;
    }

    /**
     * @return string
     */
    public function getLinkHtml()
    {
        return $this->linkHtml;
    }
}
