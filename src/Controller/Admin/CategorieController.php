<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\AddCategoryFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('admin/categorie',name:'app_admin_categorie_')]
class CategorieController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
        ]);
    }

    #[Route('/add', name: 'add', methods:['GET','POST'])]
    public function addCategory(Request $request,ValidatorInterface $validator,SluggerInterface $slugger,EntityManagerInterface $em): Response
    {

        $category = new Category();
        $categoryForm = $this->createForm(AddCategoryFormType::class,$category);
        $categoryForm->handleRequest($request);
        if($request->isMethod('POST')){
            $errors = $validator->validate($request);
            if(count($errors)>0){
                return $this->render('admin/categorie/add.html.twig',['categoryForm'=>$categoryForm->createView(),'errors'=>$errors]);
            }
            if($categoryForm->isSubmitted() && $categoryForm->isValid()){
                $slug = strtolower($slugger->slug($category->getName()));
                $category->setSlug($slug);
                $em->persist($category);
                $em->flush();
                $this->addFlash('alert-success','La catégorie a bien été créée');
                return $this->redirectToRoute('app_admin_categorie_index');
            }
        }
        return $this->render('admin/categorie/add.html.twig', [
            'categoryForm'=>$categoryForm->createView()
        ]);
    }
}