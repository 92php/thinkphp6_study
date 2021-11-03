<?php

namespace app\logic;

/**
 * 布隆过滤器
 */
class BloomFilter
{

    /** @var int 一个长度为10 亿的比特位 */
    protected $size = 256 << 22;

    /** @var int[] 为了降低错误率，使用加法hash算法，所以定义一个8个元素的质数数组 */
    protected $seeds = [3, 5, 7, 11, 13, 31, 37, 61];

    /** @var HashFunction[] 相当于构建 8 个不同的hash算法 */
    protected $functions = [];

    /** @var BitSet[] 初始化布隆过滤器的 bitmap */
    protected $bitset = [];

    public function __construct($size = null, $seeds = null)
    {
        if ($seeds != null) {
            $this->size = $size;
        }
        if ($seeds != null) {
            $this->seeds = $seeds;
        }
        foreach ($this->seeds as $v) {
            $this->functions[$v] = new HashFunction($this->size, $v);
            $this->bitset[$v] = new BitSet();
        }
    }

    /**
     * @param string $str
     */
    public function add($str)
    {
        if ($str != null) {
            foreach ($this->functions as $k => $fun) {
                $this->bitset[$k]->add($fun->hash($str));
            }
        }
    }

    /**
     * @param string $str
     * @return bool
     */
    public function has($str)
    {
        if ($str != null) {
            foreach ($this->functions as $k => $fun) {
                if (!$this->bitset[$k]->has($fun->hash($str))) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }
}


