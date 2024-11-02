<?php

namespace App\Controller\Redaction;

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

#[Route('/redaction/article',name:'app_profile_article_')]
class ArticleController extends AbstractController
{

    #[Route('/show/{id}',name:'show',methods:['GET'])]
    public function showArticle(Article $article): Response
    {
        return $this->render('redaction/article/show.html.twig',['article'=>$article]);
    }

    #[Route('/add', name: 'add', methods:['GET','POST'])]
    public function addArticle(Request $request,
    ValidatorInterface $validator,
    SluggerInterface $slugger,
    EntityManagerInterface $em,
    PhotoService $photoService,
    ): Response
    {
        if($this->denyAccessUnlessGranted('ROLE_REDACTOR')){
            $this->addFlash('alert-danger','Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_main');
        }
        $article = new Article();
        $articleForm = $this->createForm(AddArticleFormType::class,$article);
        $articleForm->handleRequest($request);
        if($request->isMethod('POST')){
            $errors = $validator->validate($request);
            if(count($errors)>0){
                return $this->render('redaction/article/add.html.twig',['articleForm'=>$articleForm->createView(),'errors'=>$errors]);
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
                return $this->redirectToRoute('app_profile_article_show',['id'=>$article->getId()]);
            }
        }
        return $this->render('redaction/article/add.html.twig', [
            'articleForm'=>$articleForm->createView()
        ]);
    }
    #[Route('/update/{id}',name:'update',methods:['GET','POST'])]
    public function updateArticle(
        Article $article,
        Photo $photo,
        EntityManagerInterface $em,
        Request $request, 
        ValidatorInterface $validator,
        SluggerInterface $slugger,
        PhotoService $photoService
        ): Response
    {
        if($this->denyAccessUnlessGranted('ROLE_REDACTOR')){
            $this->addFlash('alert-danger','Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_main');
        }
        $form = $this->createForm(AddArticleFormType::class,$article);
        $form->handleRequest($request);
        if($request->isMethod('POST')){
            $errors = $validator->validate($request);
            if(count($errors)>0){
                return $this->render('redaction/article/update.html.twig',['form'=>$form->createView(),'errors'=>$errors]);
            }
            if($form->isSubmitted() && $form->isValid()){
                $folder="articles";
                $photos= $article->getPhotos();
                foreach($photos as $photo ){
                    $photoService->delete($photo->getName(),$folder,640,480);
                    $em->remove($photo);
                }
                $em->flush();    
                $slug = strtolower($slugger->slug($article->getTitre()));
                $article->setSlug($slug);
                $article->setUser($this->getUser());
                $photos = $form->get('photos')->getData();
                foreach($photos as $photo){
                    $folder ='articles';
                    $fichier = $photoService->add($photo,$folder,640,480);
                    $image = new Photo();
                    $image->setName($fichier);
                    $article->addPhoto($image);
                }

                $em->persist($article);
                $em->flush();
                $this->addFlash('Alert-succcess','L\'article a été modifié');
                return $this->redirectToRoute('app_profile_article_show',['id'=>$article->getId()]);
            }
        }
        return $this->render('redaction/article/update.html.twig',['form'=>$form->createView(),'article'=>$article]);
    }

    #[Route('/delete/{id}',name:'delete',methods:['GET','POST'])]
    public function deleteArticle(
        Article $article,
        EntityManagerInterface $em,
        Request $request
        ): Response
    {
        if($this->denyAccessUnlessGranted('ROLE_ADMIN')){
            $this->addFlash('alert-danger','Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_main');
        }
        $form = $this->createForm(AddArticleFormType::class,$article);
        $form->handleRequest($request);
        if($request->isMethod('POST')){
                $em->remove($article);
                $em->flush();
                $this->addFlash('Alert-succcess','L\'article a été supprimé');
                return $this->redirectToRoute('app_profile_article_all');
            
        }
        return $this->render('redaction/article/delete.html.twig',['form'=>$form->createView(),'article'=>$article]);
    }

    #[Route('/all',name:'all',methods:['GET'])]
    public function allArticle(
        EntityManagerInterface $em,
        ): Response
    {
        $articles = $em->getRepository(Article::class)->findBY([],['titre'=>'asc']);
        return $this->render('redaction/article/all.html.twig',['articles'=>$articles]);
    }
}
