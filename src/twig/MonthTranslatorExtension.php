<?php

namespace App\twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MonthTranslatorExtension extends AbstractExtension
{
    private $months = [
        1 => 'Janvier',
        2 => 'Février',
        3 => 'Mars',
        4 => 'Avril',
        5 => 'Mai',
        6 => 'Juin',
        7 => 'Juillet',
        8 => 'Août',
        9 => 'Septembre',
        10 => 'Octobre',
        11 => 'Novembre',
        12 => 'Décembre',
    ];

    public function getFilters()
    {
        return [
            new TwigFilter('translate_month', [$this, 'translateMonth']),
        ];
    }

    public function translateMonth($monthNumber)
    {
        return $this->months[$monthNumber] ?? '';
    }
}
