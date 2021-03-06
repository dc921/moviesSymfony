<?php

namespace WP\PlatformBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;
/**
 * CommentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommentRepository extends \Doctrine\ORM\EntityRepository
{
     public function getComments($page, $nbPerPage,$movie) {
        $query = $this->createQueryBuilder('c')
                ->where('c.movie = '.$movie)
                ->orderBy('c.id','DESC')
                ->getQuery();
        
        $query->setFirstResult(($page-1)*$nbPerPage)->setMaxResults($nbPerPage);
        
        return new Paginator($query, true);
    }
}
