<?php

declare(strict_types=1);

namespace App\MoonShine\Palettes;

use MoonShine\Contracts\ColorManager\PaletteContract;

final class DocFlowPalette implements PaletteContract
{
    public function getDescription(): string
    {
        return 'DocFlow blue palette - Vibrant Edition';
    }

    public function getColors(): array
    {
        return [
            'body' => '0.24 0.12 255',

            // Bleu principal - VIBRANT
            'primary' => '0.47 0.30 255',
            'primary-text' => '0.98 0.01 250',

            'secondary' => '0.88 0.18 250',
            'secondary-text' => '0.25 0.03 250',

            'base' => [
                'text' => '0.95 0.02 250',
                'stroke' => '0.47 0.30 255 / 25%',
                'default' => '0.26 0.10 255',

                50 => '0.28 0.08 255',
                100 => '0.30 0.09 255',
                200 => '0.33 0.10 255',
                300 => '0.36 0.11 255',
                400 => '0.41 0.12 255',
                500 => '0.70 0.22 255',
                600 => '0.60 0.26 255',
                700 => '0.47 0.30 255',
                800 => '0.39 0.28 255',
                900 => '0.32 0.25 255',
            ],

            // SUCCESS - Vert vibrant
            'success' => '0.58 0.35 143.50',
            'success-text' => '0.45 0.20 143.50',

            // WARNING - Orange/Rose vibrant
            'warning' => '0.68 0.32 29.50',
            'warning-text' => '0.48 0.18 29.50',

            // ERROR - Rouge vibrant
            'error' => '0.55 0.32 27.50',
            'error-text' => '0.36 0.20 27.50',

            // INFO - Bleu clair vibrant
            'info' => '0.56 0.30 260.00',
            'info-text' => '0.33 0.18 260.00',
        ];
    }

    public function getDarkColors(): array
    {
        return [
            'body' => '0.18 0.10 255',

            // Bleu foncé en dark mode - VIBRANT
            'primary' => '0.66 0.28 255',
            'primary-text' => '0.98 0.01 250',

            'secondary' => '0.42 0.18 250',
            'secondary-text' => '0.94 0.02 250',

            'base' => [
                'text' => '0.96 0.01 250',
                'stroke' => '0.66 0.28 255 / 30%',
                'default' => '0.20 0.09 255',

                50 => '0.22 0.08 255',
                100 => '0.24 0.08 255',
                200 => '0.27 0.09 255',
                300 => '0.30 0.10 255',
                400 => '0.34 0.11 255',
                500 => '0.54 0.22 255',
                600 => '0.61 0.26 255',
                700 => '0.66 0.28 255',
                800 => '0.73 0.26 255',
                900 => '0.81 0.22 255',
            ],

            // SUCCESS - Vert vibrant (dark)
            'success' => '0.65 0.32 143.50',
            'success-text' => '0.90 0.22 143.50',

            // WARNING - Orange vibrant (dark)
            'warning' => '0.72 0.30 29.50',
            'warning-text' => '0.96 0.18 29.50',

            // ERROR - Rouge vibrant (dark)
            'error' => '0.62 0.30 27.50',
            'error-text' => '0.88 0.22 27.50',

            // INFO - Bleu clair vibrant (dark)
            'info' => '0.64 0.28 260.00',
            'info-text' => '0.85 0.20 260.00',
        ];
    }
}