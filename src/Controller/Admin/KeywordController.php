<?php

namespace App\Controller\Admin;

use App\Entity\Keyword;
use App\Form\AddKeywordFormType;
use App\Repository\KeywordRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/admin/keyword',name:'app_admin_keyword_')]
class KeywordController extends AbstractController
{
    #[Route('/all', name: 'index')]
    public function index(EntityManagerInterface $em): Response
    {
        $keywords = $em->getRepository(Keyword::class)->findAll([''],['name','desc']);
        return $this->render('admin/keyword/index.html.twig', [
            'keywords'=>$keywords,
        ]);
    }

    #[Route('/add', name: 'add', methods:['GET','POST'])]
    public function addKeyword(Request $request,ValidatorInterface $validator,SluggerInterface $slugger,EntityManagerInterface $em): Response
    {

        $keyword = new Keyword();
        $keywordForm = $this->createForm(AddKeywordFormType::class,$keyword);
        $keywordForm->handleRequest($request);
        if($request->isMethod('POST')){
            $errors = $validator->validate($request);
            if(count($errors)>0){
                return $this->render('admin/keyword/add.html.twig',['keywordForm'=>$keywordForm->createView(),'errors'=>$errors]);
            }
            if($keywordForm->isSubmitted() && $keywordForm->isValid()){
                $slug = strtolower($slugger->slug($keyword->getName()));
                $keyword->setSlug($slug);
                $em->persist($keyword);
                $em->flush();
                $this->addFlash('alert-success','Le mot a bien été créé');
                return $this->redirectToRoute('app_admin_keyword_index');
            }
        }
        return $this->render('admin/keyword/add.html.twig', [
            'keywordForm'=>$keywordForm->createView()
        ]);
    }

    #[Route('/delete/{id}',name:'delete',methods:['GET','POST'])]
    public function deleteKeyword(Keyword $keyword,Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AddKeywordFormType::class,$keyword);
        $form->handleRequest($request);
        if($request->isMethod('POST')){
            if($form->isSubmitted() && $form->isValid()){
                $em->remove($keyword);
                $em->flush();
                $this->addFlash('alert-success','Le mot clef a été supprimé');
                return $this->redirectToRoute('app_admin_keyword_index');
            }
        }
        return $this->render('admin/keyword/delete_keyword.html.twig',['form'=>$form->createView(),'keyword'=>$keyword]);
    }
}
