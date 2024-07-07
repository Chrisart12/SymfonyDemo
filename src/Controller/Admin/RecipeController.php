<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use DateTimeImmutable;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('admin/recipe', name: 'admin.recipe.')]
class RecipeController extends AbstractController
{
    
    #[Route('/', name: 'index')]
    public function index(Request $request, RecipeRepository $recipeRepository): Response
    {
        $recipes = $recipeRepository->findAll();
        // dd($recipeRepository->findAll());
        return $this->render('admin/recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    /**
     * @Recipe
     */
    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManagerInterface)
    {
        // dd($request);
        $recipe = new Recipe();

        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        
            $entityManagerInterface->persist($recipe);
            $entityManagerInterface->flush();

            // Ajout de message flash
            $this->addFlash(
                'success',
                'votre recette a été créée.'
            );

            return $this->redirectToRoute('admin.recipe.index');
        }

        return $this->render('admin/recipe/create.html.twig', [
            'recipeForm' => $form,
        ]);
    }

    // #[Route('/{slug}/{id}', name: 'show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Request $request,  $id, RecipeRepository $recipeRepository): Response
    {
        $recipe = $recipeRepository->find($id);

        return $this->render('admin/recipe/show.html.twig', [
            'recipe' => $recipe
        ]);
    }

    /**
     * @Recipe
     */
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => Requirement::DIGITS])]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $entityManagerInterface)
    {

        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        
            $recipe->setUpdatedAt(new DateTimeImmutable());

            // /** @var UploadedFile $file */
            // $file = $form->get('filename')->getData();
            // $filename = 'recipe_' .time() . '_' . $recipe->getId() . '.' . $file->getClientOriginalExtension();

            // $file->move($this->getParameter('kernel.project_dir') . '/public/recettes/images', $filename);
            // $recipe->setFilename($filename);
        
            $entityManagerInterface->flush();

            // Ajout de message flash
            $this->addFlash(
                'success',
                'votre modification à été bien faite.'
            );

            return $this->redirectToRoute('admin.recipe.index');
        }

        return $this->render('admin/recipe/edit.html.twig', [
            'recipe' => $recipe,
            'recipeForm' => $form,
        ]);
    }


    /**
     * @Recipe
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS])]
    public function destroy(Recipe $recipe, EntityManagerInterface $entityManagerInterface)
    {

            $entityManagerInterface->remove($recipe);
            $entityManagerInterface->flush();

            // Ajout de message flash
            $this->addFlash(
                'success',
                'votre recette a été supprimée.'
            );

            return $this->redirectToRoute('admin.recipe.index');
    }
}
