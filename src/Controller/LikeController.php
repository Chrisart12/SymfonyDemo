<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Enums\CodeStatus;
use App\Entity\RecipeLike;
use App\Repository\RecipeRepository;
use App\Repository\RecipeLikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LikeController extends AbstractController
{

    private $code;
    private $message;
    private $totalLikes;

    /**
     * Permet de liker ou unliker un recipe
     *
     * @param Request $request
     * @param [type] $id
     * @param RecipeRepository $recipeRepository
     * @param RecipeLikeRepository $recipeLikeRepository
     * @param EntityManagerInterface $entityManagerInterface
     * @return Response
     */
    #[Route('/like/{id}', name: 'like')]
    public function index(
        Request $request, 
        Recipe $recipe, RecipeRepository $recipeRepository, 
        RecipeLikeRepository $recipeLikeRepository, 
        EntityManagerInterface $entityManagerInterface): Response
    {

        try {
        
            $user = $this->getUser();
            // dd($user->getId());
            // Je recherche si cet utilisateur a liker ou pas ce recipe
            $isLikeBefore = $recipeLikeRepository->findOneBy(['user' => $user, 'recipe' =>  $recipe]);
            // On peut faire aussi
            // $isLikeBefore = $recipe->isLikeByUser($user);
            // dd($isLikeBefore);
            // Si l'utilisateur a déjà liker ce recipe, j'enlève son like, dans le cas contraire je sauvegarde son like
            if ($isLikeBefore) {
                $entityManagerInterface->remove($isLikeBefore);
                $entityManagerInterface->flush();

                $this->code = CodeStatus::Success->value;
                $this->message = 'Votre like a été supprimé';
                $this->totalLikes = $recipeLikeRepository->count(['recipe' => $recipe]);

            } else {

                $recipeLike = new RecipeLike();

                $recipeLike->setUser($user)
                            ->setRecipe($recipe);
        
                $entityManagerInterface->persist($recipeLike);
        
                $entityManagerInterface->flush();

                $this->code = CodeStatus::Success->value;
                $this->message = 'Votre like a été enregistré';
                $this->totalLikes = $recipeLikeRepository->count(['recipe' => $recipe]);
            }

            return $this->json([
                'code' => $this->code,
                'message' => $this->message,
                'totalLikes' => $this->totalLikes
            ], 200);

        } catch (\Throwable $th) {

            return $this->json([
                'code' => $th->getCode(),
                'message' => $th->getMessage(),
            ]);
            //throw $th;
        }

        

    }
}
