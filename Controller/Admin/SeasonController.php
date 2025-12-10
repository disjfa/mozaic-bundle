<?php

namespace Disjfa\MozaicBundle\Controller\Admin;

use Disjfa\MozaicBundle\Entity\UnsplashSeason;
use Disjfa\MozaicBundle\Entity\UnsplashSeasonItem;
use Disjfa\MozaicBundle\Form\Type\AdminSeasonType;
use Disjfa\MozaicBundle\Form\Type\SearchType;
use Disjfa\MozaicBundle\Services\UnsplashClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route(path: '/admin/mozaic_season')]
class SeasonController extends AbstractController
{
    public function __construct(private readonly TranslatorInterface $translator, private readonly UnsplashClient $unsplashClient, private readonly \Doctrine\Persistence\ManagerRegistry $managerRegistry)
    {
    }

    #[Route(path: '', name: 'disjfa_mozaic_admin_season_index')]
    public function indexAction()
    {
        return $this->render('@DisjfaMozaic/Admin/Season/index.html.twig', [
            'seasons' => $this->managerRegistry->getRepository(UnsplashSeason::class)->findBy([], ['dateSeason' => 'DESC']),
        ]);
    }

    /**
     * @return Response
     */
    #[Route(path: '/{unsplashSeason}/show', name: 'disjfa_mozaic_admin_season_show')]
    public function showAction(Request $request, UnsplashSeason $unsplashSeason)
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $unsplashId = $form->get('unsplashId')->getData();

            try {
                $unsplashPhoto = $this->unsplashClient->find($unsplashId);
                $unsplashSeasonItem = new UnsplashSeasonItem($unsplashSeason, $unsplashPhoto);

                $this->managerRegistry->getManager()->persist($unsplashSeasonItem);
                $this->managerRegistry->getManager()->flush();

                $this->addFlash('success', 'Photo added');

                return $this->redirectToRoute('disjfa_mozaic_admin_season_item_edit', [
                    'unsplashSeasonItem' => $unsplashSeasonItem->getId(),
                ]);
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
            }
        }

        return $this->render('@DisjfaMozaic/Admin/Season/show.html.twig', [
            'unsplashSeason' => $unsplashSeason,
            'form' => $form,
        ]);
    }

    /**
     * @return Response
     */
    #[Route(path: '/{unsplashSeason}/edit', name: 'disjfa_mozaic_admin_season_edit')]
    public function editAction(Request $request, UnsplashSeason $unsplashSeason)
    {
        return $this->handleForm($request, $unsplashSeason);
    }

    #[Route(path: '/create', name: 'disjfa_mozaic_admin_season_create')]
    public function createAction(Request $request)
    {
        $unsplashSeason = new UnsplashSeason();

        return $this->handleForm($request, $unsplashSeason);
    }

    /**
     * @return Response
     */
    private function handleForm(Request $request, UnsplashSeason $unsplashSeason)
    {
        $form = $this->createForm(AdminSeasonType::class, $unsplashSeason);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->managerRegistry->getManager();

            $entityManager->persist($unsplashSeason);
            $entityManager->flush();

            $this->addFlash('success', $this->translator->trans('admin.flash.season_saved', [], 'mozaic'));

            return $this->redirectToRoute('disjfa_mozaic_admin_season_show', [
                'unsplashSeason' => $unsplashSeason->getId(),
            ]);
        }

        return $this->render('@DisjfaMozaic/Admin/Season/form.html.twig', [
            'form' => $form,
        ]);
    }
}
