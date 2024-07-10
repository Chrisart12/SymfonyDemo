<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LangController extends AbstractController
{
    #[Route('/lang', name: 'app_lang')]
    public function index(Request $request): JsonResponse
    {
      
        $request->setLocale($request->get('locale'));

        
        // dd("eeeeeeeeeee", $request->getLocale());
        // return $this->json(['locale' => $request->get('locale'), 'message' => 'vous venez de changer de langue'], 200);
        return $this->json(['locale' => $request->getLocale(), 'message' => 'vous venez de changer de langue'], 200);
        return $this->render('lang/index.html.twig', [
            'controller_name' => 'LangController',
        ]);
    }


}
