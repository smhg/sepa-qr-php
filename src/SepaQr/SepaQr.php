<?php
namespace SepaQr;

use Endroid\QrCode\QrCode;

class SepaQr extends QrCode
{
    const UTF_8 = 1;
    const ISO8859_1 = 2;
    const ISO8859_2 = 3;
    const ISO8859_4 = 4;
    const ISO8859_5 = 5;
    const ISO8859_7 = 6;
    const ISO8859_10 = 7;
    const ISO8859_15 = 8;

    private $sepaValues = array(
        'serviceTag' => 'BCD',
        'version' => 2,
        'characterSet' => 1,
        'identification' => 'SCT'
    );

    public function __construct($text = '')
    {
        parent::__construct($text);

        $this->setErrorCorrection(self::LEVEL_MEDIUM);
    }

    public function setServiceTag($serviceTag = 'BCD')
    {
        if ($serviceTag !== 'BCD') {
            throw new Exception('Invalid service tag');
        }

        $this->sepaValues['serviceTag'] = $serviceTag;

        return $this;
    }

    public function setVersion($version = 2)
    {
        $version = (int)$version;

        if (!in_array($version, range(1, 2))) {
            throw new Exception('Invalid version');
        }

        $this->sepaValues['version'] = $version;

        return $this;
    }

    public function setCharacterSet($characterSet = self::UTF_8)
    {
        $characterSet = (int)$characterSet;

        if (!in_array($characterSet, range(1, 8))) {
            throw new Exception('Invalid character set');
        }

        $this->sepaValues['characterSet'] = $characterSet;
        return $this;
    }

    public function setIdentification($identification = 'SCT')
    {
        if ($identification !== 'SCT') {
            throw new Exception('Invalid identification code');
        }

        $this->sepaValues['identification'] = $identification;

        return $this;
    }

    public function setBic($bic)
    {
        $this->sepaValues['bic'] = $bic;
        return $this;
    }

    public function setName($name)
    {
        $this->sepaValues['name'] = $name;
        return $this;
    }

    public function setIban($iban)
    {
        $this->sepaValues['iban'] = $iban;
        return $this;
    }

    public function setAmount($amount)
    {
        $this->sepaValues['amount'] = $amount;
        return $this;
    }

    public function setPurpose($purpose)
    {
        $this->sepaValues['purpose'] = $purpose;
        return $this;
    }

    public function setRemittanceReference($remittanceReference)
    {
        if (isset($this->sepaValues['remittanceText'])) {
            throw new Exception('Use either structured or unstructured remittance information');
        }

        $this->sepaValues['remittanceReference'] = $remittanceReference;
        return $this;
    }

    public function setRemittanceText($remittanceText)
    {
        if (isset($this->sepaValues['remittanceReference'])) {
            throw new Exception('Use either structured or unstructured remittance information');
        }

        $this->sepaValues['remittanceText'] = $remittanceText;
        return $this;
    }

    public function setInformation($information)
    {
        $this->sepaValues['information'] = $information;
        return $this;
    }

    public function validateSepaValues($values)
    {
        if ($values['version'] === 1 && !$values['bic']) {
            throw new Exception('Missing BIC of the beneficiary bank');
        }

        if ($values['bic']) {
            if (strlen($values['bic']) < 8) {
                throw new Exception('BIC of the beneficiary bank cannot be shorter than 8 characters');
            }

            if (strlen($values['bic']) > 11) {
                throw new Exception('BIC of the beneficiary bank cannot be longer than 11 characters');
            }
        }

        if (!$values['name']) {
            throw new Exception('Missing name of the beneficiary');
        }

        if (strlen($values['name']) > 70) {
            throw new Exception('Name of the beneficiary cannot be longer than 70 characters');
        }

        if (!$values['iban']) {
            throw new Exception('Missing account number of the beneficiary');
        }

        if (strlen($values['iban']) > 34) {
            throw new Exception('Account number of the beneficiary cannot be longer than 34 characters');
        }

        if ($values['amount']) {
            if ($values['amount'] < 0.01) {
                throw new Exception('Amount of the credit transfer cannot be smaller than 0.01 Euro');
            }

            if ($values['amount'] > 999999999.99) {
                throw new Exception('Amount of the credit transfer cannot be higher than 999999999.99 Euro');
            }
        }

        if ($values['remittanceReference'] && strlen($values['remittanceReference']) > 35) {
            throw new Exception('Structured remittance information cannot be longer than 35 characters');
        }

        if ($values['remittanceText'] && strlen($values['remittanceText']) > 140) {
            throw new Exception('Unstructured remittance information cannot be longer than 140 characters');
        }

        if ($values['information'] && strlen($values['information']) > 70) {
            throw new Exception('Beneficiary to originator information cannot be longer than 70 characters');
        }
    }

    public function create()
    {
        $defaults = array(
            'bic' => '',
            'name' => '',
            'iban' => '',
            'amount' => 0.01,
            'purpose' => '',
            'remittanceReference' => '',
            'remittanceText' => '',
            'information' => ''
        );

        $values = array_merge($defaults, $this->sepaValues);

        $this->validateSepaValues($values);

        $this->setText(implode("\n", array(
            $values['serviceTag'],
            sprintf('%03d', $values['version']),
            $values['characterSet'],
            $values['identification'],
            $values['bic'],
            $values['name'],
            $values['iban'],
            sprintf('EUR%.2f', $values['amount']),
            $values['purpose'],
            $values['remittanceReference'] ? $values['remittanceReference'] : $values['remittanceText'],
            $values['information']
        )));

        return parent::create();
    }
}
