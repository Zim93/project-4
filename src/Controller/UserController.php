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
        $user= $this->getUser();
        $houses= $user->getHouse();
        $reservations = $user->getReservation();
        if(in_array('ROLE_HOST',$user->getRoles())){
            $host = true;
        }
        else{
            $host = false;
        }
        return $this->renderForm('user/index.html.twig',[
            'user' => $user,
            'houses' => $houses,
            'reservations' => $reservations,
            'host' => $host
        ]);
    }

    #[Route('/user/edit', name: 'app_user_edit')]
    public function edit(Request $request, UserRepository $userRepository,SluggerInterface $slugger): Response
    {
        $user= $this->getUser();
        $form= $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $avatar= $form->get('avatar')->getData();
        if($form->isSubmitted() && $form->isValid()){
            if($request->get("delete_avatar") == 'true'){
                $filesystem = new Filesystem();
                $filesystem->remove(['images/users/'.$user->getAvatar()]);
                $user->setAvatar(NULL);
            }

            if($avatar != null){

                if($user->getAvatar() != $avatar && $user->getAvatar() != null ){
                    $filesystem = new Filesystem();
                    $filesystem->remove(['images/users/'.$user->getAvatar()]);
                    $user->setAvatar(NULL);
                }

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

    #[Route('/user/edit/password', name: 'app_user_change_password')]
    public function changePassword(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository): Response
    {
        $user= $this->getUser();
        $form= $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()){
            $oldPwd=$form->get('oldPassword')->getData();
            $nenPwd=$form->get('newPassword')->getData();
            $newPwdConfirm = $form->get('confirmNewPassword')->getData();
            
            if (!$userPasswordHasher->isPasswordValid($user,$oldPwd)){
                return $this->renderForm('user/change_password.html.twig', [
                    'resetPasswordForm' => $form,
                    'alert_error_wrong_pwd' => 'Votre mot de passe est incorrect'
                ]);    
            } else{
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
