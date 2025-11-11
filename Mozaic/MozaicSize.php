<?php

namespace Disjfa\MozaicBundle\Mozaic;

class MozaicSize
{
    /**
     * @var int
     */
    private $height;

    /**
     * @param int $width
     */
    public function __construct(private $width, $height = null)
    {
        if (null === $height) {
            $this->height = $this->width / 16 * 9;
        } else {
            $this->height = $height;
        }
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }
}
