<?php

namespace App\Controller;

use DateTime;
use App\Entity\Event;
use App\Entity\House;
use App\Form\HouseType;
use App\Entity\Favorite;
use App\Entity\Attachment;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Form\SearchHouseType;
use App\Repository\EventRepository;
use App\Repository\HouseRepository;
use App\Controller\CommentController;
use App\Repository\CommentRepository;
use App\Repository\FavoriteRepository;
use App\Repository\AttachmentRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/house')]
class HouseController extends AbstractController
{
    #[Route('/', name: 'app_house_index', methods: ['GET'])]
    public function index(HouseRepository $houseRepository): Response
    {
        $searchForm = $this->createForm(SearchHouseType::class);
        return $this->renderForm('house/index.html.twig', [
            'houses' => $houseRepository->findAll(),
            'search_form' => $searchForm
        ]);
    }

    //Création d'un nouveau habitat
    #[Route('/new', name: 'app_house_new', methods: ['GET', 'POST'])]
    public function new(Request $request, HouseRepository $houseRepository, AttachmentRepository $attachmentRepository,SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        //Vérification que l'utilisateur est bien hôte
        if(in_array('ROLE_HOST',$user->getRoles()) && $user->getConfirmedHost() == true){
            $house = new House();
            $form = $this->createForm(HouseType::class, $house);
            //Récupération des données du formulaire
            $form->handleRequest($request);
            //Traitement des données du formulaire
            if ($form->isSubmitted() && $form->isValid()) {
                $house->setHost($user);
                $files = $form->get('images')->getData();

                foreach($files as $file){
                    //Ajout des photos dans le dossier public/images/houses
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $destination = $this->getParameter('kernel.project_dir').'/public/images/houses';
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
                    
                    $file->move(
                        $destination,
                        $newFilename
                    );

                    $attachment = new Attachment();
                    $attachment->setUrl($newFilename);
                    
                    $house->addAttachment($attachment);
                    $attachmentRepository->add($attachment);
                    
                }
                
                $houseRepository->add($house);

                
                return $this->redirectToRoute('app_house_index', [], Response::HTTP_SEE_OTHER);
            }
    
            return $this->renderForm('house/new.html.twig', [
                'house' => $house,
                'form' => $form,
            ]);
        }
        else{
            return $this->redirectToRoute('app_house_index');
        }
       
    }

    //Affichage d'un habitat
    #[Route('/{id}', name: 'app_house_show', methods: ['GET'])]
    public function show(House $house, $id ,CommentRepository $commentRepository, FavoriteRepository $favoriteRepository): Response
    {
        //Création du formulaire de réservation
        $reservation= new Reservation();
        $formReservation = $this->createForm(ReservationType::class, $reservation);
        $reservations = $house->getReservation();
        $events = $house->getEvents();
        $toDisable = [];
        $toEnable = [];
        $note= $commentRepository->findHouseNote($id)[0]["AVG(note)"];

        //Récupération des réservation disponible pour l'habitat pour les désactiver sur le calandrier affiché
        if(count($reservations) > 0){
            forEach($reservations as $reservation){
                $toDisable[] = ["start_date" => $reservation->getStartDate()->format("Y-m-d"), "end_date" => $reservation->getEndDate()->format("Y-m-d")];
            }
        }

        //Récupération des créneaux de disponiblité pour l'habitat pour les activer sur le calandrier affiché
        if(count($events) > 0){
            forEach($events as $event){
                $toEnable[] = ["start_date" => $event->getStartAt()->format("Y-m-d"), "end_date" => $event->getEndAt()->format("Y-m-d")];
            }
        }
        if($this->getUser()){
            $user= $this->getUser();
            $checkFavorite=$favoriteRepository->findByHouseUser($house->getId(),$user->getId());
            if($checkFavorite !=[]){
                $favorite=true;
            }
            else{
                $favorite=false;
            }
            return $this->renderForm('house/show.html.twig', [
                'form_reservation' => $formReservation,
                'note'=> $note,
                'reservation' => $reservation,
                'house' => $house,
                'to_disable' => $toDisable,
                'to_enable' => $toEnable,
                'favorite' =>$favorite
            ]);
        }           
        else
        {
            return $this->renderForm('house/show.html.twig', [
                'form_reservation' => $formReservation,
                'note'=> $note,
                'reservation' => $reservation,
                'house' => $house,
                'to_disable' => $toDisable,
                'to_enable' => $toEnable,
            ]);
        }
        
    }

