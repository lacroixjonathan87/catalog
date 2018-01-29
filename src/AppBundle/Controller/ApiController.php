<?php


namespace AppBundle\Controller;


use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\Controller\FOSRestController;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class ApiController extends FOSRestController
{

    /**
     * @param QueryBuilder $queryBuilder
     * @return DoctrineORMAdapter
     */
    protected function createAdapter($queryBuilder)
    {
        return new DoctrineORMAdapter($queryBuilder);
    }

    /**
     * @param DoctrineORMAdapter $adapter
     * @param Request $request
     * @return Pagerfanta
     */
    protected function createPager($adapter, $request)
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 100);

        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage($limit);
        $pager->setCurrentPage($page);
        return $pager;
    }

    /**
     * @param Pagerfanta $pager
     * @return array
     */
    protected function getModels($pager)
    {
        $models = [];
        foreach ($pager->getCurrentPageResults() as $model) {
            $models[] = $model;
        }
        return $models;
    }

    /**
     * @param FormInterface $form
     * @return mixed
     */
    protected function getErrorsFromForm($form)
    {
        // TODO use serializer
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }

}