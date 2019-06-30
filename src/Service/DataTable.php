<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use App\Contract\SearchableEntity;

class DataTable
{
    public function getDefaultQB(SearchableEntity $entityRepository, Request $request)
    {
        $qb = $entityRepository->createQueryBuilder('T')
            ->setMaxResults($request->request->get('length', 25))
            ->setFirstResult($request->request->get('start', 0));

        $search = $request->request->get('search', []);
        $columnsToSearchIn = $entityRepository->getSearchInColumns();
        if (!empty($search['value']) && count($columnsToSearchIn) > 0) {
            foreach ($columnsToSearchIn as $column) {
                $qb->orWhere($column . ' LIKE :search');
            }
            $qb->setParameter('search', '%' . $search['value'] . '%');
        }

        $orders = $request->request->get('order', []);
        if (!empty($orders)) {
            $datatable_columns = $request->request->get('columns', []);
            $orders = array_pop($orders);
            $qb->orderBy('T.' . $datatable_columns[$orders['column']]['data'], $orders['dir']);
        }
        
        return $qb;
    }

    public function applySchema($rows, $Schema)
    {
        foreach ($rows as $row) {
            $data = $Schema($row);

        }
    }
}