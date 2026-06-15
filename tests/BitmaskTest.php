<?php
declare(strict_types=1);

namespace Aliance\Bitmask\Tests;

use Aliance\Bitmask\Bitmask;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for simple bitmask implementation.
 */
#[CoversClass(Bitmask::class)]
class BitmaskTest extends TestCase
{
    private Bitmask $bitmask;

    public function testBitmaskCreation(): void
    {
        self::assertInstanceOf(Bitmask::class, new Bitmask());
    }

    public function testEmptyBitmask(): void
    {
        self::assertEquals(0, $this->bitmask->getSetBitsCount());
        self::assertEquals(0, $this->bitmask->getMask());
        self::assertFalse($this->bitmask->issetBit(1));
    }

    public function testThatTooBigBitCauseAnException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->bitmask->setBit(64);
    }

    public function testMaskSetting(): void
    {
        self::assertEquals(0, $this->bitmask->getMask());
        $this->bitmask->setMask(1024);
        self::assertEquals(1024, $this->bitmask->getMask());
    }

    public function testMaskAdding(): void
    {
        self::assertEquals(0, $this->bitmask->getMask());

        // adding 10th bit
        $this->bitmask->addMask(1024);
        self::assertEquals(1024, $this->bitmask->getMask());

        // adding 3rd bit
        $this->bitmask->addMask(8);
        self::assertEquals(1032, $this->bitmask->getMask());
    }

    public function testMaskDeleting(): void
    {
        self::assertEquals(0, $this->bitmask->getMask());

        // adding 3rd & 10th bits
        $this->bitmask->setMask(1032);
        self::assertEquals(1032, $this->bitmask->getMask());

        // deleting 10th bit
        $this->bitmask->deleteMask(1024);
        self::assertEquals(8, $this->bitmask->getMask());
    }

    public function testBits(): void
    {
        self::assertFalse($this->bitmask->issetBit(3));
        self::assertFalse($this->bitmask->issetBit(5));
        self::assertFalse($this->bitmask->issetBit(12));
        self::assertFalse($this->bitmask->issetBit(63));

        $this->bitmask->setBit(3);
        $this->bitmask->setBit(12);
        $this->bitmask->setBit(63);

        self::assertTrue($this->bitmask->issetBit(3));
        self::assertFalse($this->bitmask->issetBit(5));
        self::assertTrue($this->bitmask->issetBit(12));
        self::assertTrue($this->bitmask->issetBit(63));

        $this->bitmask->unsetBit(12);
        $this->bitmask->unsetBit(63);

        self::assertTrue($this->bitmask->issetBit(3));
        self::assertFalse($this->bitmask->issetBit(5));
        self::assertFalse($this->bitmask->issetBit(12));
        self::assertFalse($this->bitmask->issetBit(63));
    }

    /**
     * @param int[] $bits
     */
    #[DataProvider('getBitsWithMaskPairs')]
    public function testGeneralUsage(array $bits, int $expectedMask): void
    {
        foreach ($bits as $bit) {
            $this->bitmask->setBit($bit);
        }

        self::assertCount($this->bitmask->getSetBitsCount(), $bits);
        self::assertEquals($expectedMask, $this->bitmask->getMask());

        foreach ($bits as $bit) {
            self::assertTrue($this->bitmask->issetBit($bit));
        }
    }

    public static function getBitsWithMaskPairs(): array
    {
        return [
            [
                [],
                0, // no bits will be set
            ],
            [
                [
                    1,
                    3,
                    6,
                ],
                74, // 2^1 + 2^3 + 2^6 = 2 + 8 + 64
            ],
            [
                [
                    2,
                    10,
                    30,
                ],
                1073742852, // 2^2 + 2^10 + 2^30 = 4 + 1024 + 1073741824
            ],
            [
                range(0, 63),
                -1, // in php int64 always signed :(
            ],
        ];
    }

    protected function setUp(): void
    {
        $this->bitmask = new Bitmask();
    }
}
