<?php
namespace AppBundle\Tests\Controller\Api;

include_once('ApiTestCase.php');

class ProductsControllerTest extends ApiTestCase
{

    public function testGetProducts()
    {
        $category = $this->createCategory('Games');
        $product = $this->createProduct('Pong', $category);

        $crawler = $this->client->request('GET', '/api/v1/products');
        $response = $this->client->getResponse();

        $this->assertStatusCode(200, $response);
        $this->assertContentType('application/json', $response);

        $content = json_decode($response->getContent(), true);

        $this->assertContentStructure($content);
        $this->assertMetaForPager($content);

        $meta = $content['meta'];
        $data = $content['data'];

    }


    public function testGetProduct()
    {
        $category = $this->createCategory('Games');
        $product = $this->createProduct('Pong', $category);

        $crawler = $this->client->request('GET', '/api/v1/products/'.$product->getId());
        $response = $this->client->getResponse();

        $this->assertStatusCode(200, $response);
        $this->assertContentType('application/json', $response);

        $content = json_decode($response->getContent(), true);

        $this->assertContentStructure($content);

        $this->assertEquals($product->getId(), $content['data']['product']['id']);
        $this->assertEquals('Pong', $content['data']['product']['name']);
    }

    /*
    public function testPostProductUnidentified()
    {
        $this->assertStatusCode(401, $response);
    }
    */

    public function testPostProduct()
    {
        $category = $this->createCategory('Games');

        $crawler = $this->client->request(
            'POST',
            '/api/v1/products',
            array(
                'name' => 'Pong',
                'category' => $category->getId(),
                'sku' => 'A0001',
                'price' => '10.50',
                'quantity' => '500',
            )
        );
        $response = $this->client->getResponse();

        $this->assertStatusCode(201, $response);
        $this->assertContentType('application/json', $response);

        $content = json_decode($response->getContent(), true);

        $this->assertContentStructure($content);

        $this->assertEquals('Pong', $content['data']['product']['name']);
    }

    public function testPostProductInvalid()
    {
        $category = $this->createCategory('Games');

        $crawler = $this->client->request(
            'POST',
            '/api/v1/products',
            array(
                'name' => 'Plop',
                'category' => $category->getId(),
                'sku' => 'A0001',
                'price' => 'a', // Not valid
                'quantity' => '500',
            )
        );

        $response = $this->client->getResponse();

        $this->assertStatusCode(400, $response);
        $this->assertContentType('application/json', $response);

        $content = json_decode($response->getContent(), true);

        $this->assertContentStructure($content);

        $this->assertArrayHasKey('price', $content['meta']['errors']);
    }

    /*
    public function testPutProductUnidentified()
    {
        $this->assertStatusCode(401, $response);
    }
    */

    public function testPutProduct()
    {
        $category = $this->createCategory('Games');
        $product = $this->createProduct('Pong', $category);

        $crawler = $this->client->request(
            'PUT',
            '/api/v1/products/'.$product->getId(),
            array(
                'name' => 'Pong2',
                'category' => $category->getId(),
                'sku' => 'A0002',
                'price' => '10.50',
                'quantity' => '100',
            )
        );
        $response = $this->client->getResponse();

        $this->assertStatusCode(201, $response);
        $this->assertContentType('application/json', $response);

        $content = json_decode($response->getContent(), true);

        $this->assertContentStructure($content);

        $this->assertEquals('Pong2', $content['data']['product']['name']);
        $this->assertEquals('A0002', $content['data']['product']['sku']);
        $this->assertEquals('10.50', $content['data']['product']['price']);
        $this->assertEquals('100', $content['data']['product']['quantity']);
    }

    public function testPutProductInvalid()
    {
        $category = $this->createCategory('Games');
        $product = $this->createProduct('Pong', $category);

        $crawler = $this->client->request(
            'PUT',
            '/api/v1/products/'.$product->getId(),
            array(
                'name' => 'Pong',
                'category' => $category->getId(),
                'sku' => 'A0001',
                'price' => 'a', // Not valid
                'quantity' => '0',
            )
        );
        $response = $this->client->getResponse();

        $this->assertStatusCode(400, $response);
        $this->assertContentType('application/json', $response);

        $content = json_decode($response->getContent(), true);

        $this->assertContentStructure($content);

        $this->assertArrayHasKey('price', $content['meta']['errors']);
    }

    /*
    public function testDeleteProductUnidentified()
    {
        $this->assertStatusCode(401, $response);
    }
    */

    public function testDeleteProduct()
    {
        $category = $this->createCategory('Games');
        $product = $this->createProduct('Pong', $category);

        $id = $product->getId();

        $crawler = $this->client->request('DELETE', '/api/v1/products/'.$id);
        $response = $this->client->getResponse();

        $this->assertStatusCode(200, $response);
        $this->assertContentType('application/json', $response);

        $content = json_decode($response->getContent(), true);

        $this->assertContentStructure($content);

        $repo = $this->entityManager->getRepository('AppBundle:Product');
        $this->assertNull($repo->find($id));
    }
}