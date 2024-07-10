<?php

namespace App\Controller\Admin;

use DateTimeImmutable;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('admin/category', name: 'admin.category.')]
class CategoryController extends AbstractController
{
    
    #[Route('/category', name: 'index')]
    public function index(Request $request, CategoryRepository $categoryRepository): Response
    {
        
        $nberPerPage = $this->getParameter('pagination'); // Configuration dans service yaml
        
        $page = $request->query->getInt('page', 1);
        // dd("dddddddd", $categoryRepository->findAllWithCount($page, $nberPerPage));
        // $categories = $categoryRepository->findAll();
        $categories = $categoryRepository->findAllWithCount($page, $nberPerPage);
    
        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManagerInterface
     * @return void
     */
    #[Route('/create', name: 'create', methods: ['GET', 'POST'], requirements: ['id' => Requirement::DIGITS])]
    public function create(Request $request, EntityManagerInterface $entityManagerInterface)
    {
        // dd($request);
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManagerInterface->persist($category);
            $entityManagerInterface->flush();

            // Ajout de message flash
            $this->addFlash(
                'success',
                'votre catégorie a été créé.'
            );

            return $this->redirectToRoute('admin.category.index');
        }

        return $this->render('admin/category/create.html.twig', [
            'categoryForm' => $form,
        ]);
    }

    /**
     *
     */
    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => Requirement::DIGITS])]
    public function show(Category $category, CategoryRepository $categoryRepository): Response
    {
        // dd($category);
        // $category = $categoryRepository->find();
        // dd($category);
        return $this->render('admin/category/show.html.twig', [
            'category' => $category,
        ]);
    }

     /**
     *
     */
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => Requirement::DIGITS])]
    public function edit(Category $category, Request $request, EntityManagerInterface $entityManagerInterface)
    {

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $category->setUpdatedAt(new DateTimeImmutable());

            // $entityManagerInterface->persist($recipe);
            $entityManagerInterface->flush();

            // Ajout de message flash
            $this->addFlash(
                'success',
                'Votre modification à été bien faite.'
            );

            return $this->redirectToRoute('admin.category.index');
        }

        return $this->render('admin/category/edit.html.twig', [
            'category' => $category,
            'categoryForm' => $form,
        ]);
    }

    /**
     * 
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS])]
    public function destroy(Category $category, EntityManagerInterface $entityManagerInterface)
    {

        $entityManagerInterface->remove($category);
        $entityManagerInterface->flush();

        // Ajout de message flash
        $this->addFlash(
            'success',
            'votre categorie a été supprimée.'
        );

        return $this->redirectToRoute('admin.category.index');

    }

}
