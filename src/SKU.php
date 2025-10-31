<?php

namespace Tourze\ProductServiceContracts;

interface SKU
{
    public function getId(): string;

    public function getGtin(): ?string;

    public function getMpn(): ?string;

    public function getRemark(): ?string;

    public function isValid(): ?bool;
}
