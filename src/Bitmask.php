<?php

declare(strict_types=1);

namespace Aliance\Bitmask;

/**
 * Simple bitmask implementation.
 * Supports only 64 bits (from 0 to 63) on x64 platforms.
 */
class Bitmask
{
    private const MAX_BIT = 63;

    private int $mask;

    public function __construct(int $mask = 0)
    {
        $this->setMask($mask);
    }

    public function setBit(int $bit): self
    {
        return $this->addMask(1 << $this->checkBit($bit));
    }

    public function addMask(int $mask): self
    {
        $this->mask |= $mask;
        return $this;
    }

    public function unsetBit(int $bit): self
    {
        return $this->deleteMask(1 << $this->checkBit($bit));
    }

    public function deleteMask(int $mask): self
    {
        $this->mask &= ~$mask;
        return $this;
    }

    public function issetBit(int $bit): bool
    {
        return (bool) ($this->getMask() & (1 << $this->checkBit($bit)));
    }

    public function getMask(): int
    {
        return $this->mask;
    }

    public function setMask(int $mask): self
    {
        $this->mask = $mask;
        return $this;
    }

    /**
     * Return set bits count.
     * Actually, counts the number of 1 in binary representation of the decimal mask integer.
     */
    public function getSetBitsCount(): int
    {
        return substr_count(decbin($this->mask), '1');
    }

    private function checkBit(int $bit): int
    {
        if ($bit > self::MAX_BIT || $bit < 0) {
            throw new \InvalidArgumentException(sprintf(
                'Bit number %d is out of range [0..%d].',
                $bit,
                self::MAX_BIT
            ));
        }
        return $bit;
    }
}
