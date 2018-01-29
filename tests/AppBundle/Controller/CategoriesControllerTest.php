<?php

namespace AppBundle\Tests\Controller\Api;

use AppBundle\Entity\Category;
use AppBundle\Repository\CategoryRepository;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bridge\Doctrine\ManagerRegistry;

include_once('ApiTestCase.php');

class CategoriesControllerTest extends ApiTestCase
{

    public function testGetCategories()
    {
        $model = $this->createCategory('Games');

        $crawler = $this->client->request('GET', '/api/v1/categories');
        $response = $this->client->getResponse();

        $this->assertStatusCode(200, $response);
        $this->assertContentType('application/json', $response);

        $content = json_decode($response->getContent(), true);

        $this->assertContentStructure($content);
        $this->assertMetaForPager($content);

        $this->assertEquals($model->getId(), $content['data']['categories'][0]['id']);
        $this->assertEquals('Games', $content['data']['categories'][0]['name']);
    }



}