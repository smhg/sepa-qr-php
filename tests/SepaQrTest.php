<?php
namespace SepaQr\Test;

use SepaQr\SepaQr;

class SepaQrTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        new SepaQr();
    }

    public function testGet()
    {
        $qrCode = new SepaQr();

        $qrCode->setName('Test')
            ->setIban('ABC')
            ->get();
    }
}
