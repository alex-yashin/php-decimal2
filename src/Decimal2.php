<?php

namespace Decimal2;

use RuntimeException;
use function intval;
use function is_float;
use function is_int;
use function is_string;

class Decimal2
{

    const DECIMALS = 2;

    private $value = null;

    public function __construct($value = 0)
    {
        if (is_string($value) && strpos($value, '.')) {
            list($l, $r) = explode('.', $value, 2);
            $r = strlen($r) > 2 ? substr($r, 0, 2) : str_pad($r, 2, '0');
            $this->value = (int)$l * (10 ** self::DECIMALS) + intval($r);
        } elseif (is_string($value)) {
            $this->value = (int)$value * (10 ** self::DECIMALS);
        } elseif (is_int($value)) {
            $this->value = ($value * (10 ** self::DECIMALS));
        } elseif (is_float($value)) {
            $this->value = (int)floor(round($value * (10 ** self::DECIMALS), 8));
        } elseif (is_null($value)) {
            $this->value = 0;
        } else {
            throw new RuntimeException('Wrong initial data');
        }
        if (is_string($value) && (strncmp($value, '-', 1) === 0)) {
            $this->value *= -1;
        }

    }

    /**
     *
     * @param int $value
     * @return Decimal2
     */
    private static function makeByValue(int $value): Decimal2
    {
        $dec = new Decimal2();
        $dec->setValue($value);
        return $dec;
    }

    private function setValue(int $value)
    {
        $this->value = $value;
    }

    /**
     *
     * @param Decimal2 $value
     * @return Decimal2
     */
    public function add(Decimal2 $value): Decimal2
    {
        return static::makeByValue($this->value + $value->value);
    }

    /**
     *
     * @param float $value
     * @return Decimal2
     */
    public function addFloat($value): Decimal2
    {
        return static::makeByValue($this->value + intval($value * pow(10, self::DECIMALS)));
    }

    /**
     *
     * @param Decimal2 $value
     * @return Decimal2
     */
    public function sub(Decimal2 $value): Decimal2
    {
        return static::makeByValue($this->value - $value->value);
    }

    /**
     *
     * @param float $value
     * @return Decimal2
     */
    public function subFloat($value): Decimal2
    {
        return static::makeByValue($this->value - intval($value * pow(10, self::DECIMALS)));
    }

    /**
     *
     * @param Decimal2 $value
     * @return Decimal2
     */
    public function mul(Decimal2 $value): Decimal2
    {
        $resultValue = intdiv($this->value * $value->value, pow(10, self::DECIMALS));
        return static::makeByValue($resultValue);
    }

    /**
     *
     * @param float $value
     * @return Decimal2
     */
    public function mulFloat($value): Decimal2
    {
        return static::makeByValue(intval($this->value * $value));
    }

    /**
     *
     * @param Decimal2 $value
     * @return Decimal2
     */
    public function div(Decimal2 $value): Decimal2
    {
        $resultValue = intdiv($this->value * pow(10, self::DECIMALS), $value->value);
        return static::makeByValue($resultValue);
    }

    /**
     *
     * @param float $value
     * @return Decimal2
     */
    public function divFloat($value): Decimal2
    {
        return static::makeByValue($this->value / $value);
    }
    
    /**
     * Делит одно число на другое и получает результат в виде float
     * @param Decimal2 $value
     * @return float
     */
    public function divToFloat($value): float
    {
        return $this->value / $value->value;
    }

    /**
     * 
     * @param Decimal2 $value
     * @return bool
     */
    public function isDivisibleBy(Decimal2 $value): bool
    {
        return empty($this->value % $value->value);
    }

    /**
     *
     * @param Decimal2 $value
     * @return int
     */
    public function compare(Decimal2 $value): int
    {
        return $this->value <=> $value->value;
    }

    /**
     *
     * @param Decimal2 $dec
     * @return bool
     */
    public function equal(Decimal2 $dec): bool
    {
        return $this->value == $dec->value;
    }

    /**
     *
     * @param Decimal2 $dec
     * @return bool
     */
    public function notEqual(Decimal2 $dec): bool
    {
        return $this->value != $dec->value;
    }

    /**
     *
     * @param Decimal2 $dec
     * @return bool
     */
    public function greaterThan(Decimal2 $dec): bool
    {
        return $this->value > $dec->value;
    }

    /**
     *
     * @param Decimal2 $dec
     * @return bool
     */
    public function greaterOrEqual(Decimal2 $dec): bool
    {
        return $this->value >= $dec->value;
    }

    /**
     *
     * @param Decimal2 $dec
     * @return bool
     */
    public function lessThan(Decimal2 $dec): bool
    {
        return $this->value < $dec->value;
    }

    /**
     *
     * @param Decimal2 $dec
     * @return bool
     */
    public function lessOrEqual(Decimal2 $dec): bool
    {
        return $this->value <= $dec->value;
    }
    
    /**
     *
     * @return Decimal2
     */
    public function abs(): Decimal2
    {
        return static::makeByValue(abs($this->value));
    }

    /**
     *
     * @return Decimal2
     */
    public function floor(): Decimal2
    {
        return new Decimal2($this->floorToInt());
    }

    /**
     *
     * @return Decimal2
     */
    public function ceil(): Decimal2
    {
        return new Decimal2($this->ceilToInt());
    }

    /**
     *
     * @return Decimal2
     */
    public function round(): Decimal2
    {
        return new Decimal2($this->roundToInt());
    }

    /**
     *
     * @return int
     */
    public function floorToInt(): int
    {
        return intval(floor($this->value / pow(10, self::DECIMALS)));
    }

    /**
     *
     * @return int
     */
    public function ceilToInt(): int
    {
        return intval(ceil($this->value / pow(10, self::DECIMALS)));
    }

    /**
     *
     * @return int
     */
    public function roundToInt(): int
    {
        return intval(round($this->value / pow(10, self::DECIMALS)));
    }

    /**
     *
     * @param Decimal2 $dec1
     * @param Decimal2 $dec2
     * @return Decimal2
     */
    public static function min(Decimal2 $dec1, Decimal2 $dec2): Decimal2
    {
        return static::makeByValue(min($dec1->value, $dec2->value));
    }

    /**
     *
     * @param Decimal2 $dec1
     * @param Decimal2 $dec2
     * @return Decimal2
     */
    public static function max(Decimal2 $dec1, Decimal2 $dec2): Decimal2
    {
        return static::makeByValue(max($dec1->value, $dec2->value));
    }

    /**
     *
     * @return string
     */
    public function toString(): string
    {
        $str = str_pad(abs($this->value), self::DECIMALS + 1, '0', STR_PAD_LEFT);
        if ($this->value < 0) {
            $str = '-' . $str;
        }
        return substr($str, 0, strlen($str) - self::DECIMALS) . '.' . substr($str, -self::DECIMALS);
    }

}
