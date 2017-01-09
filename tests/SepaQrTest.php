<?php
namespace SepaQr\Test;

use SepaQr\SepaQr;

class SepaQrTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        new SepaQr();
    }

    public function testSetCharacterSet()
    {
        $qrCode = new SepaQr();

        $qrCode->setCharacterSet(SepaQr::UTF_8);

        $this->setExpectedException('SepaQr\Exception');
        $qrCode->setCharacterSet('UTF8');
    }

    public function testSetRemittance()
    {
        $qrCode = new SepaQr();

        $this->setExpectedException('SepaQr\Exception');

        $qrCode->setRemittanceReference('ABC')
            ->setRemittanceText('DEF');
    }

    public function testCreate()
    {
        $qrCode = new SepaQr();

        $qrCode->setName('Test')
            ->setIban('ABC')
            ->get();
    }
}
