<?php
namespace SepaQr\Test;

use PHPUnit\Framework\TestCase;
use SepaQr\SepaQr;

class SepaQrTest extends TestCase
{
    public function testConstructor()
    {
        $this->assertInstanceOf(
            SepaQr::class,
            new SepaQr()
        );
    }

    public function testSetCharacterSet()
    {
        $qrCode = new SepaQr();

        $qrCode->setCharacterSet(SepaQr::UTF_8);

        $this->expectException('SepaQr\Exception');

        $qrCode->setCharacterSet('UTF8');
    }

    public function testSetRemittance()
    {
        $qrCode = new SepaQr();

        $this->expectException('SepaQr\Exception');

        $qrCode->setRemittanceReference('ABC')
            ->setRemittanceText('DEF');
    }

    public function testEncodeMessage()
    {
        $qrCode = new SepaQr();

        $qrCode->setName('Test')
            ->setIban('ABC')
            ->setRemittanceText('DEF');

        $message = $qrCode->encodeMessage();

        $this->assertEquals(
            11,
            count(explode("\n", $message)),
            'The last populated element cannot be followed by any character or element separator'
        );

        $this->assertTrue(
            substr($message, strlen($message) - 3) === 'DEF',
            'The last populated element cannot be followed by any character or element separator'
        );
    }

    public function testGetWriter()
    {
        $qrCode = new SepaQr();

        $this->assertInternalType(
            'string',
            $qrCode->setName('Test')
                ->setIban('ABC')
                ->writeString()
        );
    }
}
