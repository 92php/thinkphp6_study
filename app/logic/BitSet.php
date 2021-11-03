<?php

namespace app\logic;

/**
 * BitSet 模拟BitSet 在PHP中可以使用Array代替
 */
class BitSet
{
    protected $bit = [];

    public function add($index)
    {
        $this->bit[$index] = 1;
    }

    public function has($index)
    {
        if (isset($this->bit[$index])) {
            return true;
        }
        return false;
    }
}

