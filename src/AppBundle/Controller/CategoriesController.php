<?php

namespace AppBundle\Controller;

use AppBundle\Repository\CategoryRepository;
use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\View\View;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\Annotations as FOS;
use Symfony\Component\HttpFoundation\Request;

class CategoriesController extends ApiController
{

    /**
     * @SWG\Tag(name="Categories")
     *
     * @FOS\QueryParam(
     *     name="page",
     *     requirements="\d+",
     *     default="1",
     *     description="Page of the overview."
     * )
     * @FOS\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="100",
     *     description="Number of elements in the list."
     * )
     *
     * @SWG\Response(response=200, description="List categories")
     *
     * @param Request $request
     * @return View
     */
    public function getCategoriesAction(Request $request)
    {
        $queryBuilder = $this->createQueryBuilder();
        $adapter = $this->createAdapter($queryBuilder);
        $pager = $this->createPager($adapter, $request);

        $models = $this->getModels($pager);

        return View::create(
            array(
                'meta' => array(
                    'page' => $pager->getCurrentPage(),
                    'limit' => $pager->getMaxPerPage(),
                    'count' => count($models),
                    'total' => $pager->getNbResults(),
                ),
                'data' => array(
                    'categories' => $models,
                ),
            ),
            200
        );
    }

    /**
     * @return CategoryRepository
     */
    protected function getRepository()
    {
        /** @var CategoryRepository $repo */
        $repo = $this->getDoctrine()
            ->getRepository('AppBundle:Category');
        return $repo;
    }

    /**
     * @return QueryBuilder
     */
    protected function createQueryBuilder()
    {
        return $this->getRepository()
            ->createQueryBuilder('category');
    }


}