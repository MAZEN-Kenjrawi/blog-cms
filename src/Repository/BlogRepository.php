<?php

namespace App\Repository;

use App\Entity\Blog;
use App\Traits\Admin\RepositoryFlipStatus;
use App\Contract\SearchableEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Blog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blog[]    findAll()
 * @method Blog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogRepository extends ServiceEntityRepository implements SearchableEntity
{
    use RepositoryFlipStatus;
    
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Blog::class);
    }
    
    public function getSearchInColumns()
    {
        return ['T.title', 'T.content'];
    }
}
