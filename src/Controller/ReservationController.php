<?php

namespace App\Controller;

use DateTime;
use DateTimeImmutable;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Entity\Reservation;
use App\Entity\Notification;
use App\Form\ReservationType;
use App\Repository\EventRepository;
use App\Repository\HouseRepository;
use App\Repository\ReservationRepository;
use App\Repository\NotificationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    //Ajout d'une nouvelle réservation
    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservationRepository $reservationRepository, HouseRepository $houseRepository, EventRepository $eventRepository, NotificationRepository $notificationRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        
        //Création d'une nouvelle réservation
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Récupération des données du formulaire
            $start = new DateTime($request->get('start_date'));
            $end = new DateTime($request->get('end_date'));
            //Nombre de nuit
            $interval = date_diff($start,$end)->format('%a');

            $reservation->setStartDate($start);
            $reservation->setEndDate($end);
            $reservation->setNbrNights($interval);

            
            $house = $houseRepository->find($request->get('house'));
            //Calcule du prix de la réservation
            $total = $house->getPrice()*$interval;

            
            $reservation->setTotal($total);
            $reservation->setHouse($house);
            $reservation->setGuest($user);
            $reservation->setCreatedAt(new DateTimeImmutable());
            
            $reservationRepository->add($reservation);

            $notification = new Notification();
            $notification->setHouse($house);
            $notification->setReservation($reservation);
            $notification->setUser($user);
            $notification->setType('reservation');
            $notification->setOpened(0);

            $notificationRepository->add($notification);

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        $user= $this->getUser();
        $today = new DateTime();
        $guest = $reservation->getGuest();
        $comment = $reservation->getComment();
        
        //Ajout d'un nouveau commentaire à la réservation
        //Vérification que la réservation est bien terminé pour pouvoir ajouter un commentaire
        if($comment == NULL && $guest == $user && $reservation->getEndDate() < $today){
        
            $formComment = $this->createForm(CommentType::class, $comment);
            return $this->renderForm('reservation/show.html.twig', [
                'form_comment' => $formComment,
                'reservation' => $reservation,
            ]);
        }      

        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->add($reservation);
            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $reservationRepository->remove($reservation);
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
