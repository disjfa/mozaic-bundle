<?php

namespace Disjfa\MozaicBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: \UnsplashPhotoRepository::class)]
#[ORM\Table(name: 'unsplash_images')]
class UnsplashPhoto implements \Stringable
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false)]
    private $createdAt;

    /**
     * @var string
     */
    #[ORM\Column(name: 'url_raw', type: 'string')]
    private $urlRaw;

    /**
     * @var string
     */
    #[ORM\Column(name: 'url_regular', type: 'string')]
    private $urlRegular;

    /**
     * @var string
     */
    #[ORM\Column(name: 'title', type: 'string', nullable: true)]
    private $title;

    /**
     * @var string
     */
    #[ORM\Column(name: 'description', type: 'text', nullable: true)]
    private $description;

    /**
     * @var string
     */
    #[ORM\Column(name: 'name', type: 'string', nullable: true)]
    private $name;

    /**
     * @var string
     */
    #[ORM\Column(name: 'city', type: 'string', nullable: true)]
    private $city;

    /**
     * @var string
     */
    #[ORM\Column(name: 'country', type: 'string', nullable: true)]
    private $country;

    /**
     * @var float
     */
    #[ORM\Column(name: 'latitude', type: 'float', nullable: true)]
    private $latitude;

    /**
     * @var float
     */
    #[ORM\Column(name: 'longitude', type: 'float', nullable: true)]
    private $longitude;

    /**
     * @var string
     */
    #[ORM\Column(name: 'link_html', type: 'string')]
    private $linkHtml;

    /**
     * @var UserPhoto[]|ArrayCollection
     */
    #[ORM\OneToMany(targetEntity: UserPhoto::class, mappedBy: 'unsplashPhoto')]
    private $userPhotos;

    /**
     * @var UserLike[]|ArrayCollection
     */
    #[ORM\OneToMany(targetEntity: UserLike::class, mappedBy: 'unsplashPhoto')]
    private $userLikes;

    /**
     * UnsplashPhoto constructor.
     *
     * @param string $unsplashId
     * @param int    $width
     * @param int    $height
     * @param string $color
     * @param int    $likes
     */
    public function __construct(#[ORM\ManyToOne(targetEntity: UnsplashUser::class)]
        private readonly UnsplashUser $unsplashUser, #[ORM\Column(name: 'unsplash_id', type: 'string', nullable: false, unique: true)]
        private $unsplashId, $description, $createdAt, #[ORM\Column(name: 'width', type: 'integer', nullable: false)]
        private $width, #[ORM\Column(name: 'height', type: 'integer', nullable: false)]
        private $height, #[ORM\Column(name: 'color', type: 'string', nullable: false)]
        private $color, #[ORM\Column(name: 'likes', type: 'integer')]
        private $likes, array $urls, array $links, array $location)
    {
        $this->createdAt = new \DateTime($createdAt);

        if (array_key_exists('raw', $urls)) {
            $this->urlRaw = $urls['raw'];
        }
        if (array_key_exists('regular', $urls)) {
            $this->urlRegular = $urls['regular'];
        }
        if (array_key_exists('html', $links)) {
            $this->linkHtml = $links['html'];
        }

        if (array_key_exists('title', $location)) {
            $this->title = $location['title'];
        }
        if (array_key_exists('name', $location)) {
            $this->name = $location['name'];
        }
        if (array_key_exists('city', $location)) {
            $this->city = $location['city'];
        }
        if (array_key_exists('country', $location)) {
            $this->country = $location['country'];
        }
        if (array_key_exists('position', $location)) {
            $this->latitude = $location['position']['latitude'];
            $this->longitude = $location['position']['longitude'];
        }
    }

    public function __toString(): string
    {
        return $this->unsplashId.' - '.$this->description.$this->unsplashUser->getName();
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
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @return int
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @return string
     */
    public function getUrlRaw()
    {
        return $this->urlRaw;
    }

    /**
     * @return string
     */
    public function getUrlRegular()
    {
        return $this->urlRegular;
    }

    /**
     * @return string
     */
    public function getLinkHtml()
    {
        return $this->linkHtml;
    }

    /**
     * @return UnsplashUser
     */
    public function getUnsplashUser()
    {
        return $this->unsplashUser;
    }

    /**
     * @return UserPhoto[]
     */
    public function getUserPhotos()
    {
        return $this->userPhotos;
    }

    /**
     * @return UserPhoto[]
     */
    public function getUserPhotoByUser(int $userId)
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('userId', $userId));

        return $this->userPhotos->matching($criteria);
    }

    /**
     * @return UserLike
     */
    public function getLikeByUser(int $userId)
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('userId', $userId));
        $criteria->setFirstResult(1);

        return $this->userLikes->matching($criteria)->current();
    }

    public function getLikeCount()
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('liked', true));

        return $this->userLikes->matching($criteria)->count();
    }

    public function getUnlikeCount()
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('liked', false));

        return $this->userLikes->matching($criteria)->count();
    }
}
