<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SyliusSitemapBundle\Factory;

use SyliusSitemapBundle\Model\Sitemap;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class SitemapFactory implements SitemapFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createNew()
    {
        return new Sitemap();
    }
}