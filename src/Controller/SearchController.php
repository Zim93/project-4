<?php

namespace App\Controller;

use DateTime;
use App\Form\SearchHouseType;
use App\Repository\HouseRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    #[Route('/search-render', name: 'app_house_search_render', methods: ['POST','GET'])]
    public function searchRender(Request $request, HouseRepository $houseRepository, PaginatorInterface $paginator): Response
    {
        $ids = $request->get('ids');
        
        if($ids != null){
            $houses = [];
            $from = null;

            if($request->get('page') !== null ){
                $page = $request->get('page');
            }
            else{
                $page = $request->query->getInt('page', 1);
            }

            forEach($ids as $id){
                $houses[] = $houseRepository->find($id);
            }

            $houses_found = $paginator->paginate(
                $houses, 
                $page,
                5
            );
           
            
            return $this->render('house/_houses.html.twig', [
                'houses' => $houses_found,
                'page_ids' => $ids,
                'page' => $request->query->getInt('page', 1)
            ]);
          
        }
        else{
            return $this->render('house/_houses.html.twig',[
                'page_ids' => '',
                'page' => $request->query->getInt('page', 1)
            ]);
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
        $type= $request->get('type');
        $houses = $houseRepository->findAvailbleHouses($start_date,$end_date,$nbr_voyagers,$type);

        return $this->json($houses);
    }


    #[Route('/search-index', name: 'app_house_search_index', methods: ['POST','GET'])]
    public function searchIndex(Request $request, HouseRepository $houseRepository, PaginatorInterface $paginator): RedirectResponse
    {
        $ids = $request->get('ids');

        $arrayIds = explode(',',$ids);
        
        return $this->redirectToRoute('app_house_index', ['ids' => $arrayIds]);
    }
}
