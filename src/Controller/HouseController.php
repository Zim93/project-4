<?php

namespace App\Controller;

use App\Entity\House;
use App\Form\HouseType;
use App\Entity\Attachment;
use App\Repository\HouseRepository;
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
        return $this->render('house/index.html.twig', [
            'houses' => $houseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_house_new', methods: ['GET', 'POST'])]
    public function new(Request $request, HouseRepository $houseRepository, AttachmentRepository $attachmentRepository,SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        if(in_array('ROLE_HOST',$user->getRoles())){
            $house = new House();
            $form = $this->createForm(HouseType::class, $house);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $house->setHost($user);
                $fullAddress= 'blabla';
                $house->setFullAddress($fullAddress);
                $house->setCalendarId(5);

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

                    $attachmentRepository->add($attachment);
                    $house->addAttachment($attachment);
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

    #[Route('/{id}', name: 'app_house_show', methods: ['GET'])]
    public function show(House $house): Response
    {
        return $this->render('house/show.html.twig', [
            'house' => $house,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_house_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, House $house, HouseRepository $houseRepository): Response
    {
        $form = $this->createForm(HouseType::class, $house);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $houseRepository->add($house);
            return $this->redirectToRoute('app_house_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('house/edit.html.twig', [
            'house' => $house,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_house_delete', methods: ['POST'])]
    public function delete(Request $request, House $house, HouseRepository $houseRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$house->getId(), $request->request->get('_token'))) {
            $filesystem = new Filesystem();
            foreach( $house->getAttachments() as $image ){
                $filesystem->remove(['images/houses/'.$image->getUrl()]);
            }
            $houseRepository->remove($house);
        }

        return $this->redirectToRoute('app_house_index', [], Response::HTTP_SEE_OTHER);
    }
}
