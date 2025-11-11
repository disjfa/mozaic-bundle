<?php

namespace Disjfa\MozaicBundle\Menu;

use Disjfa\MenuBundle\Menu\ConfigureMenuEvent;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class MozaicAdminMenuListener
{
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        try {
            $menu = $event->getMenu();
            $mozaicMenu = $menu->addChild('mozaic', [
                'label' => 'Mozaic puzzle',
                'route' => 'disjfa_mozaic_puzzle_index',
            ])->setExtra('icon', 'fa-puzzle-piece');

            $mozaicMenu->addChild('Seasons', ['route' => 'disjfa_mozaic_admin_season_index'])->setExtra('icon', 'fa-dice');
            $mozaicMenu->addChild('Daily', ['route' => 'disjfa_mozaic_admin_mozaic_daily'])->setExtra('icon', 'fa-calendar');
            $mozaicMenu->addChild('All photos', ['route' => 'disjfa_mozaic_admin_mozaic_photos'])->setExtra('icon', 'fa-image');
            $mozaicMenu->addChild('Search', ['route' => 'disjfa_mozaic_admin_mozaic_search'])->setExtra('icon', 'fa-search');
        } catch (RouteNotFoundException) {
            // routing.yml not set up
            return;
        }
    }
}
