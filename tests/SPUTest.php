<?php

namespace Tourze\ProductServiceContracts\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\ProductServiceContracts\SPU;

/**
 * @internal
 */
#[CoversClass(SPU::class)]
class SPUTest extends TestCase
{
    public function testSPUInterfaceDefinition(): void
    {
        $this->assertTrue(interface_exists(SPU::class));

        $reflection = new \ReflectionClass(SPU::class);
        $method = $reflection->getMethod('getGtin');

        $returnType = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $returnType);
        $this->assertTrue($returnType->allowsNull());
        $this->assertSame('string', $returnType->getName());

        $this->assertCount(0, $method->getParameters());
        $this->assertTrue($method->isPublic());
    }

    public function testSPUImplementationWithGtin(): void
    {
        $implementation = new class implements SPU {
            public function getGtin(): string
            {
                return '1234567890123';
            }
        };

        $this->assertInstanceOf(SPU::class, $implementation);
        $this->assertSame('1234567890123', $implementation->getGtin());
    }

    public function testSPUImplementationWithoutGtin(): void
    {
        $implementation = new class implements SPU {
            public function getGtin(): ?string
            {
                return null;
            }
        };

        $this->assertInstanceOf(SPU::class, $implementation);
        $this->assertNull($implementation->getGtin());
    }
}
