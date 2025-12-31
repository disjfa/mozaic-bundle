<?php

namespace Disjfa\MozaicBundle\Twig;

use Disjfa\MozaicBundle\Entity\UnsplashPhoto;

class UnsplashExtension
{
    #[\Twig\Attribute\AsTwigFilter(name: 'unsplash_photo_block')]
    public function unsplashPhotoBlock(UnsplashPhoto $unsplashPhoto): string
    {
        $outputWidth = 600;
        $outputHeight = $outputWidth / 16 * 9;

        $realWidth = $unsplashPhoto->getWidth();
        $realHeight = $unsplashPhoto->getHeight();

        $blockX = $realWidth / 8;
        $blockY = $realHeight / 8;

        $widthX = $blockX;
        $widthY = $blockX * 16 / 9;

        $params = [
            'w' => $outputWidth,
            'h' => $outputHeight,
            'rect' => implode(',', [
                (int) ($blockX * 5),
                (int) ($blockY * 3),
                (int) $widthY,
                (int) $widthX,
            ]),
        ];

        return $unsplashPhoto->getUrlRaw().'?'.http_build_query($params, '&amp;');
    }
}
