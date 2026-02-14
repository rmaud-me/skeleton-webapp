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
            self::getChartjsMenu(),
            self::getPaginationMenu(),
            self::getComponentsMenu(),
            self::getSecurityMenu(),
            self::getSubmenuMenu(),
        ];
    }

    private static function getComponentsMenu(): array
    {
        return [
            "name" => "Components",
            "isTitle" => false,
            "url" => 'components',
            'pictureType' => self::PICTURE_TYPE_ICON,
            "icon" => "collection-fill",
            "subMenu" => [],
        ];
    }

    private static function getChartjsMenu(): array
    {
        return [
            "name" => "Chartjs",
            "isTitle" => false,
            "url" => "app_chartjs",
            'pictureType' => self::PICTURE_TYPE_ICON,
            "icon" => "file-earmark-spreadsheet-fill"
        ];
    }

    private static function getSubmenuMenu(): array
    {
        return [
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
        ];
    }

    private static function getPaginationMenu(): array
    {
        return [
            "name" => "Pagination",
            "isTitle" => false,
            "url" => "app_paginator",
            'pictureType' => self::PICTURE_TYPE_ICON,
            "icon" => "file-earmark-spreadsheet-fill"
        ];
    }

    private static function getSecurityMenu(): array
    {
        return [
            "name" => "Security",
            "isTitle" => false,
            "url" => null,
            'pictureType' => self::PICTURE_TYPE_ICON,
            "icon" => "person-badge-fill",
            "subMenu" => [
                [
                    "name" => "Login",
                    "url" => "security_login",
                    "subMenu" => []
                ],
                [
                    "name" => "Register",
                    "url" => "security_register",
                    "subMenu" => []
                ],
                [
                    "name" => "Forgot Password",
                    "url" => "security_forgot_password",
                    "subMenu" => []
                ],
            ],
        ];
    }
}
