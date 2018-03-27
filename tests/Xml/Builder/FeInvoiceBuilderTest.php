<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 16/07/2017
 * Time: 22:54
 */

namespace Tests\Greenter\Xml\Builder;

use Greenter\Model\Sale\Invoice;

/**
 * Class FeInvoiceBuilderTest
 * @package Tests\Greenter\Xml\Builder
 */
class FeInvoiceBuilderTest extends \PHPUnit_Framework_TestCase
{
    use FeBuilderTrait;
    use XsdValidatorTrait;

    public function testCreateXmlInvoice()
    {
        $invoice = $this->getFullInvoice();
        $this->loadExtras($invoice);

        $xml = $this->build($invoice);

//        file_put_contents('x.xml', $xml);
        $this->assertNotEmpty($xml);
        $this->assertSchema($xml);
    }

    public function testCompanyValidate()
    {
        $company = $this->getCompany();
        $adress = $company->getAddress();

        $this->assertNotNull($company->getAddress());
        $this->assertNotEmpty($company->getNombreComercial());
        $this->assertNotEmpty($company->getRazonSocial());
        $this->assertNotEmpty($company->getRuc());
        $this->assertNotEmpty($adress->getDepartamento());
        $this->assertNotEmpty($adress->getProvincia());
        $this->assertNotEmpty($adress->getDistrito());
        $this->assertNotEmpty($adress->getUrbanizacion());
    }

    public function testInvoiceFilename()
    {
        $invoice = $this->getInvoice();
        $filename = $invoice->getName();

        $this->assertEquals($this->getFilename($invoice), $filename);
    }

    private function getFileName(Invoice $invoice)
    {
        $parts = [
            $invoice->getCompany()->getRuc(),
            $invoice->getTipoDoc(),
            $invoice->getSerie(),
            $invoice->getCorrelativo(),
        ];

        return join('-', $parts);
    }

    private function loadExtras(Invoice $invoice)
    {
        $invoice->getCompany()
            ->setEmail('admin@corp.com')
            ->setTelephone('001-123243');

        $invoice->getClient()
            ->setEmail('client@corp.com')
            ->setTelephone('001-445566');

        $invoice->setSeller($this->getClient()
        ->setTipoDoc('0')
        ->setNumDoc('00000000')
        ->setRznSocial('Super Seller')
        ->setEmail('seller@corp.com')
        ->setTelephone('990134255'));

    }
}