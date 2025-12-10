<?php

namespace Disjfa\MozaicBundle\Controller;

use Disjfa\MozaicBundle\Entity\UnsplashSeason;
use Disjfa\MozaicBundle\Entity\UnsplashSeasonItem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/mozaic-season')]
class SeasonController extends AbstractController
{
    public function __construct(private readonly \Doctrine\Persistence\ManagerRegistry $managerRegistry)
    {
    }
    /**
     * @return Response
     */
    #[Route(path: '', name: 'disjfa_mozaic_season_index')]
    public function indexAction()
    {
        return $this->render('@DisjfaMozaic/Season/index.html.twig', [
            'seasons' => $this->managerRegistry->getRepository(UnsplashSeason::class)->findPublicSeasons(),
        ]);
    }

    /**
     * @return Response
     */
    #[Route(path: '/{unsplashSeason}', name: 'disjfa_mozaic_season_show')]
    public function showAction(UnsplashSeason $unsplashSeason)
    {
        $this->denyAccessUnlessGranted('view', $unsplashSeason);

        return $this->render('@DisjfaMozaic/Season/show.html.twig', [
            'unsplashSeason' => $unsplashSeason,
        ]);
    }

    /**
     * @return Response
     */
    #[Route(path: '/{unsplashSeason}/{unsplashSeasonItem}', name: 'disjfa_mozaic_season_item')]
    public function itemAction(UnsplashSeason $unsplashSeason, UnsplashSeasonItem $unsplashSeasonItem)
    {
        $unsplashPhoto = $unsplashSeasonItem->getUnsplashPhoto();
        if (null === $this->getUser()) {
            $myPhotos = [];
            $myLike = false;
        } else {
            $userId = $this->getUser()->getId();
            $myPhotos = $unsplashPhoto->getUserPhotoByUser($userId);
            $myLike = $unsplashPhoto->getLikeByUser($userId);
        }

        return $this->render('@DisjfaMozaic/Puzzle/photo.html.twig', [
            'unsplashSeason' => $unsplashSeason,
            'unsplashSeasonItem' => $unsplashSeasonItem,
            'unsplashPhoto' => $unsplashPhoto,
            'myPhotos' => $myPhotos,
            'myLike' => $myLike,
        ]);
    }
}
