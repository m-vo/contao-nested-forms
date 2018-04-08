<?php

declare(strict_types=1);

/*
 * Nested Forms Bundle for Contao Open Source CMS
 *
 * @copyright  Moritz Vondano
 * @license    MIT
 * @link       https://github.com/m-vo/contao-nested-forms
 *
 */

namespace Mvo\ContaoNestedForms\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Mvo\ContaoNestedForms\MvoContaoNestedFormsBundle;

class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(MvoContaoNestedFormsBundle::class)
                ->setLoadAfter(
                    [
                        ContaoCoreBundle::class
                    ]
                ),
        ];
    }
}
