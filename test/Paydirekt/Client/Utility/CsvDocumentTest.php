<?php


namespace Paydirekt\Client\Utility;


class CsvDocumentTest extends \PHPUnit_Framework_TestCase
{

    public function testThatHeaderIsParsedCorrect()
    {
        $csvDocumentAsString = "product_name,merchant_id,price\n" .
            "Bobbycar,1015,25.99\n" .
            "Helm,1015,18.53\n";
        $csvDocument = new CsvDocument($csvDocumentAsString);

        $this->assertNotNull($csvDocument->getHeader());
        $this->assertEquals(['product_name', 'merchant_id', 'price'], $csvDocument->getHeader());
    }

    public function testThatDataIsParsedCorrect()
    {
        $csvDocumentAsString = "product_name,merchant_id,price\n" .
            "Bobbycar,1015,25.99\n" .
            "Helm,1015,18.53\n";
        $csvDocument = new CsvDocument($csvDocumentAsString);

        $this->assertNotNull($csvDocument->getData());
        $this->assertTrue(is_array($csvDocument->getData()));
        $this->assertEquals(2, count($csvDocument->getData()));

        $firstLine = $csvDocument->getData()[0];
        $this->assertNotNull($firstLine);
        $this->assertTrue(is_array($firstLine));
        $this->assertEquals(3, count($firstLine));
        $this->assertEquals('Bobbycar', $firstLine['product_name']);
        $this->assertEquals('1015', $firstLine['merchant_id']);
        $this->assertEquals('25.99', $firstLine['price']);

        $secondLine = $csvDocument->getData()[1];
        $this->assertNotNull($secondLine);
        $this->assertTrue(is_array($secondLine));
        $this->assertEquals(3, count($secondLine));
        $this->assertEquals('Helm', $secondLine['product_name']);
        $this->assertEquals('1015', $secondLine['merchant_id']);
        $this->assertEquals('18.53', $secondLine['price']);
    }

    public function testThatEmptyStringIsParsedCorrect()
    {
        $csvDocumentAsString = "";
        $csvDocument = new CsvDocument($csvDocumentAsString);

        $this->assertNotNull($csvDocument->getHeader());
        $this->assertEmpty($csvDocument->getHeader());
        $this->assertNotNull($csvDocument->getData());
        $this->assertEmpty($csvDocument->getData());
    }

    public function testThatEmptyCsvDocumentIsParsedCorrect()
    {
        $csvDocumentAsString = "product_name,merchant_id,price\n";
        $csvDocument = new CsvDocument($csvDocumentAsString);

        $this->assertNotNull($csvDocument->getHeader());
        $this->assertEquals(['product_name', 'merchant_id', 'price'], $csvDocument->getHeader());

        $this->assertTrue(is_array($csvDocument->getData()));
        $this->assertEmpty($csvDocument->getData());
    }
}