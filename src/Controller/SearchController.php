<?php

namespace App\Controller;

use DateTime;
use App\Repository\HouseRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    #[Route('/search-render', name: 'app_house_search_render', methods: ['POST'])]
    public function searchRender(Request $request, HouseRepository $houseRepository): Response
    {
        
        $ids = $request->get('ids');
        if($ids != null){
            $houses = [];

            forEach($ids as $id){
                $houses[] = $houseRepository->find($id);
            }
           
            return $this->render('house/_houses.html.twig', [
                'houses' => $houses,
            ]);
        }else{
            return $this->render('house/_houses.html.twig');
        }
        
    }

    #[Route('/search-primary', name: 'app_house_search_primary', methods: ['POST','GET'])]
    public function searchPrimary(Request $request, HouseRepository $houseRepository): Response
    {
        $lat = $request->get('lat');
        $lng = $request->get('lng');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $nbr_voyagers = $request->get('nbr_voyagers');

        $houses = $houseRepository->findAvailbleHouses($start_date,$end_date,$nbr_voyagers);
        
        return $this->json($houses);
    }
}
