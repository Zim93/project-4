<?php

namespace App\Controller;

use DateTime;
use App\Entity\Event;
use DateTimeImmutable;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\EventRepository;
use App\Repository\HouseRepository;
use App\Repository\ReservationRepository;
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

    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservationRepository $reservationRepository, HouseRepository $houseRepository, EventRepository $eventRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $start = new DateTime($request->get('start_date'));
            $end = new DateTime($request->get('end_date'));
            $interval = date_diff($start,$end)->format('%a');

            $reservation->setStartDate($start);
            $reservation->setEndDate($end);
            $reservation->setNbrNights($interval);

            
            $house = $houseRepository->find($request->get('house'));
            $total = $house->getPrice()*$interval;

            $event = new Event();
            $event->setHouse($house);
            $event->setStartAt($start);
            $event->setEndAt($end);
            $eventRepository->add($event);

            $reservation->setEvent($event);
            $reservation->setTotal($total);
            $reservation->setHouse($house);
            $reservation->setGuest($user);
            $reservation->setCreatedAt(new DateTimeImmutable());

            $reservationRepository->add($reservation);

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
        if($reservation->getGuest() == $user && $reservation->getEndDate() < $today){
            $comment = new Comment();
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