    //Modification d'un habitat
    #[Route('/{id}/edit', name: 'app_house_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, House $house, HouseRepository $houseRepository,SluggerInterface $slugger,AttachmentRepository $attachmentRepository): Response
    {
        $form = $this->createForm(HouseType::class, $house);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Récupération des photos à supprimer 
            $toDelete = $request->get("delete_attach");
            $toDelete = explode(',',$toDelete);
            //Suppresion des photos
            if(count($toDelete) > 0 && $toDelete[0] != ""){
                $filesystem = new Filesystem();
                foreach($toDelete as $file){
                    $attachment = $attachmentRepository->find($file);
                    $house->removeAttachment($attachment);
                    $attachmentName = $attachment->getUrl();
                    $filesystem->remove(['images/houses/'.$attachmentName]);
                }
            }

            //Ajout de nouvelles photos
            $files = $form->get('images')->getData();

                foreach($files as $file){

                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $destination = $this->getParameter('kernel.project_dir').'/public/images/houses';
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
                    
                    $file->move(
                        $destination,
                        $newFilename
                    );

                    $attachment = new Attachment();
                    $attachment->setUrl($newFilename);

                    $house->addAttachment($attachment);
                    $attachmentRepository->add($attachment);
                    
                }
            $houseRepository->add($house);
            return $this->redirectToRoute('app_house_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('house/edit.html.twig', [
            'house' => $house,
            'form' => $form,
        ]);
    }

    //Modification des date de disponiblité de l'habitat
    #[Route('/{id}/edit-dispo', name: 'app_house_edit_dispo', methods: ["GET", 'POST'])]
    public function editDispo(Request $request,House $house, HouseRepository $houseRepository, $id, EventRepository $eventRepository): Response
    {
       //Récupération des réservation disponible pour l'habitat pour les désactiver sur le calandrier affiché
        $reservations = $house->getReservation();
        $toDisable = [];

        
        if(count($reservations) > 0){
            forEach($reservations as $reservation){
                $toDisable[] = ["start_date" => $reservation->getStartDate()->format("Y-m-d"), "end_date" => $reservation->getEndDate()->format("Y-m-d")];
            }
        }

        //Récupération des créneaux de disponiblité pour l'habitat pour les activer sur le calandrier affiché
        $events = $house->getEvents();

        if(count($events) > 0){
            forEach($events as $event){
                $toDisable[] = ["start_date" => $event->getStartAt()->format("Y-m-d"), "end_date" => $event->getEndAt()->format("Y-m-d")];
            }
        }

        //Ajout des nouvelles dates de disponibilité
        if($request->get('submitted')== 'true'){

            $start =$request->get('start_date');
            $end = $request->get('end_date');

            $house = $houseRepository->find($id);

            $event = new Event();
            $event->setHouse($house);
            $startData = new DateTime($start);
            $endData = new DateTime($end);
            $event->setStartAt($startData);
            $event->setEndAt($endData);
            $eventRepository->add($event);
            $house->addEvent($event);

            $events = $house->getEvents();

            $toDisable = [];

            if(count($events) > 0){
                forEach($events as $event){
                    $toDisable[] = ["start_date" => $event->getStartAt()->format("Y-m-d"), "end_date" => $event->getEndAt()->format("Y-m-d")];
                }
            }

            if(count($reservations) > 0){
                forEach($reservations as $reservation){
                    $toDisable[] = ["start_date" => $reservation->getStartDate()->format("Y-m-d"), "end_date" => $reservation->getEndDate()->format("Y-m-d")];
                }
            }

            return $this->render('house/_events_table.html.twig', [
                'success' => 'Vos disponibilité ont été mis à jour',
                'house' => $house,
                'to_disable' => $toDisable,
                'events' => $events
            ]);
        }
        
        return $this->render('house/edit_dispo.html.twig', [
            'success' => null,
            'house' => $house,
            'to_disable' => $toDisable,
            'events' => $events
        ]);
    }
    
    //Suppression d'un habitat
    #[Route('/{id}', name: 'app_house_delete', methods: ['POST'])]
    public function delete(Request $request, House $house, HouseRepository $houseRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$house->getId(), $request->request->get('_token'))) {
            //Suppression des photos de l'habitat 
            $filesystem = new Filesystem();
            foreach( $house->getAttachments() as $image ){
                $filesystem->remove(['images/houses/'.$image->getUrl()]);
            }
            $houseRepository->remove($house);
        }

        return $this->redirectToRoute('app_house_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/favorite', name: 'app_house_favorite')]
    public function favorite(House $house, HouseRepository $houseRepository,FavoriteRepository $favoriteRepository)
    {
        $user = $this->getUser();   
        $favorite= new Favorite();
        
        $checkFavorite=$favoriteRepository->findByHouseUser($house->getId(),$user->getId());
        if($checkFavorite == []){
            $favorite->setHouse($house);
            $favorite->setUser($user);
            $favoriteRepository->add($favorite);
            $response = ['action'=>'added'] ;
            return $this->json($response);
        }else{
            $fav=$favoriteRepository->find($checkFavorite[0]['id']);
            $favoriteRepository->remove($fav);
            $response = ['action'=>'deleted'] ;
            return $this->json($response);
        }        
    }
}
