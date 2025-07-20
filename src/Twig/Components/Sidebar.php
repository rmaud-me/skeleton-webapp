<?php
declare(strict_types=1);

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/sidebar.html.twig')]
class Sidebar
{
    public const PICTURE_TYPE_EMOJI = 'emoji';
    public const PICTURE_TYPE_ICON  = 'icon';

    public function getItems(): array
    {
        return [
            [
                "name" => "Menu",
                "isTitle" => true,
            ],
            [
                "name" => "Charjs",
                "isTitle" => false,
                "url" => "app_chartjs",
                'pictureType' => self::PICTURE_TYPE_ICON,
                "icon" => "file-earmark-spreadsheet-fill"
            ],
            [
                "name" => "Pagination",
                "isTitle" => false,
                "url" => "app_paginator",
                'pictureType' => self::PICTURE_TYPE_ICON,
                "icon" => "file-earmark-spreadsheet-fill"
            ],
            [
                "name" => "Sub Menu",
                "isTitle" => false,
                "url" => null,
                'pictureType' => self::PICTURE_TYPE_ICON,
                "icon" => "stack",
                "subMenu" => [
                    [
                        "name" => "Sub Menu 1",
                        "url" => "submenu_1",
                        "subMenu" => []
                    ],
                    [
                        "name" => "Sub Menu 2",
                        "url" => "submenu_2",
                        "subMenu" => []
                    ],
                ],
            ],
        ];
    }
}
