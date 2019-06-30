<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use App\Service\DataTable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("", name="blog_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('admin/blog/index.html.twig', [
            'section_name' => 'Blogs',
            'module_name' => 'blog',
        ]);
    }

    /**
     * @Route("/data", name="blog_data", methods={"GET", "POST"})
     */
    public function data(Request $request, BlogRepository $blogRepository, DataTable $dataTable): JsonResponse
    {
        $QB = $dataTable->getDefaultQB($blogRepository, $request);
        $QB->orderBy('T.date', 'DESC')
            ->andWhere('T.lang = :lang')
            ->setParameter('lang', $request->request->get('lang'));
        $blogs = $QB->getQuery()->execute();
        $count_all = count($blogs);

        $rows_data = [];
        foreach ($blogs as $blog) {
            $single_row['title'] = $blog->getTitle();
            $single_row['category'] = $blog->getCategory()->getTitle();
            $single_row['status'] = $this->renderView('admin/_partials/_status_col.html.twig', [
                'id' => $blog->getId(),
                'active' => $blog->getActive(),
                'module_name' => 'blog',
            ]);
            $single_row['functions'] = $this->renderView('admin/_partials/_functions_col.html.twig', [
                'id' => $blog->getId(),
                'module_name' => 'blog',
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
     * @Route("/add", name="blog_add", methods={"GET","POST"})
     */
    public function add(Request $request): Response
    {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($blog);
            $entityManager->flush();

            return $this->redirectToRoute('blog_index');
        }

        return $this->render('admin/blog/add.html.twig', [
            'blog' => $blog,
            'module_name' => 'blog',
            'section_name' => 'Add Blog',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="blog_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Blog $blog): Response
    {
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('blog_index');
        }

        return $this->render('admin/blog/edit.html.twig', [
            'blog' => $blog,
            'module_name' => 'blog',
            'section_name' => 'Edit Blog',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="blog_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Blog $blog): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blog->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($blog);
            $entityManager->flush();
        }

        return $this->redirectToRoute('blog_index');
    }

    /**
     * @Route("/{id}/status", name="blog_status", methods={"GET"})
     */
    public function status(BlogRepository $blogRepository, Blog $blog): JsonResponse
    {
        $new_status = $blogRepository->flipStatus($blog);
        return new JsonResponse(['id' => $blog->getId(), 'status' => $new_status], Response::HTTP_OK);
    }
}
