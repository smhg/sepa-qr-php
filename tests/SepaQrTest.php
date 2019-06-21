<?php
namespace SepaQr\Test;

use PHPUnit\Framework\TestCase;
use SepaQr\SepaQr;
use SepaQr\Exception;

class SepaQrTest extends TestCase
{
    public function testConstructor(): void
    {
        $this->assertInstanceOf(
            SepaQr::class,
            new SepaQr()
        );
    }

    public function testSetCharacterSet(): void
    {
        $qrCode = new SepaQr();

        $qrCode->setCharacterSet(SepaQr::UTF_8);

        $this->expectException('SepaQr\Exception');

        $qrCode->setCharacterSet('UTF8');
    }

    public function testSetRemittance(): void
    {
        $qrCode = new SepaQr();

        $this->expectException('SepaQr\Exception');

        $qrCode->setRemittanceReference('ABC')
            ->setRemittanceText('DEF');
    }

    public function testEncodeMessage(): void
    {
        $qrCode = new SepaQr();

        $qrCode->setName('Test')
            ->setIban('ABC')
            ->setAmount(1075.25)
            ->setRemittanceText('DEF');

        $message = $qrCode->encodeMessage();

        $this->assertTrue(
            stristr($message, '1075.25') !== false,
            'The amount should be formatted using only a dot (.) as the decimal separator'
        );

        $this->assertEquals(
            11,
            count(explode("\n", $message)),
            'The last populated element cannot be followed by any character or element separator'
        );

        $this->assertTrue(
            substr($message, strlen($message) - 3) === 'DEF',
            'The last populated element cannot be followed by any character or element separator'
        );

        $expectedString = <<<EOF
BCD
002
1
SCT

Test
ABC
EUR1075.25


DEF
EOF;

        $this->assertSame($expectedString, $message);
    }

    public function testGetWriter(): void
    {
        $qrCode = new SepaQr();

        $this->assertIsString(
            $qrCode->setName('Test')
                ->setIban('ABC')
                ->writeString()
        );
    }

    public function testSetVersionExceptionCase1(): void
    {
        $this->expectException(Exception::class);

        $qrCode = new SepaQr();
        $qrCode->setVersion(3);
    }

    public function testSetVersionExceptionCase2(): void
    {
        $this->expectException(\TypeError::class);

        $qrCode = new SepaQr();
        $qrCode->setVersion('v1');
    }
}
