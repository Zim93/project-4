<?php

namespace App\Controller;

use App\Repository\HouseRepository;
use App\Repository\NotificationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NotificationController extends AbstractController
{
    #[Route('/notification/reservation', name: 'app_notification_reservation')]
    public function reservationOpened(NotificationRepository $notificationRepository)
    {
        $user=$this->getUser();
        $notifications = $user->getNotifications();
        foreach($notifications as $notification)
        {
            if ($notification->getType()=="reservation"){
                $notification->setOpened(1);
                $notificationRepository->add($notification);
            }
        }
        return new Response('ok');
    }

    #[Route('/notification/reservation/{id}', name: 'app_notification_reservation_single')]
    public function singleReservationOpened(NotificationRepository $notificationRepository, $id, HouseRepository $houseRepository )
    {
        $user=$this->getUser();
        $house= $houseRepository->find($id);
        $notifications = $house->getNotifications();
        foreach($notifications as $notification)
        {
            if ($notification->getType()=="reservation"){
                $notification->setOpened(1);
                $notificationRepository->add($notification);
            }
        }
        return new Response('ok');
    }

    #[Route('/notification/comment', name: 'app_notification_comment')]
    public function commentOpened(NotificationRepository $notificationRepository)
    {
        $user=$this->getUser();
        $notifications = $user->getNotifications();
        foreach($notifications as $notification)
        {
            if ($notification->getType()=="comment"){
                $notification->setOpened(1);
                $notificationRepository->add($notification);
            }
        }
        return new Response('ok');
    }
}
