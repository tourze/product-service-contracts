<?php

namespace Tourze\ProductServiceContracts;

interface SkuLoaderInterface
{
    public function loadSkuByIdentifier(string $identifier): ?SKU;

    public function createSku(
        SPU $spu,
        ?string $gtin = null,
        ?string $mpn = null,
        ?string $remark = null,
        ?bool $valid = true,
    ): SKU;
}
