<?php

declare(strict_types=1);

namespace Tourze\ProductServiceContracts\Tests;

/** @phpstan-ignore InterfaceStubTrait.consistency */
/** @phpstan-ignore class.implementsInterface */

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\ProductServiceContracts\SKU;
use Tourze\ProductServiceContracts\SkuLoaderInterface;
use Tourze\ProductServiceContracts\SPU;

/**
 * @internal
 */
#[CoversClass(SkuLoaderInterface::class)]
class SkuLoaderInterfaceTest extends TestCase
{
    public function testSkuLoaderInterfaceDefinition(): void
    {
        $this->assertTrue(interface_exists(SkuLoaderInterface::class));

        $reflection = new \ReflectionClass(SkuLoaderInterface::class);

        // Test loadSkuByIdentifier method
        $loadMethod = $reflection->getMethod('loadSkuByIdentifier');
        $this->assertTrue($loadMethod->isPublic());
        $this->assertCount(1, $loadMethod->getParameters());
        $this->assertSame('identifier', $loadMethod->getParameters()[0]->getName());

        $returnType = $loadMethod->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $returnType);
        $this->assertTrue($returnType->allowsNull());
        $this->assertSame(SKU::class, $returnType->getName());

        // Test createSku method
        $createMethod = $reflection->getMethod('createSku');
        $this->assertTrue($createMethod->isPublic());
        $this->assertCount(5, $createMethod->getParameters());

        $parameters = $createMethod->getParameters();
        $this->assertSame('spu', $parameters[0]->getName());
        $this->assertSame('gtin', $parameters[1]->getName());
        $this->assertSame('mpn', $parameters[2]->getName());
        $this->assertSame('remark', $parameters[3]->getName());
        $this->assertSame('valid', $parameters[4]->getName());

        // Check parameter defaults
        $this->assertTrue($parameters[1]->isDefaultValueAvailable());
        $this->assertNull($parameters[1]->getDefaultValue());
        $this->assertTrue($parameters[4]->isDefaultValueAvailable());
        $this->assertTrue($parameters[4]->getDefaultValue());

        $createReturnType = $createMethod->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $createReturnType);
        $this->assertFalse($createReturnType->allowsNull());
        $this->assertSame(SKU::class, $createReturnType->getName());
    }

    public function testSkuLoaderImplementation(): void
    {
        $mockSpu = $this->createMock(SPU::class);

        // 简化实现，避免复杂匿名类
        /** @phpstan-ignore-next-line */
        $implementation = new class implements SkuLoaderInterface {
            public function loadSkuByIdentifier(string $identifier): ?SKU
            {
                return 'test-sku-123' === $identifier ? $this->createTestSku('test-sku-123') : null;
            }

            public function createSku(
                SPU $spu,
                ?string $gtin = null,
                ?string $mpn = null,
                ?string $remark = null,
                ?bool $valid = true,
            ): SKU {
                return $this->createTestSku('created-sku-' . uniqid(), $gtin, $mpn, $remark, $valid);
            }

            private function createTestSku(
                string $id,
                ?string $gtin = '1234567890123',
                ?string $mpn = 'MPN-001',
                ?string $remark = 'Test SKU',
                ?bool $valid = true,
            ): SKU {
                return new class($id, $gtin, $mpn, $remark, $valid) implements SKU {
                    public function __construct(
                        private string $id,
                        private ?string $gtin,
                        private ?string $mpn,
                        private ?string $remark,
                        private ?bool $valid,
                    ) {
                    }

                    public function getId(): string
                    {
                        return $this->id;
                    }

                    public function getGtin(): ?string
                    {
                        return $this->gtin;
                    }

                    public function getMpn(): ?string
                    {
                        return $this->mpn;
                    }

                    public function getRemark(): ?string
                    {
                        return $this->remark;
                    }

                    public function isValid(): ?bool
                    {
                        return $this->valid;
                    }
                };
            }
        };

        $this->assertInstanceOf(SkuLoaderInterface::class, $implementation);

        // Test loadSkuByIdentifier
        $sku = $implementation->loadSkuByIdentifier('test-sku-123');
        $this->assertInstanceOf(SKU::class, $sku);
        $this->assertSame('test-sku-123', $sku->getId());

        $nonExistentSku = $implementation->loadSkuByIdentifier('non-existent');
        $this->assertNull($nonExistentSku);

        // Test createSku
        $createdSku = $implementation->createSku(
            $mockSpu,
            '9876543210987',
            'MPN-NEW',
            'New SKU',
            false
        );

        $this->assertInstanceOf(SKU::class, $createdSku);
        $this->assertSame('9876543210987', $createdSku->getGtin());
        $this->assertSame('MPN-NEW', $createdSku->getMpn());
        $this->assertSame('New SKU', $createdSku->getRemark());
        $this->assertFalse($createdSku->isValid());
    }
}
