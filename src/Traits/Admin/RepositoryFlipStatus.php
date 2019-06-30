<?php

namespace App\Traits\Admin;

trait RepositoryFlipStatus
{
    public function flipStatus($entity): bool
    {
        $new_status = intval(!$entity->getActive());
        $this->createQueryBuilder('E')
            ->update()
            ->andWhere('E.id = :id')
            ->setParameter('id', $entity->getId())
            ->set('E.active', $new_status)
            ->getQuery()
            ->execute()
            ;
            
        return (bool) $new_status;
    }
}