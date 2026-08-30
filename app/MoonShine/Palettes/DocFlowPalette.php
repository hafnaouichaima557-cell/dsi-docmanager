<?php

declare(strict_types=1);

namespace App\MoonShine\Palettes;

use MoonShine\Contracts\ColorManager\PaletteContract;

final class DocFlowPalette implements PaletteContract
{
    public function getDescription(): string
    {
        return 'Doc Flow / icosnet Blue';
    }

    public function getColors(): array
    {
        return [
            'body' => '0.98 0.01 245',

            'primary' => '0.36 0.18 258',
            'primary-text' => '0.99 0.01 245',

            'secondary' => '0.56 0.16 275',
            'secondary-text' => '0.99 0.01 245',

            'base' => [
                'text' => '0.22 0.03 255',
                'stroke' => '0.36 0.18 258 / 20%',
                'default' => '0.98 0.01 245',

                50 => '0.98 0.015 245',
                100 => '0.96 0.025 245',
                200 => '0.93 0.045 245',
                300 => '0.89 0.07 245',
                400 => '0.84 0.10 245',
                500 => '0.76 0.14 245',
                600 => '0.66 0.17 250',
                700 => '0.56 0.19 255',
                800 => '0.46 0.16 258',
                900 => '0.36 0.12 258',
            ],

            'success' => '0.64 0.22 142.49',
            'success-text' => '0.45 0.16 142.49',

            'warning' => '0.78 0.17 75.35',
            'warning-text' => '0.50 0.10 76.10',

            'error' => '0.58 0.21 26.855',
            'error-text' => '0.37 0.145 26.85',

            'info' => '0.60 0.219 245',
            'info-text' => '0.35 0.12 245',
        ];
    }

    public function getDarkColors(): array
    {
        return [
            'body' => '0.16 0.025 250',

            'primary' => '0.64 0.17 255',
            'primary-text' => '0.98 0.01 245',

            'secondary' => '0.60 0.15 275',
            'secondary-text' => '0.98 0.01 245',

            'base' => [
                'text' => '0.92 0.025 245',
                'stroke' => '0.64 0.17 255 / 20%',
                'default' => '0.21 0.035 250',

                50 => '0.24 0.04 250',
                100 => '0.28 0.05 250',
                200 => '0.33 0.065 250',
                300 => '0.39 0.085 250',
                400 => '0.46 0.11 252',
                500 => '0.54 0.14 255',
                600 => '0.62 0.16 255',
                700 => '0.70 0.17 255',
                800 => '0.77 0.14 255',
                900 => '0.84 0.11 255',
            ],

            'success' => '0.64 0.22 142.495',
            'success-text' => '0.93 0.12 144.46',

            'warning' => '0.90 0.22 92.72',
            'warning-text' => '0.99 0.072 107.64',

            'error' => '0.589 0.214 26.855',
            'error-text' => '0.71 0.24 25.96',

            'info' => '0.60 0.22 245',
            'info-text' => '0.88 0.065 245',
        ];
    }
}