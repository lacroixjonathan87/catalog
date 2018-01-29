<?php
namespace AppBundle\Tests\Controller\Api;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Repository\CategoryRepository;
use AppBundle\Repository\ProductRepository;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApiTestCase extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var ObjectManager
     */
    protected $entityManager;

    public function setUp()
    {
        $this->client = static::createClient();

        $container = self::$kernel->getContainer();
        $this->entityManager = $container->get('doctrine')->getManager();

        $this->clearDatabase();
    }

    public function tearDown() {
        $this->client = null;
        $this->clearDatabase();
    }

    /**
     * @param int $expected
     * @param Response $response
     */
    protected function assertStatusCode($expected, $response)
    {
        $this->assertEquals(
            $expected,
            $response->getStatusCode(),
            strtr('StatusCode "{statusCode}" expected', array('{statusCode}' => $expected))
        );
    }

    /**
     * @param string $expected
     * @param Response $response
     */
    protected function assertContentType($expected, $response)
    {
        $this->assertTrue(
            $response->headers->contains('Content-Type', $expected),
            strtr('Content-Type "{contentType}" expected', array('{contentType}' => $expected))
        );
    }

    /**
     * @param array $content
     */
    protected function assertContentStructure($content)
    {
        $this->assertArrayHasKey('meta', $content);
        $this->assertArrayHasKey('data', $content);
    }

    /**
     * @param array $content
     */
    protected function assertMetaForPager($content)
    {
        $meta = $content['meta'];
        $this->assertArrayHasKey('page', $meta);
        $this->assertArrayHasKey('limit', $meta);
        $this->assertArrayHasKey('count', $meta);
        $this->assertArrayHasKey('total', $meta);
    }

    protected function clearDatabase()
    {
        /** @var ProductRepository $productRepository */
        $productRepository = $this->entityManager->getRepository('AppBundle:Product');
        $productRepository->createQueryBuilder('t')
            ->delete()
            ->getQuery()
            ->execute();

        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = $this->entityManager->getRepository('AppBundle:Category');
        $categoryRepository->createQueryBuilder('t')
            ->delete()
            ->getQuery()
            ->execute();
    }

    protected function createCategory($name)
    {
        $now = new DateTime();

        $model = new Category();
        $model->setName($name);
        $model->setCreatedAt($now);
        $model->setModifiedAt($now);

        $this->entityManager->persist($model);
        $this->entityManager->flush();

        return $model;
    }

    protected function createProduct($name, $category, $sku='A0001', $price=0, $quantity=0)
    {
        $now = new DateTime();

        $model = new Product();
        $model->setName($name);
        $model->setCategory($category);
        $model->setSku($sku);
        $model->setPrice($price);
        $model->setQuantity($quantity);
        $model->setCreatedAt($now);
        $model->setModifiedAt($now);

        $this->entityManager->persist($model);
        $this->entityManager->flush();

        return $model;
    }
}