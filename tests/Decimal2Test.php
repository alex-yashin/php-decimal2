<?php

use PHPUnit\Framework\TestCase;
use Decimal2\Decimal2;

class DecimalTest extends TestCase
{

    public function testRemoveBuildingType()
    {
        $dec1 = new Decimal2(0.1);
        $dec2 = new Decimal2(0.2);

        $this->assertSame('0.30', $dec1->add($dec2)->toString());
        $this->assertSame('-0.10', $dec1->sub($dec2)->toString());
        $this->assertSame('0.02', $dec1->mul($dec2)->toString());
        $this->assertSame('0.50', $dec1->div($dec2)->toString());
        $this->assertSame('2.00', $dec2->div($dec1)->toString());

        $this->assertSame('0.43', $dec1->addFloat(0.3333333)->toString());
        $this->assertSame('0.03', $dec1->mulFloat(0.3333333)->toString());
        $this->assertSame('0.30', $dec1->divFloat(0.3333333)->toString());

        $this->assertFalse($dec1->greaterThan($dec2));
        $this->assertTrue($dec2->greaterThan($dec1));
        $this->assertFalse($dec2->lessThan($dec1));
        $this->assertTrue($dec1->lessThan($dec2));
        $this->assertFalse($dec2->lessThan($dec2));
        $this->assertFalse($dec1->lessThan($dec1));
        $this->assertTrue($dec1->lessOrEqual($dec1));
        $this->assertTrue($dec1->greaterOrEqual($dec1));
        $this->assertTrue($dec1->equal($dec1));

        $dec = new Decimal2(4.56);
        $this->assertSame('4.56', $dec->toString());

        $dec = new Decimal2('369.05');
        $this->assertSame('369.05', $dec->toString());
        $dec = new Decimal2('343.4');
        $this->assertSame('343.40', $dec->toString());
        $dec = new Decimal2('343.');
        $this->assertSame('343.00', $dec->toString());
        $dec = new Decimal2('343.005');
        $this->assertSame('343.00', $dec->toString());
        $dec = new Decimal2('343.0005');
        $this->assertSame('343.00', $dec->toString());
        $dec = new Decimal2('343.1005');
        $this->assertSame('343.10', $dec->toString());
        $dec = new Decimal2('343.0105');
        $this->assertSame('343.01', $dec->toString());

        $empty = new Decimal2();
        $this->assertSame('0.00', $empty->toString());


        $this->assertSame('0.10', Decimal2::min($dec1, $dec2)->toString());
        $this->assertSame('0.20', Decimal2::max($dec1, $dec2)->toString());

        $this->assertSame(0, $dec1->floorToInt());
        $this->assertSame(2, $dec2->div($dec1)->floorToInt());
        $this->assertSame(2, $dec2->div($dec1)->ceilToInt());
        $this->assertSame(2, $dec2->div($dec1)->add($dec1)->floorToInt());
        $this->assertSame(3, $dec2->div($dec1)->add($dec1)->ceilToInt());
        $this->assertSame('2.00', $dec2->div($dec1)->add($dec1)->floor()->toString());
        $this->assertSame('3.00', $dec2->div($dec1)->add($dec1)->ceil()->toString());
        $this->assertSame('0.01', (new Decimal2(1))->div(new Decimal2(100))->toString());

        $this->assertSame(2, $dec2->divToFloat($dec1));
        $this->assertSame(1, $dec2->divToFloat($dec2));
    }

    public function testNegativeValues()
    {
        $dec1 = new Decimal2(-0.1);
        $dec2 = new Decimal2(-0.2);

        $this->assertSame('-0.30', $dec1->add($dec2)->toString());
        $this->assertSame('0.10', $dec1->sub($dec2)->toString());

        $dec1 = new Decimal2("-0.10");
        $dec2 = new Decimal2("-0.20");

        $this->assertSame('-0.30', $dec1->add($dec2)->toString());
        $this->assertSame('0.10', $dec1->sub($dec2)->toString());
    }

}
