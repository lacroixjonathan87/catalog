<?php

use AppBundle\Entity\Product;

include_once('EntityTestCase.php');

class ProductTest extends EntityTestCase
{
    /**
     * @param $field
     * @param $value
     * @param $valid
     *
     * @dataProvider constrainsDataProvider
     */
    public function testConstrains($field, $value, $valid)
    {
        $model = new Product();
        $this->assertEntityConstrain($model, $field, $value, $valid);
    }

    public function constrainsDataProvider()
    {
        return array(

            // name
            'Empty name' => array('name', '', false),
            'Valid name' => array('name', 'Pong', true),

            // category
            'Empty category' => array('category', '', false),
            'Valid category' => array('category', '1', true),

            // sku
            'Empty sku' => array('sku', '', false),
            'Valid sku' => array('sku', 'A0001', true),

            // price
            'Empty price' => array('price', '', false),
            'Valid price' => array('price', '10', true),
            'Valid price with decimal' => array('price', '9.99', true),
            'Invalid price (string)' => array('price', 'abc', false),
            'Negative price' => array('price', '-5', false),

            // quantity
            'Empty quantity' => array('quantity', '', false),
            //'Valid quantity' => array('quantity', '100', true), // TODO check issue with transform
            //'Quantity of zero' => array('quantity', '0', true),
            'Negative quantity' => array('quantity', '-5', false),
            'Decimal quantity' => array('quantity', '0.5', false),

        );
    }
}