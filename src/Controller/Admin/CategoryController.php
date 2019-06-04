<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/admin/category", name="category_index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('admin/category/index.html.twig', [
            'section_name' => 'Categories',
            'categories' => $categoryRepository->findBy([], ['sort' => 'ASC']),
        ]);
    }

    /**
     * @Route("/admin/category/add", name="category_add", methods={"GET","POST"})
     */
    public function add(Request $request, CategoryRepository $categoryRepository): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $submittedData = $form->getData();
            if ($submittedData->getActive() == null) {
                $category->setActive(false);
            }
            $categoryRepository->incrementSortColumnByOne();
            $category->setSort(1);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category_index');
        }

        return $this->render('admin/category/add.html.twig', [
            'category' => $category,
            'section_name' => 'Add Category',
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/admin/category/{id}/edit", name="category_edit", methods={"GET","POST"})
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
            'blog' => $category,
            'section_name' => 'Edit Category',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/category/{id}/delete", name="category_delete", methods={"GET"})
     */
    public function delete(Request $request, Category $category)
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('category_index');
    }
}
