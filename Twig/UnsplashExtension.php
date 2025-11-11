<?php

namespace Disjfa\MozaicBundle\Twig;

use Disjfa\MozaicBundle\Entity\UnsplashPhoto;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UnsplashExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('unsplash_photo_block', $this->unsplashPhotoBlock(...)),
        ];
    }

    public function unsplashPhotoBlock(UnsplashPhoto $unsplashPhoto)
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

    public function getName()
    {
        return 'unsplash_extension';
    }
}
