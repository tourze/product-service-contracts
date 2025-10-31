# Product Service Contracts

[English](README.md) | [中文](README.zh-CN.md)

PHP 应用程序产品服务定义合约包，提供 SKU 和 SPU 管理的标准接口。

## 安装

```bash
composer require tourze/product-service-contracts
```

## 快速开始

此包为 PHP 应用程序中的产品相关服务提供标准接口和合约。

### SKU 接口

```php
<?php

use Tourze\ProductServiceContracts\SKU;

// 实现 SKU 接口
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
        return '产品备注';
    }
    
    public function isValid(): ?bool
    {
        return true;
    }
}
```

### SPU 接口

```php
<?php

use Tourze\ProductServiceContracts\SPU;

// 实现 SPU 接口  
class ProductSpu implements SPU
{
    public function getGtin(): ?string
    {
        return '1234567890123';
    }
}
```

### SKU 加载器接口

```php
<?php

use Tourze\ProductServiceContracts\SkuLoaderInterface;
use Tourze\ProductServiceContracts\SKU;
use Tourze\ProductServiceContracts\SPU;

class SkuLoader implements SkuLoaderInterface
{
    public function loadSkuByIdentifier(string $identifier): ?SKU
    {
        // 通过标识符加载 SKU 的实现
        return null;
    }
    
    public function createSku(
        SPU $spu,
        ?string $gtin = null,
        ?string $mpn = null,
        ?string $remark = null,
        ?bool $valid = true,
    ): SKU {
        // 创建新 SKU 的实现
    }
}
```

### SPU 加载器接口

```php
<?php

use Tourze\ProductServiceContracts\SpuLoaderInterface;
use Tourze\ProductServiceContracts\SPU;

class SpuLoader implements SpuLoaderInterface
{
    public function loadSpuByIdentifier(string $identifier): ?SPU
    {
        // 通过标识符加载 SPU 的实现
        return null;
    }
    
    public function createSpu(
        ?string $gtin = null,
        ?string $title = null,
        ?string $remark = null,
        ?bool $valid = true,
    ): SPU {
        // 创建新 SPU 的实现
    }
    
    public function loadOrCreateSpu(
        ?string $gtin = null,
        ?string $title = null,
        ?string $remark = null,
        ?bool $valid = true,
    ): SPU {
        // 加载现有或创建新 SPU 的实现
    }
}
```

## 功能特性

- **SKU 接口**：库存单位合约，包含 ID、GTIN、MPN 和验证功能
- **SPU 接口**：标准产品单位合约，支持 GTIN  
- **SkuLoaderInterface**：用于加载和创建 SKU 实例的合约
- **SpuLoaderInterface**：用于加载、创建和加载或创建 SPU 操作的合约
- 支持可空类型的类型安全合约
- 与 Symfony 依赖注入兼容
- PSR-4 自动加载支持
- 全面的测试覆盖

## 接口说明

### SKU (库存单位)
- `getId(): string` - 获取唯一 SKU 标识符
- `getGtin(): ?string` - 获取全球贸易项目代码（可空）
- `getMpn(): ?string` - 获取制造商零件号（可空）
- `getRemark(): ?string` - 获取 SKU 备注（可空）
- `isValid(): ?bool` - 检查 SKU 是否有效（可空）

### SPU (标准产品单位)
- `getGtin(): ?string` - 获取全球贸易项目代码（可空）

### SkuLoaderInterface
- `loadSkuByIdentifier(string $identifier): ?SKU` - 通过标识符加载 SKU
- `createSku(SPU $spu, ?string $gtin, ?string $mpn, ?string $remark, ?bool $valid): SKU` - 创建新 SKU

### SpuLoaderInterface  
- `loadSpuByIdentifier(string $identifier): ?SPU` - 通过标识符加载 SPU
- `createSpu(?string $gtin, ?string $title, ?string $remark, ?bool $valid): SPU` - 创建新 SPU
- `loadOrCreateSpu(?string $gtin, ?string $title, ?string $remark, ?bool $valid): SPU` - 加载现有或创建新 SPU

## 配置

此包仅提供合约。实现应由您的应用程序或其他包提供。

## 许可证

本包采用 MIT 许可证。详情请查看 [LICENSE](LICENSE) 文件。
