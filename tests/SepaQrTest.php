<?php
declare(strict_types=1);

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

    public function testCreate()
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
