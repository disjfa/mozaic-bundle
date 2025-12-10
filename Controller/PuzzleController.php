<?php

namespace Disjfa\MozaicBundle\Controller;

use Crew\Unsplash\Exception as UnsplashException;
use Disjfa\MozaicBundle\Entity\Daily;
use Disjfa\MozaicBundle\Entity\DailyDateTime;
use Disjfa\MozaicBundle\Entity\UnsplashPhoto;
use Disjfa\MozaicBundle\Entity\UserLike;
use Disjfa\MozaicBundle\Entity\UserPhoto;
use Disjfa\MozaicBundle\Services\UnsplashClient;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use FOS\UserBundle\Model\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route(path: '/mozaic')]
class PuzzleController extends AbstractController
{
    public function __construct(private readonly UnsplashClient $unsplashClient, private readonly TranslatorInterface $translator, private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route(path: '/', name: 'disjfa_mozaic_puzzle_index')]
    public function indexAction()
    {
        return $this->render('@DisjfaMozaic/Puzzle/index.html.twig', [
            'lastPhotos' => $this->entityManager->getRepository(Daily::class)->findLatest(),
        ]);
    }

    #[Route(path: '/my-progress', name: 'disjfa_mozaic_puzzle_my_progress')]
    public function myProgressAction()
    {
        if (false === $this->getUser() instanceof User) {
            return $this->createAccessDeniedException('PLease log in');
        }

        return $this->render('@DisjfaMozaic/Puzzle/my_progress.html.twig', [
            'userPhotos' => $this->entityManager->getRepository(UserPhoto::class)->findByMyPhotos($this->getUser()->getId()),
        ]);
    }

    /**
     * @throws NonUniqueResultException
     */
    #[Route(path: '/daily', name: 'disjfa_mozaic_puzzle_daily')]
    public function daylyAction()
    {
        $today = new DailyDateTime('now');
        $daily = $this->entityManager->getRepository(Daily::class)->findDailyByDate($today);

        if (null === $daily) {
            try {
                $unsplashPhoto = $this->unsplashClient->random();
            } catch (UnsplashException) {
                $unsplashPhotos = $this->entityManager->getRepository(UnsplashPhoto::class)->findAll();
                shuffle($unsplashPhotos);
                $unsplashPhoto = current($unsplashPhotos);
            }

            $daily = new Daily($unsplashPhoto, $today);

            $this->entityManager->persist($daily);
            $this->entityManager->flush($daily);
        }

        $unsplashPhoto = $daily->getUnsplashPhoto();

        return $this->redirectToRoute('disjfa_mozaic_puzzle_photo', ['unsplashPhoto' => $unsplashPhoto->getUnsplashId()]);
    }

    #[Route(path: '/random', name: 'disjfa_mozaic_puzzle_random')]
    public function randomAction()
    {
        try {
            $unsplashPhoto = $this->unsplashClient->random();
        } catch (UnsplashException) {
            $unsplashPhotos = $this->entityManager->getRepository(UnsplashPhoto::class)->findAll();
            shuffle($unsplashPhotos);
            $unsplashPhoto = current($unsplashPhotos);
        }

        return $this->redirectToRoute('disjfa_mozaic_puzzle_photo', ['unsplashPhoto' => $unsplashPhoto->getUnsplashId()]);
    }

    /**
     * @return Response
     */
    #[Route(path: '/{unsplashPhoto}', name: 'disjfa_mozaic_puzzle_photo')]
    public function photoAction(UnsplashPhoto $unsplashPhoto)
    {
        if (null === $this->getUser()) {
            $myPhotos = [];
            $myLike = false;
        } else {
            $userId = $this->getUser()->getId();
            $myPhotos = $unsplashPhoto->getUserPhotoByUser($userId);
            $myLike = $unsplashPhoto->getLikeByUser($userId);
        }

        return $this->render('@DisjfaMozaic/Puzzle/photo.html.twig', [
            'unsplashPhoto' => $unsplashPhoto,
            'myPhotos' => $myPhotos,
            'myLike' => $myLike,
        ]);
    }

    /**
     * @return Response
     *
     * @throws NonUniqueResultException
     */
    #[Route(path: '/{unsplashPhoto}/like', name: 'disjfa_mozaic_puzzle_photo_like')]
    public function likeAction(UnsplashPhoto $unsplashPhoto, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $userId = $this->getUser()->getId();
        $userLike = $this->entityManager->getRepository(UserLike::class)->findUserLike($unsplashPhoto, $userId);
        if (null === $userLike) {
            $userLike = new UserLike($unsplashPhoto, $userId, true);
        } else {
            $userLike->setLiked(true);
        }

        $this->entityManager->persist($userLike);
        $this->entityManager->flush();

        $this->addFlash('success', $this->translator->trans('mozaic.liked.message', [], 'mozaic'));

        if ($request->headers->has('referer')) {
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->redirectToRoute('disjfa_mozaic_puzzle_photo', [
            'unsplashPhoto' => $unsplashPhoto->getUnsplashId(),
        ]);
    }

    /**
     * @return Response
     *
     * @throws NonUniqueResultException
     */
    #[Route(path: '/{unsplashPhoto}/unlike', name: 'disjfa_mozaic_puzzle_photo_unlike')]
    public function unlikeAction(UnsplashPhoto $unsplashPhoto, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $userId = $this->getUser()->getId();
        $userLike = $this->entityManager->getRepository(UserLike::class)->findUserLike($unsplashPhoto, $userId);
        if (null === $userLike) {
            $userLike = new UserLike($unsplashPhoto, $userId, false);
        } else {
            $userLike->setLiked(false);
        }

        $this->entityManager->persist($userLike);
        $this->entityManager->flush();

        $this->addFlash('success', $this->translator->trans('mozaic.unliked.message', [], 'mozaic'));

        if ($request->headers->has('referer')) {
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->redirectToRoute('disjfa_mozaic_puzzle_photo', [
            'unsplashPhoto' => $unsplashPhoto->getUnsplashId(),
        ]);
    }
}
