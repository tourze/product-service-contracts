<?php

declare(strict_types=1);

namespace Tourze\ProductServiceContracts\Tests;

/** @phpstan-ignore InterfaceStubTrait.consistency */
/** @phpstan-ignore class.implementsInterface */

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\ProductServiceContracts\SPU;
use Tourze\ProductServiceContracts\SpuLoaderInterface;

/**
 * @internal
 */
#[CoversClass(SpuLoaderInterface::class)]
class SpuLoaderInterfaceTest extends TestCase
{
    public function testSpuLoaderInterfaceDefinition(): void
    {
        $this->assertTrue(interface_exists(SpuLoaderInterface::class));

        $reflection = new \ReflectionClass(SpuLoaderInterface::class);

        // Test loadSpuByIdentifier method
        $loadMethod = $reflection->getMethod('loadSpuByIdentifier');
        $this->assertTrue($loadMethod->isPublic());
        $this->assertCount(1, $loadMethod->getParameters());
        $this->assertSame('identifier', $loadMethod->getParameters()[0]->getName());

        $returnType = $loadMethod->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $returnType);
        $this->assertTrue($returnType->allowsNull());
        $this->assertSame(SPU::class, $returnType->getName());

        // Test createSpu method
        $createMethod = $reflection->getMethod('createSpu');
        $this->assertTrue($createMethod->isPublic());
        $this->assertCount(4, $createMethod->getParameters());

        $parameters = $createMethod->getParameters();
        $this->assertSame('gtin', $parameters[0]->getName());
        $this->assertSame('title', $parameters[1]->getName());
        $this->assertSame('remark', $parameters[2]->getName());
        $this->assertSame('valid', $parameters[3]->getName());

        // Check parameter defaults
        $this->assertTrue($parameters[0]->isDefaultValueAvailable());
        $this->assertNull($parameters[0]->getDefaultValue());
        $this->assertTrue($parameters[3]->isDefaultValueAvailable());
        $this->assertTrue($parameters[3]->getDefaultValue());

        $createReturnType = $createMethod->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $createReturnType);
        $this->assertFalse($createReturnType->allowsNull());
        $this->assertSame(SPU::class, $createReturnType->getName());

        // Test loadOrCreateSpu method
        $loadOrCreateMethod = $reflection->getMethod('loadOrCreateSpu');
        $this->assertTrue($loadOrCreateMethod->isPublic());
        $this->assertCount(4, $loadOrCreateMethod->getParameters());

        $loadOrCreateReturnType = $loadOrCreateMethod->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $loadOrCreateReturnType);
        $this->assertFalse($loadOrCreateReturnType->allowsNull());
        $this->assertSame(SPU::class, $loadOrCreateReturnType->getName());
    }

    public function testSpuLoaderImplementation(): void
    {
        // 简化实现，避免复杂匿名类
        /** @phpstan-ignore-next-line */
        $implementation = new class implements SpuLoaderInterface {
            /** @var array<SPU> */
            private array $storage = [];

            public function loadSpuByIdentifier(string $identifier): ?SPU
            {
                return 'test-spu-123' === $identifier ? $this->createTestSpu('1234567890123') : null;
            }

            public function createSpu(
                ?string $gtin = null,
                ?string $title = null,
                ?string $remark = null,
                ?bool $valid = true,
            ): SPU {
                $spu = $this->createTestSpu($gtin);
                $this->storage[] = $spu;

                return $spu;
            }

            public function loadOrCreateSpu(
                ?string $gtin = null,
                ?string $title = null,
                ?string $remark = null,
                ?bool $valid = true,
            ): SPU {
                foreach ($this->storage as $spu) {
                    if ($spu->getGtin() === $gtin) {
                        return $spu;
                    }
                }

                return $this->createSpu($gtin, $title, $remark, $valid);
            }

            private function createTestSpu(?string $gtin = '1234567890123'): SPU
            {
                return new class($gtin) implements SPU {
                    public function __construct(private ?string $gtin)
                    {
                    }

                    public function getGtin(): ?string
                    {
                        return $this->gtin;
                    }
                };
            }
        };

        $this->assertInstanceOf(SpuLoaderInterface::class, $implementation);

        // Test loadSpuByIdentifier
        $spu = $implementation->loadSpuByIdentifier('test-spu-123');
        $this->assertInstanceOf(SPU::class, $spu);
        $this->assertSame('1234567890123', $spu->getGtin());

        $nonExistentSpu = $implementation->loadSpuByIdentifier('non-existent');
        $this->assertNull($nonExistentSpu);

        // Test createSpu
        $createdSpu = $implementation->createSpu('9876543210987', 'Test Title', 'Test Remark', false);
        $this->assertInstanceOf(SPU::class, $createdSpu);
        $this->assertSame('9876543210987', $createdSpu->getGtin());

        // Test loadOrCreateSpu - should create new
        $newSpu = $implementation->loadOrCreateSpu('1111111111111');
        $this->assertInstanceOf(SPU::class, $newSpu);
        $this->assertSame('1111111111111', $newSpu->getGtin());

        // Test loadOrCreateSpu - should load existing
        $existingSpu = $implementation->loadOrCreateSpu('9876543210987');
        $this->assertInstanceOf(SPU::class, $existingSpu);
        $this->assertSame('9876543210987', $existingSpu->getGtin());
    }

    public function testSpuLoaderImplementationWithNullValues(): void
    {
        // 简化实现，避免复杂匿名类
        /** @phpstan-ignore-next-line */
        $implementation = new class implements SpuLoaderInterface {
            public function loadSpuByIdentifier(string $identifier): ?SPU
            {
                return null;
            }

            public function createSpu(
                ?string $gtin = null,
                ?string $title = null,
                ?string $remark = null,
                ?bool $valid = true,
            ): SPU {
                return $this->createTestSpu($gtin);
            }

            public function loadOrCreateSpu(
                ?string $gtin = null,
                ?string $title = null,
                ?string $remark = null,
                ?bool $valid = true,
            ): SPU {
                return $this->createSpu($gtin, $title, $remark, $valid);
            }

            private function createTestSpu(?string $gtin = null): SPU
            {
                return new class($gtin) implements SPU {
                    public function __construct(private ?string $gtin)
                    {
                    }

                    public function getGtin(): ?string
                    {
                        return $this->gtin;
                    }
                };
            }
        };

        // Test with null values
        $spuWithNull = $implementation->createSpu();
        $this->assertInstanceOf(SPU::class, $spuWithNull);
        $this->assertNull($spuWithNull->getGtin());

        $loadOrCreateWithNull = $implementation->loadOrCreateSpu();
        $this->assertInstanceOf(SPU::class, $loadOrCreateWithNull);
        $this->assertNull($loadOrCreateWithNull->getGtin());
    }
}
