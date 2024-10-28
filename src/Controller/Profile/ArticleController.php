<?php

namespace App\Controller\Profile;

use App\Entity\Article;
use App\Entity\Photo;
use App\Form\AddArticleFormType;
use App\Service\PhotoService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/profile/article',name:'app_profile_article_')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('profile/article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    #[Route('/add', name: 'add', methods:['GET','POST'])]
    public function addArticle(Request $request,
    ValidatorInterface $validator,
    SluggerInterface $slugger,
    EntityManagerInterface $em,
    PhotoService $photoService,
    ): Response
    {
        $article = new Article();
        $articleForm = $this->createForm(AddArticleFormType::class,$article);
        $articleForm->handleRequest($request);
        if($request->isMethod('POST')){
            $errors = $validator->validate($request);
            if(count($errors)>0){
                return $this->render('profile/article/add.html.twig',['articleForm'=>$articleForm->createView(),'errors'=>$errors]);
            }
            if($articleForm->isSubmitted() && $articleForm->isValid()){
                $slug = strtolower($slugger->slug($article->getTitre()));
                $article->setSlug($slug);
                $article->setUser($this->getUser());
                $photos = $articleForm->get('photos')->getData();
                foreach($photos as $photo){
                    $folder ='articles';
                    $fichier = $photoService->add($photo,$folder,640,480);
                    $image = new Photo();
                    $image->setName($fichier);
                    $article->addPhoto($image);
                }
                $em->persist($article);
                $em->flush();
                $this->addFlash('alert-success','L\'article a bien été créé');
                return $this->redirectToRoute('app_profile_article_index');
            }
        }
        return $this->render('profile/article/add.html.twig', [
            'articleForm'=>$articleForm->createView()
        ]);
    }
}
