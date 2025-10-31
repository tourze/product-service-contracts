# Product Service Contracts

[English](README.md) | [中文](README.zh-CN.md)

Product service definition contracts for PHP applications, providing standard interfaces for SKU and SPU management.

## Installation

```bash
composer require tourze/product-service-contracts
```

## Quick Start

This package provides standard interfaces and contracts for product-related services in PHP applications.

### SKU Interface

```php
<?php

use Tourze\ProductServiceContracts\SKU;

// Implement SKU interface
class ProductSku implements SKU
{
    public function getId(): string
    {
        return 'sku-123';
    }
    
    public function getGtin(): ?string
    {
        return '1234567890123';
    }
    
    public function getMpn(): ?string
    {
        return 'MPN-001';
    }
    
    public function getRemark(): ?string
    {
        return 'Product remarks';
    }
    
    public function isValid(): ?bool
    {
        return true;
    }
}
```

### SPU Interface

```php
<?php

use Tourze\ProductServiceContracts\SPU;

// Implement SPU interface  
class ProductSpu implements SPU
{
    public function getGtin(): ?string
    {
        return '1234567890123';
    }
}
```

### SKU Loader Interface

```php
<?php

use Tourze\ProductServiceContracts\SkuLoaderInterface;
use Tourze\ProductServiceContracts\SKU;
use Tourze\ProductServiceContracts\SPU;

class SkuLoader implements SkuLoaderInterface
{
    public function loadSkuByIdentifier(string $identifier): ?SKU
    {
        // Implementation to load SKU by identifier
        return null;
    }
    
    public function createSku(
        SPU $spu,
        ?string $gtin = null,
        ?string $mpn = null,
        ?string $remark = null,
        ?bool $valid = true,
    ): SKU {
        // Implementation to create new SKU
    }
}
```

### SPU Loader Interface

```php
<?php

use Tourze\ProductServiceContracts\SpuLoaderInterface;
use Tourze\ProductServiceContracts\SPU;

class SpuLoader implements SpuLoaderInterface
{
    public function loadSpuByIdentifier(string $identifier): ?SPU
    {
        // Implementation to load SPU by identifier
        return null;
    }
    
    public function createSpu(
        ?string $gtin = null,
        ?string $title = null,
        ?string $remark = null,
        ?bool $valid = true,
    ): SPU {
        // Implementation to create new SPU
    }
    
    public function loadOrCreateSpu(
        ?string $gtin = null,
        ?string $title = null,
        ?string $remark = null,
        ?bool $valid = true,
    ): SPU {
        // Implementation to load existing or create new SPU
    }
}
```

## Features

- **SKU Interface**: Stock Keeping Unit contract with ID, GTIN, MPN, and validation
- **SPU Interface**: Standard Product Unit contract with GTIN support  
- **SkuLoaderInterface**: Contract for loading and creating SKU instances
- **SpuLoaderInterface**: Contract for loading, creating, and load-or-create SPU operations
- Type-safe contracts with nullable support
- Compatible with Symfony dependency injection
- PSR-4 autoloading support
- Comprehensive test coverage

## Interfaces

### SKU (Stock Keeping Unit)
- `getId(): string` - Get unique SKU identifier
- `getGtin(): ?string` - Get Global Trade Item Number (nullable)
- `getMpn(): ?string` - Get Manufacturer Part Number (nullable)
- `getRemark(): ?string` - Get SKU remarks (nullable)
- `isValid(): ?bool` - Check if SKU is valid (nullable)

### SPU (Standard Product Unit)
- `getGtin(): ?string` - Get Global Trade Item Number (nullable)

### SkuLoaderInterface
- `loadSkuByIdentifier(string $identifier): ?SKU` - Load SKU by identifier
- `createSku(SPU $spu, ?string $gtin, ?string $mpn, ?string $remark, ?bool $valid): SKU` - Create new SKU

### SpuLoaderInterface  
- `loadSpuByIdentifier(string $identifier): ?SPU` - Load SPU by identifier
- `createSpu(?string $gtin, ?string $title, ?string $remark, ?bool $valid): SPU` - Create new SPU
- `loadOrCreateSpu(?string $gtin, ?string $title, ?string $remark, ?bool $valid): SPU` - Load existing or create new SPU

## Configuration

This package provides contracts only. Implementation should be provided by your application or other packages.

## License

This package is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.