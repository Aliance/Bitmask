<?php
declare(strict_types=1);

namespace Aliance\Bitmask\Tests;

use Aliance\Bitmask\Bitmask;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for simple bitmask implementation.
 * @covers \Aliance\Bitmask\Bitmask
 */
class BitmaskTest extends TestCase
{
    private Bitmask $bitmask;

    protected function setUp(): void
    {
        $this->bitmask = new Bitmask();
    }

    public function testBitmaskCreation(): void
    {
        $this->assertInstanceOf(Bitmask::class, new Bitmask());
    }

    public function testEmptyBitmask(): void
    {
        $this->assertEquals(0, $this->bitmask->getSetBitsCount());
        $this->assertEquals(0, $this->bitmask->getMask());
        $this->assertFalse($this->bitmask->issetBit(1));
    }

    public function testThatTooBigBitCauseAnException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->bitmask->setBit(64);
    }

    public function testMaskSetting(): void
    {
        $this->assertEquals(0, $this->bitmask->getMask());
        $this->bitmask->setMask(1024);
        $this->assertEquals(1024, $this->bitmask->getMask());
    }

    public function testMaskAdding(): void
    {
        $this->assertEquals(0, $this->bitmask->getMask());

        // adding 10th bit
        $this->bitmask->addMask(1024);
        $this->assertEquals(1024, $this->bitmask->getMask());

        // adding 3rd bit
        $this->bitmask->addMask(8);
        $this->assertEquals(1032, $this->bitmask->getMask());
    }

    public function testMaskDeleting(): void
    {
        $this->assertEquals(0, $this->bitmask->getMask());

        // adding 3rd & 10th bits
        $this->bitmask->setMask(1032);
        $this->assertEquals(1032, $this->bitmask->getMask());

        // deleting 10th bit
        $this->bitmask->deleteMask(1024);
        $this->assertEquals(8, $this->bitmask->getMask());
    }

    public function testBits(): void
    {
        $this->assertFalse($this->bitmask->issetBit(3));
        $this->assertFalse($this->bitmask->issetBit(5));
        $this->assertFalse($this->bitmask->issetBit(12));
        $this->assertFalse($this->bitmask->issetBit(63));

        $this->bitmask->setBit(3);
        $this->bitmask->setBit(12);
        $this->bitmask->setBit(63);

        $this->assertTrue($this->bitmask->issetBit(3));
        $this->assertFalse($this->bitmask->issetBit(5));
        $this->assertTrue($this->bitmask->issetBit(12));
        $this->assertTrue($this->bitmask->issetBit(63));

        $this->bitmask->unsetBit(12);
        $this->bitmask->unsetBit(63);

        $this->assertTrue($this->bitmask->issetBit(3));
        $this->assertFalse($this->bitmask->issetBit(5));
        $this->assertFalse($this->bitmask->issetBit(12));
        $this->assertFalse($this->bitmask->issetBit(63));
    }

    /**
     * @dataProvider getBitsWithMaskPairs
     * @param int[] $bits
     * @param int $expectedMask
     */
    public function testGeneralUsage(array $bits, int $expectedMask): void
    {
        foreach ($bits as $bit) {
            $this->bitmask->setBit($bit);
        }

        $this->assertCount($this->bitmask->getSetBitsCount(), $bits);
        $this->assertEquals($expectedMask, $this->bitmask->getMask());

        foreach ($bits as $bit) {
            $this->assertTrue($this->bitmask->issetBit($bit));
        }
    }

    public function getBitsWithMaskPairs(): array
    {
        return [
            [
                [],
                0, // no bits will be set
            ],
            [
                [1, 3, 6],
                74, // 2^1 + 2^3 + 2^6 = 2 + 8 + 64
            ],
            [
                [2, 10, 30,],
                1073742852, // 2^2 + 2^10 + 2^30 = 4 + 1024 + 1073741824
            ],
            [
                range(0, 63),
                -1, // in php int64 always signed :(
            ],
        ];
    }
}
