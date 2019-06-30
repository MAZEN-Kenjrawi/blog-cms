<?php

namespace App\Repository;

use App\Entity\Category;
use App\Traits\Admin\RepositoryFlipStatus;
use App\Contract\SearchableEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository implements SearchableEntity
{
    use RepositoryFlipStatus;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function incrementSortColumnByOne()
    {
        return $this->createQueryBuilder('C')
            ->update()
            ->set('C.sort', 'C.sort + 1')
            ->getQuery()
            ->execute();
    }

    public function getSearchInColumns()
    {
        return ['T.title', 'T.content'];
    }
}
