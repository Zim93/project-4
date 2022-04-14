<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{

    #[Route('/login', name: 'login')]
    public function index(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        $user= $this->getUser();
        //Redirection vers la page index si l'utilisateur est déjà authentifié 
        if(isset($user)){
            $request->getSession()
                    ->getFlashBag()
                    ->add('already-logged', 'Vous êtes déjà connecté');
            return $this->redirectToRoute('app_index');
        }else{
            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();

            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();
            return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
             ]);
        }
        
    }

    #[Route('/logout', name: 'logout', methods: ['GET'])]
    public function logout(AuthenticationUtils $authenticationUtils)
    {
        
    }
}
