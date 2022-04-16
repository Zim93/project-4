<?php

namespace App\Controller;

use App\Entity\User;
use DateTimeImmutable;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Form\RegistrationHostFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    //Inscription au site
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user= $this->getUser();
        if(isset($user)){
            return $this->redirectToRoute('app_index');
        }
        else{

        //Création du nouveau utilisateur 
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            //Vérification de la confirmation de l'email
            if($form->get('email')->getData()!=$form->get('emailConfirmation')->getData())
            {
                return $this->render('registration/index.html.twig', [
                    'registrationForm' => $form->createView(),
                    'alert_error_email' => 'Les e-mails de passes ne sont pas identiques'
                ]);
            }
            elseif($form->get('plainPassword')->getData()!=$form->get('confirmPassword')->getData())
            {
                return $this->render('registration/index.html.twig', [
                    'registrationForm' => $form->createView(),
                    'alert_error_password' => 'Les mots de passes ne sont pas identiques'
                ]);
            }
           
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setCreatedAt(new DateTimeImmutable('now'));
            $user->setConfirmedHost(0);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render('registration/index.html.twig', [
            'registrationForm' => $form->createView(),
        ]);}
    }

    #[Route('/register/host', name: 'app_register_host')]
    public function registerHost(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        if($this->getUser() && in_array("ROLE_USER",$this->getUser()->getRoles()) && $this->getUser()->getConfirmedHost()==1 ){
            return $this->redirectToRoute('app_house_new');
        }
        elseif($this->getUser() && in_array("ROLE_USER",$this->getUser()->getRoles())){
            if($request->request->all() != []){
                $user=$this->getUser();
                $formData= $request->request->all()['registration_host_form'];
            
                if($formData['status'] !=""){
                    $user->setStatus($formData['status']);
                }

                if( $formData['fonction'] != ""){
                    $user->setFonction($formData['fonction']);
                }
                if($formData['company_name'] !=""){
                    $user->setCompanyName();
                }

                $user->setroles(['ROLE_HOST']);
                $user->setConfirmedHost(0);
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('app_house_index');
            }
            
            return $this->render('registration/indexHost.html.twig', [
                'already_user'=> 'true'
            ]);
        }
        else{

        //Création du nouveau utilisateur 
        $user = new User();
        $form = $this->createForm(RegistrationHostFormType::class, $user);
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            //Vérification de la confirmation de l'email
            if($form->get('email')->getData()!=$form->get('emailConfirmation')->getData())
            {
                return $this->render('registration/indexHost.html.twig', [
                    'registrationForm' => $form->createView(),
                    'alert_error_email' => 'Les e-mails de passes ne sont pas identiques'
                ]);
            }
            elseif($form->get('plainPassword')->getData()!=$form->get('confirmPassword')->getData())
            {
                return $this->render('registration/indexHost.html.twig', [
                    'registrationForm' => $form->createView(),
                    'alert_error_password' => 'Les mots de passes ne sont pas identiques'
                ]);
            }
           
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setCreatedAt(new DateTimeImmutable('now'));
            $user->setRoles(['ROLE_HOST']);
            $user->setConfirmedHost(0);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render('registration/indexHost.html.twig', [
            'registrationForm' => $form->createView(),
        ]);}
    }
}
