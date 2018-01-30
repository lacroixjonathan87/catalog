<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;
use AppBundle\Repository\ProductRepository;

use FOS\RestBundle\View\View;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\Annotations as FOS;
use Symfony\Component\HttpFoundation\Request;


class ProductsController extends AppController
{

    /**
     * @SWG\Tag(name="Products")
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
     *     description="Page of the overview."
     * )
     *
     * @SWG\Response(response=200, description="List products")
     *
     * @param Request $request
     * @return View
     */
    public function getProductsAction(Request $request)
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 100);

        $pager = $this->getRepository()
            ->getProducts($page, $limit);

        $models = $pager->getCurrentPageResults();
        if($models instanceof \Traversable)
            $models = iterator_to_array($models);

        return View::create(
            array(
                'meta' => array(
                    'page' => $pager->getCurrentPage(),
                    'limit' => $pager->getMaxPerPage(),
                    'count' => count($models),
                    'total' => $pager->getNbResults(),
                ),
                'data' => array(
                    'products' => $models,
                ),
            ),
            200
        );
    }

    /**
     * @SWG\Tag(name="Products")
     *
     * @SWG\Response(response=200, description="Returns the product")
     * @SWG\Response(response=404, description="Product not found")
     *
     * @param Request $request
     * @return View
     */

    public function getProductAction($id, Request $request)
    {
        $model = $this->loadModel($id);

        return View::create(
            array(
                'meta' => array(),
                'data' => array(
                    'product' => $model,
                ),
            ),
            200
        );
    }

    /**
     * @SWG\Tag(name="Products")
     *
     * @FOS\RequestParam(name="name", description="Product name")
     * @FOS\RequestParam(name="category", description="Category ID")
     * @FOS\RequestParam(name="sku", description="SKU")
     * @FOS\RequestParam(name="price", default="0.00", description="Price")
     * @FOS\RequestParam(name="quantity", default="0", description="Quantity")
     *
     * @SWG\Response(response=201, description="Product added to the catalog")
     * @SWG\Response(response=400, description="Error, see meta")
     * @SWG\Response(response=401, description="Unauthorized")
     *
     * @param Request $request
     * @return View
     */
    public function postProductAction(Request $request)
    {
        $model = new Product();

        $form = $this->createForm(ProductType::class, $model);
        $form->submit($request->request->all());

        if(!$form->isValid()){
            return View::create(
                array(
                    'meta' => array(
                        'errors' => $this->getErrorsFromForm($form),
                    ),
                    'data' => array(),
                ),
                400
            );
        }

        $now = new \DateTime();
        $model->setCreatedAt($now);
        $model->setModifiedAt($now);

        $em = $this->getDoctrine()->getManager();
        $em->persist($model);
        $em->flush();

        return View::create(
                array(
                'meta' => array(),
                'data' => array(
                    'product' => $model,
                ),
            ),
            201
        );

    }

    /**
     * @SWG\Tag(name="Products")
     *
     * @FOS\RequestParam(name="name", description="Product name")
     * @FOS\RequestParam(name="category", description="Category ID")
     * @FOS\RequestParam(name="sku", description="SKU")
     * @FOS\RequestParam(name="price", default="0.00", description="Price")
     * @FOS\RequestParam(name="quantity", default="0", description="Quantity")
     *
     * @SWG\Response(response=201, description="Product updated")
     * @SWG\Response(response=400, description="Error, see meta")
     * @SWG\Response(response=401, description="Unauthorized")
     * @SWG\Response(response=404, description="Product not found")
     *
     * @param $id
     * @param Request $request
     * @return View
     */
    public function putProductAction($id, Request $request)
    {
        $model = $this->loadModel($id);

        $form = $this->createForm(ProductType::class, $model);
        $form->submit($request->request->all());

        if(!$form->isValid()){
            return View::create(
                array(
                    'meta' => array(
                        'errors' => $this->getErrorsFromForm($form),
                    ),
                    'data' => array(),
                ),
                400
            );
        }

        $now = new \DateTime();
        $model->setModifiedAt($now);

        $em = $this->getDoctrine()->getManager();

        $em->persist($model);
        $em->flush();

        return View::create(
            array(
                'meta' => array(),
                'data' => array(
                    'product' => $model,
                ),
            ),
            201
        );
    }

    /**
     * @SWG\Tag(name="Products")
     *
     * @SWG\Response(response=200, description="Delete product")
     * @SWG\Response(response=401, description="Unauthorized")
     * @SWG\Response(response=404, description="Product not found")
     *
     * @param $id
     * @return View
     */
    public function deleteProductAction($id)
    {
        $model = $this->loadModel($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($model);
        $em->flush();

        return View::create(
            array(
                'meta' => array(),
                'data' => array(),
            ),
            200
        );
    }

    /**
     * @return ProductRepository
     */
    protected function getRepository()
    {
        /** @var ProductRepository $repo */
        $repo = $this->getDoctrine()
            ->getRepository('AppBundle:Product');
        return $repo;
    }

}