<?php

namespace Disjfa\MozaicBundle\Mozaic;

use Disjfa\MozaicBundle\Entity\UnsplashPhoto;

class MozaicPuzzle
{
    /**
     * @var UnsplashPhoto
     */
    private $unsplashPhoto;
    /**
     * @var int
     */
    private $imageWidth;
    /**
     * @var int
     */
    private $imageHeight;
    /**
     * @var string
     */
    private $imageUrl;
    /**
     * @var MozaicBlock[]
     */
    private $blocks;

    /**
     * MozaicPuzzle constructor.
     */
    public function __construct(UnsplashPhoto $unsplashPhoto)
    {
        $this->imageUrl = $unsplashPhoto->getUrlRaw();
        $imageSize = new MozaicSize($unsplashPhoto->getWidth());
        $resize = new MozaicSize(1040);

        $columns = [];
        $colY = 6;
        $colX = 5;

        $realBlockSize = new MozaicSize($imageSize->getWidth() / $colX, $imageSize->getHeight() / $colY);
        $blockSize = new MozaicSize($resize->getWidth() / $colX, $resize->getHeight() / $colY);

        $this->blocks = [];

        for ($i = 0; $i < $colX; ++$i) {
            for ($j = 0; $j < $colY; ++$j) {
                if ($this->containsXAndY($i, $j)) {
                    continue;
                }

                $sizeX = mt_rand(1, 3);
                $sizeX = $i + $sizeX > $colX ? $colX - $i : $sizeX;
                $maxX = $sizeX;
                $sizeY = mt_rand(1, 3);
                $sizeY = $j + $sizeY > $colY ? $colY - $j : $sizeY;
                $maxY = $sizeY;

                for ($mx = $i; $mx < $i + $sizeX; ++$mx) {
                    if ($this->containsXAndY($mx, $j)) {
                        $maxX = $mx - $i < $maxX ? $mx - $i + 1 : $maxX;
                    }
                    for ($my = $j; $my < $j + $sizeY; ++$my) {
                        if ($this->containsXAndY($mx, $my)) {
                            $maxY = $my - $j < $maxY ? $my - $j + 1 : $maxY;
                        }
                    }
                }
                $sizeX = $maxX;
                $sizeY = $maxY;
                for ($mx = $i; $mx < $i + $sizeX; ++$mx) {
                    for ($my = $j; $my < $j + $sizeY; ++$my) {
                        $this->blocks[] = new MozaicBlock($mx, $my);
                    }
                }

                $iWidth = floor($blockSize->getWidth() * $sizeX);
                $iHeight = floor($blockSize->getHeight() * $sizeY);

                $params = [
                    'w' => $iWidth,
                    'h' => $iHeight,
                    'rect' => implode(',', [
                        $i * $realBlockSize->getWidth(),
                        $j * $realBlockSize->getHeight(),
                        $realBlockSize->getWidth() * $sizeX,
                        $realBlockSize->getHeight() * $sizeY,
                    ]),
                ];

                $this->blocks[] = new MozaicBlock($i, $j);

                $columns[$i][$j] = [
                    'image' => $image.'?'.http_build_query($params, '&amp;'),
                    'styles' => [
                        'left' => $i * $blockWidth,
                        'top' => $j * $blockHeight,
                        'width' => $iWidth,
                        'height' => $iHeight,
                        'x' => $sizeX,
                        'y' => $sizeY,
                    ],
                ];
            }
            ksort($columns[$i]);
        }
        ksort($columns);

        dump($unsplashPhoto);
        exit;

        $this->unsplashPhoto = $unsplashPhoto;
    }

    public function containsXAndY($x, $y)
    {
        foreach ($this->blocks as $block) {
            if ($x === $block->getPosX() && $y === $block->getPosY()) {
                return true;
            }
        }

        return false;
    }
}
