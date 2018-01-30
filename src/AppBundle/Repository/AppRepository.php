<?php

namespace AppBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

abstract class AppRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * @param QueryBuilder $queryBuilder
     * @param int $page
     * @param int $limit
     * @return Pagerfanta
     */
    protected function paginate($queryBuilder, $page, $limit)
    {
        $adapter = new DoctrineORMAdapter($queryBuilder);

        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage($limit);
        $pager->setCurrentPage($page);

        return $pager;
    }

}