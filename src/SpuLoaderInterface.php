<?php

namespace Tourze\ProductServiceContracts;

interface SpuLoaderInterface
{
    public function loadSpuByIdentifier(string $identifier): ?SPU;

    public function createSpu(
        ?string $gtin = null,
        ?string $title = null,
        ?string $remark = null,
        ?bool $valid = true,
    ): SPU;

    public function loadOrCreateSpu(
        ?string $gtin = null,
        ?string $title = null,
        ?string $remark = null,
        ?bool $valid = true,
    ): SPU;
}
