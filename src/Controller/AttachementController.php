<?php

namespace App\Controller;

use App\Repository\AttachmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AttachementController extends AbstractController
{
    #[Route('/attachement', name: 'app_attachement')]
    public function removePhoto(Request $request, AttachmentRepository $attachement, EntityManagerInterface $em ): Response
    {
        $imagesId=$request->request->all()['images'];
        $filesystem = new Filesystem();
        
        foreach($imagesId as $imageId){
            $image = $attachement->find($imageId);
            $house = $image->getHouse();
            $em->remove($image);
            $em->flush();
            $imageName= $image->getUrl();
            $filesystem->remove(['images/houses/'.$imageName]);
        }
        
        return $this->render('/house/_edit_images.html.twig',['house'=>$house]);
    }
}
