<?php

namespace App\Twig;

use Twig\Attribute\AsTwigFilter;
use Twig\Attribute\AsTwigFunction;

class AppExtension
{
    // {{ product.price | price(2, ',', '.') }}

    #[AsTwigFilter('price')]
    public function formatPrice(float $number, int $decimals = 0, string $decPoint = '.', string $thousandsSep = ','): string
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = '$'.$price;

        return $price;
    }

    // <p>Total area: {{ area(5, 10) }} sq ft</p>
    
    #[AsTwigFunction('area')]
    public function calculateArea(int $width, int $length): int
    {
        return $width * $length;
    }

}