<?php

namespace Disjfa\MozaicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/mozaic/unsplash')]
class UnsplashController extends AbstractController
{
    #[Route(path: '/callback', name: 'disjfa_mozaic_unsplash_callback')]
    public function callbackAction(Request $request)
    {
        // @todo this does nothing
        return $this->render('@DisjfaMozaic/Puzzle/index.html.twig', [
        ]);
    }
}
