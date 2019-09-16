<?php

declare(strict_types=1);

use Contao\System;
use Symfony\Component\Asset\Packages;

/** @var Packages $packages */
$packages = System::getContainer()->get('assets.packages');

$GLOBALS['TL_SUBCL'] = [
    'foundation6' => [
        'label' => 'Foundation 6',
        'scclass' => 'grid-x',
        'inside' => false,
        'gap' => false,
        'sets' => [
            '2 Columns, 2 Offset' => [
                ['cell small-12 medium-4 medium-offset-4'],
                ['cell small-12 medium-4'],
            ],
        ],
    ],
];

if (TL_MODE == 'BE') {

    $GLOBALS['TL_SUBCL']['foundation6']['files'] = [
        'css' => $packages->getUrl('backend.css'),
    ];
}
