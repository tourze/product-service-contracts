<?php

namespace Tourze\ProductServiceContracts\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\ProductServiceContracts\SKU;

/**
 * @internal
 */
#[CoversClass(SKU::class)]
class SKUTest extends TestCase
{
    public function testSKUInterfaceDefinition(): void
    {
        $this->assertTrue(interface_exists(SKU::class));

        $reflection = new \ReflectionClass(SKU::class);
        $method = $reflection->getMethod('getId');

        $returnType = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $returnType);
        $this->assertSame('string', $returnType->getName());
        $this->assertCount(0, $method->getParameters());
        $this->assertTrue($method->isPublic());
    }

    public function testSKUImplementation(): void
    {
        $implementation = new class implements SKU {
            public function getId(): string
            {
                return 'test-sku-123';
            }

            public function getGtin(): string
            {
                return '1234567890123';
            }

            public function getMpn(): string
            {
                return 'MPN-TEST-001';
            }

            public function getRemark(): string
            {
                return 'Test SKU remark';
            }

            public function isValid(): ?bool
            {
                return true;
            }
        };

        $this->assertInstanceOf(SKU::class, $implementation);
        $this->assertSame('test-sku-123', $implementation->getId());
        $this->assertSame('1234567890123', $implementation->getGtin());
        $this->assertSame('MPN-TEST-001', $implementation->getMpn());
        $this->assertSame('Test SKU remark', $implementation->getRemark());
        $this->assertTrue($implementation->isValid());
    }

    public function testSKUImplementationWithNullValues(): void
    {
        $implementation = new class implements SKU {
            public function getId(): string
            {
                return 'test-sku-456';
            }

            public function getGtin(): ?string
            {
                return null;
            }

            public function getMpn(): ?string
            {
                return null;
            }

            public function getRemark(): ?string
            {
                return null;
            }

            public function isValid(): ?bool
            {
                return null;
            }
        };

        $this->assertInstanceOf(SKU::class, $implementation);
        $this->assertSame('test-sku-456', $implementation->getId());
        $this->assertNull($implementation->getGtin());
        $this->assertNull($implementation->getMpn());
        $this->assertNull($implementation->getRemark());
        $this->assertNull($implementation->isValid());
    }
}
