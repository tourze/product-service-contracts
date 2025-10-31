<?php

namespace Tourze\ProductServiceContracts;

/**
 * @method array<int, \Tourze\CatalogBundle\Entity\Catalog> getCategories()
 */
interface SPU
{
    public function getGtin(): ?string;
}
