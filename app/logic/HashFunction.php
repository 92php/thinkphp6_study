<?php

namespace app\logic;

/**
 * Hash算法
 */
class HashFunction
{
    protected $size;
    protected $seed;

    public function __construct($size, $seed)
    {
        $this->size = $size;
        $this->seed = $seed;
    }

    public function hash($value)
    {
        $r = 0;
        $l = strlen($value);
        for ($i = 0; $i < $l; $i++) {
            $r = $this->seed * $r + ord($value[$i]);
        }
        return ($this->size - 1) & $r;
    }
}

