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
        self::assertSame(0, $this->bitmask->getSetBitsCount());
        self::assertSame(0, $this->bitmask->getMask());
        self::assertFalse($this->bitmask->issetBit(1));
    }

    public function testThatTooBigBitCauseAnException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->bitmask->setBit(64);
    }

    public function testMaskSetting(): void
    {
        self::assertSame(0, $this->bitmask->getMask());
        $this->bitmask->setMask(1024);
        self::assertSame(1024, $this->bitmask->getMask());
    }

    public function testMaskAdding(): void
    {
        self::assertSame(0, $this->bitmask->getMask());

        // adding 10th bit
        $this->bitmask->addMask(1024);
        self::assertSame(1024, $this->bitmask->getMask());

        // adding 3rd bit
        $this->bitmask->addMask(8);
        self::assertSame(1032, $this->bitmask->getMask());
    }

    public function testMaskDeleting(): void
    {
        self::assertSame(0, $this->bitmask->getMask());

        // adding 3rd & 10th bits
        $this->bitmask->setMask(1032);
        self::assertSame(1032, $this->bitmask->getMask());

        // deleting 10th bit
        $this->bitmask->deleteMask(1024);
        self::assertSame(8, $this->bitmask->getMask());
    }

    #[DataProvider('provideHasAllCases')]
    public function testHasAll(int $stored, int $requested, bool $expected): void
    {
        self::assertSame($expected, (new Bitmask($stored))->hasAll($requested));
    }

    public static function provideHasAllCases(): array
    {
        return [
            'empty mask satisfies an empty request' => [
                0,
                0,
                'expected' => true,
            ],
            'nonempty mask also satisfies an empty request' => [
                0b1011,
                0,
                'expected' => true,
            ],
            'empty mask cannot supply any requested bits' => [
                0,
                0b0011,
                'expected' => false,
            ],
            'extra stored bits are allowed' => [
                0b1011,
                0b0011,
                'expected' => true,
            ],
            'one missing bit makes a partial match fail' => [
                0b1011,
                0b0110,
                'expected' => false,
            ],
            'disjoint masks leave every requested bit missing' => [
                0b1011,
                0b0100,
                'expected' => false,
            ],
            'bit 63 satisfies a request for bit 63 alone' => [
                PHP_INT_MIN,
                PHP_INT_MIN,
                'expected' => true,
            ],
            'bit 63 alone cannot satisfy a request for bits 63 and 0' => [
                PHP_INT_MIN,
                PHP_INT_MIN | 1,
                'expected' => false,
            ],
            'a full mask satisfies a request for all 64 bits' => [
                -1,
                -1,
                'expected' => true,
            ],
        ];
    }

    #[DataProvider('provideHasAnyCases')]
    public function testHasAny(int $stored, int $requested, bool $expected): void
    {
        self::assertSame($expected, (new Bitmask($stored))->hasAny($requested));
    }

    public static function provideHasAnyCases(): array
    {
        return [
            'two empty masks share no bits' => [
                0,
                0,
                'expected' => false,
            ],
            'an empty request shares no bits with a nonempty mask' => [
                0b1011,
                0,
                'expected' => false,
            ],
            'an empty mask shares no bits with a nonempty request' => [
                0,
                0b0011,
                'expected' => false,
            ],
            'a request overlaps when every requested bit is set' => [
                0b1011,
                0b0011,
                'expected' => true,
            ],
            'one shared bit is enough for a partial match' => [
                0b1011,
                0b0110,
                'expected' => true,
            ],
            'disjoint masks share no bits' => [
                0b1011,
                0b0100,
                'expected' => false,
            ],
            'two masks containing only bit 63 overlap' => [
                PHP_INT_MIN,
                PHP_INT_MIN,
                'expected' => true,
            ],
            'sharing bit 63 is enough even when bit 0 is missing' => [
                PHP_INT_MIN,
                PHP_INT_MIN | 1,
                'expected' => true,
            ],
            'two full masks overlap across all 64 bits' => [
                -1,
                -1,
                'expected' => true,
            ],
        ];
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
        self::assertSame($expectedMask, $this->bitmask->getMask());

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
