<?php

namespace App\Controller;

use App\Form\SearchHouseType;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(CommentRepository $commentRepository): Response
    {
        
        $searchForm = $this->createForm(SearchHouseType::class);
        $comments = $commentRepository->findAll();

        return $this->renderForm('index/index.html.twig', [
            'search_form' => $searchForm,
            'comments' => $comments
        ]);
    }
}
