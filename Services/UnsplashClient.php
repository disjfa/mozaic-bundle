<?php

namespace Disjfa\MozaicBundle\Services;

use Disjfa\MozaicBundle\Entity\UnsplashPhoto;
use Disjfa\MozaicBundle\Entity\UnsplashUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\RouterInterface;
use Unsplash\HttpClient;
use Unsplash\Photo;

class UnsplashClient
{
    /**
     * @throws \Exception
     */
    public function __construct(
        #[Autowire(env: 'DISJFA_MOZAIC_APPLICATION_ID')] string $applicationId,
        #[Autowire(env: 'DISJFA_MOZAIC_SECRET')] string $secret,
        RouterInterface $router,
        private readonly EntityManagerInterface $entityManager,
    ) {
        if (empty($applicationId) || empty($secret)) {
            throw new \Exception('No "disjfa_mozaic.unsplash.application_id" or "disjfa_mozaic.unsplash.secret" set in parameters');
        }

        HttpClient::init([
            'applicationId' => $applicationId,
            'secret' => $secret,
            'callbackUrl' => $router->generate('disjfa_mozaic_unsplash_callback', [], 0),
            'utmSource' => 'dimme_mozaic',
        ]);
    }

    /**
     * @return UnsplashPhoto
     */
    public function random()
    {
        $photo = Photo::random([]);

        return $this->updateOrInsertPhoto($photo);
    }

    /**
     * @return UnsplashPhoto
     */
    public function find(string $unsplashId)
    {
        $unsplashPhoto = $this->entityManager->getRepository(UnsplashPhoto::class)->find($unsplashId);
        if ($unsplashPhoto instanceof UnsplashPhoto) {
            return $unsplashPhoto;
        }

        $photo = Photo::find($unsplashId);

        return $this->updateOrInsertPhoto($photo);
    }

    /**
     * @return UnsplashPhoto
     */
    private function updateOrInsertPhoto(Photo $photo)
    {
        $unsplashUser = $this->entityManager->getRepository(UnsplashUser::class)->find($photo->user['id']);
        if (null === $unsplashUser) {
            $unsplashUser = new UnsplashUser($photo->user);
            $this->entityManager->persist($unsplashUser);
        }

        $unsplashPhoto = $this->entityManager->getRepository(UnsplashPhoto::class)->find($photo->id);
        if (null === $unsplashPhoto) {
            $location = $photo->location ?? [];
            $unsplashPhoto = new UnsplashPhoto($unsplashUser, $photo->id, $photo->description, $photo->created_at, $photo->width, $photo->height, $photo->color, $photo->likes, $photo->urls, $photo->links, $location);
            $this->entityManager->persist($unsplashPhoto);
        }

        $this->entityManager->flush();

        return $unsplashPhoto;
    }
}
