<?php

namespace Disjfa\MozaicBundle\Controller\Api;

use Disjfa\MozaicBundle\Entity\UnsplashPhoto;
use Disjfa\MozaicBundle\Entity\UserPhoto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route(path: '/api/mozaic')]
class PuzzleController extends AbstractController
{
    #[Route(path: '/{unsplashPhoto}', name: 'disjfa_mozaic_api_puzzle_photo')]
    public function photoAction(UnsplashPhoto $unsplashPhoto, Request $request): Response
    {
        //        $mozaicPuzzel = new MozaicPuzzle($unsplashPhoto);
        //        dump($mozaicPuzzel);
        //        exit;
        //        dump($unsplashPhoto);
        //        exit;

        $image = $unsplashPhoto->getUrlRaw();
        $width = $unsplashPhoto->getWidth();
        // $height = 3264;
        $height = $width / 16 * 9;

        $w = 1040;
        $h = $w / 16 * 9;

        $columns = [];
        $colY = 5;
        $colX = 7;

        $realWidth = floor($width / $colX);
        $realHeight = floor($height / $colY);

        $blockWidth = floor($w / $colX);
        $blockHeight = floor($h / $colY);

        for ($i = 0; $i < $colX; ++$i) {
            for ($j = 0; $j < $colY; ++$j) {
                if (isset($columns[$i][$j])) {
                    continue;
                }

                $sizeX = mt_rand(1, 3);
                $sizeX = $i + $sizeX > $colX ? $colX - $i : $sizeX;
                $maxX = $sizeX;
                $sizeY = mt_rand(1, 3);
                $sizeY = $j + $sizeY > $colY ? $colY - $j : $sizeY;
                $maxY = $sizeY;

                for ($mx = $i; $mx < $i + $sizeX; ++$mx) {
                    if (isset($columns[$mx])) {
                        $maxX = $mx - $i < $maxX ? $mx - $i + 1 : $maxX;
                    }
                    for ($my = $j; $my < $j + $sizeY; ++$my) {
                        if (isset($columns[$mx][$my])) {
                            $maxY = $my - $j < $maxY ? $my - $j + 1 : $maxY;
                        }
                    }
                }
                $sizeX = $maxX;
                $sizeY = $maxY;
                for ($mx = $i; $mx < $i + $sizeX; ++$mx) {
                    for ($my = $j; $my < $j + $sizeY; ++$my) {
                        $columns[$mx][$my] = false;
                    }
                }

                $iWidth = floor($blockWidth * $sizeX);
                $iHeight = floor($blockHeight * $sizeY);

                $params = [
                    'w' => $iWidth,
                    'h' => $iHeight,
                    'rect' => implode(',', [
                        $i * $realWidth,
                        $j * $realHeight,
                        $realWidth * $sizeX,
                        $realHeight * $sizeY,
                    ]),
                ];

                $columns[$i][$j] = [
                    'image' => $image.'?'.http_build_query($params, '&amp;'),
                    'x' => $i,
                    'y' => $j,
                    'styles' => [
                        'left' => $i * $blockWidth,
                        'top' => $j * $blockHeight,
                        'width' => $iWidth,
                        'height' => $iHeight,
                        'widthPercent' => $iWidth / $w * 100,
                        'heightPercent' => $iHeight / $h * 100,
                    ],
                    'percent' => [
                        'left' => $i * $blockWidth / $w * 100,
                        'top' => $j * $blockHeight / $h * 100,
                        'width' => $iWidth / $w * 100,
                        'height' => $iHeight / $h * 100,
                    ],
                ];
            }
            ksort($columns[$i]);
        }
        ksort($columns);

        $mozaic = [];
        foreach ($columns as $row) {
            foreach ($row as $item) {
                if (false === $item) {
                    continue;
                }
                $mozaic[] = $item;
            }
        }
        $now = new \DateTime('now');
        $dates = [
            (string) $unsplashPhoto->getId() => $now->format('r'),
        ];

        $session = $request->getSession();
        $session->set('dates', $dates);
        $session->save();

        return new JsonResponse(['mozaic' => $mozaic]);
    }

    #[Route(path: '/{unsplashPhoto}/finish', name: 'disjfa_mozaic_api_puzzle_finish', methods: ['POST'])]
    public function finishAction(UnsplashPhoto $unsplashPhoto, Request $request, EntityManagerInterface $entityManager): Response
    {
        $requestData = json_decode($request->getContent(), true);
        if (false === is_array($requestData)) {
            throw new BadRequestHttpException('No array found');
        }

        if (false === array_key_exists('token', $requestData)) {
            throw new BadRequestHttpException('No token found');
        }

        if (false === $this->isCsrfTokenValid($unsplashPhoto->getId(), $requestData['token'])) {
            throw new BadRequestHttpException('Bad token found');
        }

        $user = $this->getUser();
        if ($user instanceof UserInterface) {
            $userId = $user->getId();
        } else {
            $userId = null;
        }

        $dateFinished = new \DateTime('now');
        $session = $dates = $request->getSession();
        $dates = $session->get('dates', []);

        if (isset($dates[(string) $unsplashPhoto->getId()])) {
            $dateStarted = new \DateTime($dates[(string) $unsplashPhoto->getId()]);
        } else {
            $dateStarted = $dateFinished;
        }

        $userPhoto = new UserPhoto($unsplashPhoto, $userId, $dateStarted, $dateFinished);
        $entityManager->persist($userPhoto);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'saved',
        ]);
    }
}
