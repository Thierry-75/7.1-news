<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(ArticleRepository $articleRepository, UserRepository $userRepository): Response
    {
        $lastArticle = $articleRepository->findOneBy([],['id'=>'desc']);
        $articles = $articleRepository->findBy([],['id'=>'desc'],8);

        $authors = $userRepository->getUsersByArticles(4);

        return $this->render('main/index.html.twig', [
            'lastArticle'=>$lastArticle,'articles'=>$articles,'authors'=>$authors]);
    }

    #[Route('/mentions-legales', name: 'app_mentions')]
    public function mentions(): Response
    {
        return $this->render('main/mentions.html.twig');
    }
}
