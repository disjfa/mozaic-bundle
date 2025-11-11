<?php

namespace Disjfa\MozaicBundle\Controller\Admin;

use Disjfa\MozaicBundle\Entity\UnsplashSeasonItem;
use Disjfa\MozaicBundle\Form\Type\AdminSeasonItemType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route(path: '/admin/mozaic_season/items')]
class SeasonItemController extends AbstractController
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    /**
     * @return Response
     */
    #[Route(path: '/{unsplashSeasonItem}/edit', name: 'disjfa_mozaic_admin_season_item_edit')]
    public function editAction(Request $request, UnsplashSeasonItem $unsplashSeasonItem)
    {
        return $this->handleForm($request, $unsplashSeasonItem);
    }

    /**
     * @return Response
     */
    private function handleForm(Request $request, UnsplashSeasonItem $unsplashSeasonItem)
    {
        $form = $this->createForm(AdminSeasonItemType::class, $unsplashSeasonItem);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($unsplashSeasonItem);
            $entityManager->flush();

            $this->addFlash('success', $this->translator->trans('admin.flash.season_item_saved', [], 'mozaic'));

            return $this->redirectToRoute('disjfa_mozaic_admin_season_show', [
                'unsplashSeason' => $unsplashSeasonItem->getUnsplashSeason()->getId(),
            ]);
        }

        return $this->render('@DisjfaMozaic/Admin/SeasonItem/form.html.twig', [
            'form' => $form->createView(),
            'unsplashSeasonItem' => $unsplashSeasonItem,
        ]);
    }
}
