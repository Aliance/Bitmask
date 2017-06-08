<?php
namespace Aliance\Bitmask;

/**
 * Simple bitmask implementation.
 * Supports only 63 bits (from 0 to 62) on x64 platforms.
 */
class Bitmask
{
    /**
     * @var int
     */
    const MAX_BIT = 63;

    /**
     * @var int
     */
    private $mask;

    /**
     * @param int $mask
     */
    public function __construct($mask = 0)
    {
        $this->setMask($mask);
    }

    /**
     * @param int $mask
     * @return $this
     */
    public static function create($mask = 0)
    {
        return new self($mask);
    }

    /**
     * @param int $bit
     * @return $this
     */
    public function setBit($bit)
    {
        return $this->addMask(1 << $this->checkBit($bit));
    }

    /**
     * @param int $mask
     * @return $this
     */
    public function addMask($mask)
    {
        $this->mask |= $mask;
        return $this;
    }

    /**
     * @param int $bit
     * @return int
     * @throws \InvalidArgumentException
     */
    private function checkBit($bit)
    {
        if ($bit > self::MAX_BIT) {
            throw new \InvalidArgumentException(sprintf(
                'Bit number %d is greater than possible limit %d.',
                $bit,
                self::MAX_BIT
            ));
        }
        return $bit;
    }

    /**
     * @param int $bit
     * @return $this
     */
    public function unsetBit($bit)
    {
        return $this->deleteMask(1 << $this->checkBit($bit));
    }

    /**
     * @param int $mask
     * @return $this
     */
    public function deleteMask($mask)
    {
        $this->mask &= ~$mask;
        return $this;
    }

    /**
     * @param int $bit
     * @return bool
     */
    public function issetBit($bit)
    {
        return (bool)($this->getMask() & (1 << $this->checkBit($bit)));
    }

    /**
     * @return int
     */
    public function getMask()
    {
        return $this->mask;
    }

    /**
     * @param int $mask
     * @return $this
     */
    public function setMask($mask)
    {
        $this->mask = (int)$mask;
        return $this;
    }

    /**
     * Return set bits count.
     * Actually, counts the number of 1 in binary representation of the decimal mask integer.
     * @return int
     */
    public function getSetBitsCount()
    {
        return substr_count(decbin($this->mask), '1');
    }
}
