<?php

namespace AppBundle\Repository;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends AppRepository
{

    /**
     * @param int $page
     * @param int $limit
     * @return \Pagerfanta\Pagerfanta
     */
    public function getCategories($page=1, $limit=100)
    {
        $queryBuilder = $this->createQueryBuilder('t');

        $pager = $this->paginate($queryBuilder, $page, $limit);

        return $pager;
    }


}
