<?php

namespace Disjfa\MozaicBundle\Entity;

/**
 * Class DailyDateTime.
 */
class DailyDateTime extends \DateTime implements \Stringable
{
    public function __toString(): string
    {
        return $this->format('Y-m-d');
    }
}
