<?php

namespace App\Controller;

use App\Form\UserType;
use App\Form\ChangePasswordType;
use App\Repository\UserRepository;
use App\Repository\HouseRepository;
use App\Repository\ReservationRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(HouseRepository $houseRepository, ReservationRepository $reservationRepository): Response
    {
        //Récupération des données de l'utilisateur, ses réservation et ses habitats
        $user= $this->getUser();
        $favorites= $user->getFavorites();
        $houses= $user->getHouse();
        $reservations = $user->getReservation();
        //vérification si l'utilisateurs est bien un hôte 
        if(in_array('ROLE_HOST',$user->getRoles())){
            $host = true;
        }
        else{
            $host = false;
        }
        return $this->renderForm('user/index.html.twig',[
            'user' => $user,
            'favorites'=>$favorites,
            'houses' => $houses,
            'reservations' => $reservations,
            'host' => $host
        ]);
    }

    //Modification des information de l'utilisateur
    #[Route('/user/edit', name: 'app_user_edit')]
    public function edit(Request $request, UserRepository $userRepository,SluggerInterface $slugger): Response
    {
        $user= $this->getUser();
        $form= $this->createForm(UserType::class, $user);
        //Récupération des données du formulaire
        $form->handleRequest($request);
        
        //Traitement des données du formulaire
        $avatar= $form->get('avatar')->getData();
        if($form->isSubmitted() && $form->isValid()){
            //Suppression de  l'avatar du dossier public/images/users
            if($request->get("delete_avatar") == 'true'){
                $filesystem = new Filesystem();
                $filesystem->remove(['images/users/'.$user->getAvatar()]);
                $user->setAvatar(NULL);
            }

            if($avatar != null){
                //Ajout de l'avatar

                //En cas d'ajout d'un nouveau avatar, suppression de l'ancien
                if($user->getAvatar() != $avatar && $user->getAvatar() != null ){
                    $filesystem = new Filesystem();
                    $filesystem->remove(['images/users/'.$user->getAvatar()]);
                    $user->setAvatar(NULL);
                }
                //Ajout du nouveau avatar dans le dossier public/images/users
                $originalAvatarname = pathinfo($avatar->getClientOriginalName(), PATHINFO_FILENAME);
                $destination = $this->getParameter('kernel.project_dir').'/public/images/users';
                $safeAvatarname = $slugger->slug($originalAvatarname);
                $newAvatarname = $safeAvatarname.'-'.uniqid().'.'.$avatar->guessExtension();
                
                $avatar->move(
                    $destination,
                    $newAvatarname
                );
                $user->setAvatar($newAvatarname);
            }
            $userRepository->add($user);
            return $this->redirectToRoute('app_user');
        }

        return $this->renderForm('user/edit.html.twig',[
            'editForm' => $form,
            'user' => $user
        ]);
    }

    //Changement du mot de passe de l'utilisateurs
    #[Route('/user/edit/password', name: 'app_user_change_password')]
    public function changePassword(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository): Response
    {
        $user= $this->getUser();
        $form= $this->createForm(ChangePasswordType::class);
        //Récupération des données du formulaire
        $form->handleRequest($request);

        //Traitement des données du formulaire
        if ($form->isSubmitted() && $form->isValid()){
            $oldPwd=$form->get('oldPassword')->getData();
            $nenPwd=$form->get('newPassword')->getData();
            $newPwdConfirm = $form->get('confirmNewPassword')->getData();
            
            //Vérification de l'ancien mot de passe
            if (!$userPasswordHasher->isPasswordValid($user,$oldPwd)){
                return $this->renderForm('user/change_password.html.twig', [
                    'resetPasswordForm' => $form,
                    'alert_error_wrong_pwd' => 'Votre mot de passe est incorrect'
                ]);    
            } else{
                //Vérification de la confirmation du mot de passe 
                if($nenPwd == $newPwdConfirm){
                    $user->setPassword(
                    $userPasswordHasher->hashPassword($user,$nenPwd));
                    $userRepository->add($user);
                    $request->getSession()
                            ->getFlashBag()
                            ->add('success', 'Votre mot de passe a bien été changé');
                    return $this->redirectToRoute('app_user');
                } else {
                    return $this->renderForm('user/change_password.html.twig', [
                        'resetPasswordForm' => $form,
                        'alert_error_no_match' => 'Les mots de passe ne correspondent pas !'
                    ]);
                }
            }
        }
       
        return $this->renderForm('user/change_password.html.twig',[
            'resetPasswordForm' => $form,
            'user' => $user
        ]);
    }

}
