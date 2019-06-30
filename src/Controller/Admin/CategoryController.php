<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Service\DataTable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("", name="category_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('admin/category/index.html.twig', [
            'section_name' => 'Categories',
            'module_name' => 'category',
        ]);
    }

    /**
     * @Route("/data", name="category_data", methods={"GET", "POST"})
     */
    public function data(Request $request, CategoryRepository $categoryRepository, DataTable $dataTable): JsonResponse
    {
        $QB = $dataTable->getDefaultQB($categoryRepository, $request);
        $QB->andWhere('T.lang = :lang')
            ->setParameter('lang', $request->request->get('lang'));
        $categories = $QB->getQuery()->execute();
        $count_all = count($categories);

        $rows_data = [];
        foreach ($categories as $category) {
            $single_row['sort'] = $category->getSort();
            $single_row['title'] = $category->getTitle();
            $single_row['status'] = $this->renderView('admin/_partials/_status_col.html.twig', [
                'id' => $category->getId(),
                'active' => $category->getActive(),
                'module_name' => 'category',
            ]);
            $single_row['functions'] = $this->renderView('admin/_partials/_functions_col.html.twig', [
                'id' => $category->getId(),
                'module_name' => 'category',
            ]);
            $rows_data[] = $single_row;
        }

        return new JsonResponse([
            'draw' => intval($request->request->get('draw')),
            'recordsTotal' => $count_all,
            'recordsFiltered' => $count_all,
            'data' => $rows_data
        ], Response::HTTP_OK);
    }

    /**
     * @Route("/add", name="category_add", methods={"GET","POST"})
     */
    public function add(Request $request, CategoryRepository $categoryRepository): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->incrementSortColumnByOne();
            $category->setSort(1);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category_index');
        }

        return $this->render('admin/category/add.html.twig', [
            'category' => $category,
            'module_name' => 'category',
            'section_name' => 'Add Category',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('category_index');
        }

        return $this->render('admin/category/edit.html.twig', [
            'category' => $category,
            'module_name' => 'category',
            'section_name' => 'Edit Category',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="category_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Category $category)
    {
        if ($this->isCsrfTokenValid('delete_category' . $category->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('category_index');
    }

    /**
     * @Route("/{id}/status", name="category_status", methods={"GET"})
     */
    public function status(CategoryRepository $categoryRepository, Category $category): JsonResponse
    {
        $new_status = $categoryRepository->flipStatus($category);
        return new JsonResponse(['id' => $category->getId(), 'status' => $new_status], Response::HTTP_OK);
    }
}
