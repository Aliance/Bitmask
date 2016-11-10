<?php
use Aliance\Bitmask\Bitmask;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Unit tests for simple bitmask implementation.
 */
class BitmaskTest extends TestCase
{
    /**
     * @var Bitmask
     */
    private $Bitmask;

    public function testEmptyBitmask()
    {
        $this->assertEquals(0, $this->Bitmask->getSetBitsCount());
        $this->assertEquals(0, $this->Bitmask->getMask());
        $this->assertFalse($this->Bitmask->issetBit(1));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testThatTooBigBitCauseAnException()
    {
        $this->Bitmask->setBit(Bitmask::MAX_BIT + 1);
    }

    public function testMask()
    {
        $this->assertEquals(0, $this->Bitmask->getMask());
        $this->Bitmask->setMask(1024);
        $this->assertEquals(1024, $this->Bitmask->getMask());
    }

    public function testBits()
    {
        $this->assertFalse($this->Bitmask->issetBit(3));
        $this->assertFalse($this->Bitmask->issetBit(5));
        $this->assertFalse($this->Bitmask->issetBit(12));

        $this->Bitmask->setBit(3);
        $this->Bitmask->setBit(12);

        $this->assertTrue($this->Bitmask->issetBit(3));
        $this->assertFalse($this->Bitmask->issetBit(5));
        $this->assertTrue($this->Bitmask->issetBit(12));

        $this->Bitmask->unsetBit(12);

        $this->assertTrue($this->Bitmask->issetBit(3));
        $this->assertFalse($this->Bitmask->issetBit(5));
        $this->assertFalse($this->Bitmask->issetBit(12));
    }

    /**
     * @dataProvider getBitsWithMaskPairs
     * @param int[] $bits
     * @param int   $expectedMask
     */
    public function testGeneralUsage($bits, $expectedMask)
    {
        foreach ($bits as $bit) {
            $this->Bitmask->setBit($bit);
        }

        $this->assertCount($this->Bitmask->getSetBitsCount(), $bits);
        $this->assertEquals($expectedMask, $this->Bitmask->getMask());

        foreach ($bits as $bit) {
            $this->assertTrue($this->Bitmask->issetBit($bit));
        }
    }

    /**
     * @return array
     */
    public function getBitsWithMaskPairs()
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
                range(0, Bitmask::MAX_BIT),
                PHP_INT_MAX, // 9223372036854775807 for x64
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->Bitmask = new Bitmask();
    }
}
