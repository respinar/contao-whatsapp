<?php

declare(strict_types=1);

/*
 * This file is part of Contao Whatsapp Button Bundle.
 *
 * (c) Hamid Peywasti
 *
 * @license MIT
 */

namespace Respinar\WhatsappButtonBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Respinar\WhatsappButtonBundle\WhatsappButtonBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(WhatsappButtonBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
