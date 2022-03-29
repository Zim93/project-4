<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use App\Repository\HouseRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event')]
    public function reservation(): Response
    {
        
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }

    #[Route('/event/{id}/delete', name: 'app_event_delete')]
    public function delete(Request $request,Event $event, EventRepository $eventRepository, HouseRepository $houseRepository): Response
    {

        $id = $request->get('house_id');
        $house = $houseRepository->find($id);
        $reservations = $house->getReservation();
        $toDisable = [];

        if(count($reservations) > 0){
            forEach($reservations as $reservation){
                $toDisable[] = ["start_date" => $reservation->getStartDate()->format("Y-m-d"), "end_date" => $reservation->getEndDate()->format("Y-m-d")];
            }
        }

        $eventRepository->remove($event);

        $events = $house->getEvents();

        if(count($events) > 0){
            forEach($events as $event){
                $toDisable[] = ["start_date" => $event->getStartAt()->format("Y-m-d"), "end_date" => $event->getEndAt()->format("Y-m-d")];
            }
        }

        return $this->render('house/_events_table.html.twig', [
            'success' => 'Evenement supprimé avec succès',
            'house' => $house,
            'to_disable' => $toDisable,
            'events' => $events
        ]);
    }
}
