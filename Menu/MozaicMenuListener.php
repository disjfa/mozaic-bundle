<?php

namespace Disjfa\MozaicBundle\Menu;

use Disjfa\MenuBundle\Menu\ConfigureMenuEvent;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class MozaicMenuListener
{
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        try {
            $menu = $event->getMenu();
            $mozaicMenu = $menu->addChild('Mozaic puzzle', [
                'route' => 'disjfa_mozaic_puzzle_index',
            ])->setExtra('icon', 'fa-puzzle-piece');

            $mozaicMenu->addChild('Seasons', ['route' => 'disjfa_mozaic_season_index'])->setExtra('icon', 'fa-dice');
            $mozaicMenu->addChild('Puzzles', ['route' => 'disjfa_mozaic_puzzle_index'])->setExtra('icon', 'fa-puzzle-piece');
            $mozaicMenu->addChild('Random', ['route' => 'disjfa_mozaic_puzzle_random'])->setExtra('icon', 'fa-random');
            $mozaicMenu->addChild('Daily', ['route' => 'disjfa_mozaic_puzzle_daily'])->setExtra('icon', 'fa-calendar');
        } catch (RouteNotFoundException) {
            // routing.yml not set up
            return;
        }
    }
}
