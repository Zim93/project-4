<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Entity\Notification;
use App\Repository\HouseRepository;
use App\Repository\CommentRepository;
use App\Repository\ReservationRepository;
use App\Repository\NotificationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/comment')]
class CommentController extends AbstractController
{
    #[Route('/', name: 'app_comment_index', methods: ['GET'])]
    public function index(CommentRepository $commentRepository): Response
    {
        $user = $this->getUser();
        $comment= $user->getComment();
        return $this->render('comment/index.html.twig', [
            'comments' => $comment,
        ]);
    }

    //Ajouter un commentaire aprés la fin de la réservatio
    #[Route('/new', name: 'app_comment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CommentRepository $commentRepository,HouseRepository $houseRepository, ReservationRepository $reservationRepository,NotificationRepository $notificationRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Récupération de la maison et de la réservation du formulaire 
            $house = $houseRepository->find($request->get('house'));
            $reservation = $reservationRepository->find($request->get('reservation'));
            
            //Création et ajout des commentaires        
            $comment->setGuest($user);
            $comment->setHouse($house);
            $comment->setReservation($reservation);
            $commentRepository->add($comment);
            $commentRepository->setHouseNote($house->getId());

            $notification = new Notification();
            $notification->setHouse($house);
            $notification->setReservation($reservation);
            $notification->setUser($user);
            $notification->setType('comment');
            $notification->setOpened(0);

            $notificationRepository->add($notification);

            return $this->redirectToRoute('app_comment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

}
