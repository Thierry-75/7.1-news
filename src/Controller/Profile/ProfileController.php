<?php

namespace App\Controller\Profile;

use App\Entity\User;
use App\Form\UpdateProfilFormType;
use App\Service\PhotoService;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/profile',name:'app_profile_')]
class ProfileController extends AbstractController
{
    #[Route('/all', name: 'show_all')]
    public function index(): Response
    {
        if($this->denyAccessUnlessGranted('ROLE_ADMIN')){
            $this->addFlash('alert-danger','Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_main');
        }
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/show', name: 'show_profil')]
    public function add(): Response
    {
        if($this->denyAccessUnlessGranted('ROLE_USER')){
            $this->addFlash('alert-danger','Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_main');
        }
        return $this->render('profile/show.html.twig', [
            'inscrit' => $this->getUser(),
        ]);
    }

    #[Route('/update', name: 'update_profil',methods:['GET','POST'])]
    public function update(
        Request $request,
        ValidatorInterface $validator,
        PhotoService $photoService,
        SendMailService $mailer,
        EntityManagerInterface $em,
        MessageBusInterface $businterface  ): Response
    {
        if($this->denyAccessUnlessGranted('ROLE_USER')){
            $this->addFlash('alert-danger','Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_main');
        }
        $form = $this->createForm(UpdateProfilFormType::class,$this->getUser());
        $form->handleRequest($request);
        if($request->isMethod('POST')){
            $errors = $validator->validate($request);
            if(count($errors)>0){
                return $this->render('profile/update.html.twig',['form'=>$form->createView(),'errors'=>$errors]);
            }
            if($form->isSubmitted() && $form->isValid()){
               // dd($this->getUser());
                $image = $form->get('avatar')->getData();
                $folder='inscrits';
                $fichier = $photoService->add($image,$folder,300,300);
                $this->getUser()->getAvatar()->setName($fichier);
                $em->persist($this->getUser());
                $em->flush();
                return $this->redirectToRoute('app_profile_show_profil');
            }
        }
        return $this->render('profile/update.html.twig', [
            'form'=>$form->createView()
        ]);
    }

}
