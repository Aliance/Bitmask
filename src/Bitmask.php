<?php
declare(strict_types=1);

namespace Aliance\Bitmask;

/**
 * Simple bitmask implementation.
 * Supports only 64 bits (from 0 to 63) on x64 platforms.
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
    public function __construct(int $mask = 0)
    {
        $this->setMask($mask);
    }

    /**
     * @param int $mask
     * @return self
     */
    public static function create(int $mask = 0): self
    {
        return new self($mask);
    }

    /**
     * @param int $bit
     * @return self
     */
    public function setBit(int $bit): self
    {
        return $this->addMask(1 << $this->checkBit($bit));
    }

    /**
     * @param int $mask
     * @return self
     */
    public function addMask(int $mask): self
    {
        $this->mask |= $mask;
        return $this;
    }

    /**
     * @param int $bit
     * @return int
     * @throws \InvalidArgumentException
     */
    private function checkBit(int $bit): int
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
     * @return self
     */
    public function unsetBit(int $bit): self
    {
        return $this->deleteMask(1 << $this->checkBit($bit));
    }

    /**
     * @param int $mask
     * @return self
     */
    public function deleteMask(int $mask): self
    {
        $this->mask &= ~$mask;
        return $this;
    }

    /**
     * @param int $bit
     * @return bool
     */
    public function issetBit(int $bit): bool
    {
        return (bool)($this->getMask() & (1 << $this->checkBit($bit)));
    }

    /**
     * @return int
     */
    public function getMask(): int
    {
        return $this->mask;
    }

    /**
     * @param int $mask
     * @return self
     */
    public function setMask(int $mask): self
    {
        $this->mask = $mask;
        return $this;
    }

    /**
     * Return set bits count.
     * Actually, counts the number of 1 in binary representation of the decimal mask integer.
     * @return int
     */
    public function getSetBitsCount(): int
    {
        return substr_count(decbin($this->mask), '1');
    }
}
