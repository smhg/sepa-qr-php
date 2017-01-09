# sepa-qr-php
Generates SEPA QR codes based on the [European Payments Council's standard](http://www.europeanpaymentscouncil.eu/index.cfm/knowledge-bank/epc-documents/quick-response-code-guidelines-to-enable-data-capture-for-the-initiation-of-a-sepa-credit-transfer/epc069-12-quick-response-code-guidelines-to-enable-data-capture-for-the-initiation-of-a-sepa-credit-transfer1/). These QR codes are scannable by many mobile banking apps. Because app support is at a decent level, it is a good idea to print such a code on an invoice.

Extends the [QrCode](https://github.com/endroid/QrCode) library preserving all its functionality in case lower level QR code manipulation is needed.

## Installation
```bash
composer require smhg/sepa-qr
```

## Example
```php
use SepaQr\SepaQr;

$sepaQr = new SepaQr();

$sepaQr
  ->setName('Name of the beneficiary')
  ->setIban('BE123456789123456789')
  ->setAmount(100) // The standard (currently?) only supports Euro
  ->setRemittanceText('Invoice 123456789')
  ->setSize(300);

$tmpFileName = tempnam("/tmp");
$tmpFile = fopen($tmpFileName, "w");
fwrite($tmpFile, $sepaQr->get());
fclose($tmpFile);
```
## Methods

### setServiceTag($serviceTag = 'BCD')
### setVersion($version = 2)
### setCharacterSet($characterSet = SepaQr::UTF_8)
### setIdentification($identification = 'SCT')
### setBic($bic)
### setName($name)
### setIban($iban)
### setAmount($amount)
### setPurpose($purpose)
### setRemittanceReference($remittanceReference)
### setRemittanceText($remittanceText)
### setInformation($information)
