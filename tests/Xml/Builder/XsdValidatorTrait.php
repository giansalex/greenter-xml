<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 24/01/2018
 * Time: 10:14 AM
 */

namespace Tests\Greenter\Xml\Builder;
use Greenter\Ubl\SchemaValidator;
use Greenter\Ubl\SchemaValidatorInterface;

/**
 * Trait XsdValidatorTrait
 * @method assertTrue($state)
 */
trait XsdValidatorTrait
{

    public function assertSchema($xml, $version = '2.0')
    {
        $validator = $this->getValidator($version);

        $success = $validator->validate($xml);

        if ($success === false) {
            echo $validator->getMessage().PHP_EOL;
        }

        $this->assertTrue($success);
    }

    public function assertSchemaV21($xml)
    {
        $doc = new \DOMDocument();
        $doc->loadXML($xml);
        $items = $doc->getElementsByTagNameNS('urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2','ExtensionContent');

        if ($items->length > 0) {
            $node = $doc->createElement('sign', '');
            $items->item(0)->appendChild($node);
            $xml = $doc->saveXML();
        }

        $this->assertSchema($xml, '2.1');
    }

    /**
     * @param string $version
     * @return SchemaValidatorInterface
     */
    private function getValidator($version = '2.0')
    {
        $validator = new SchemaValidator();
        $validator->setVersion($version);

        return $validator;
    }
}