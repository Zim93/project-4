<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\House;
use App\Entity\Comment;
use App\Entity\Promotion;
use App\Entity\Reservation;
use App\Repository\UserRepository;
use App\Controller\Admin\UserCrudController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class AdminController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }
    
    #[Route('/admin/confirm-host', name: 'admin_confirm_host')]
    public function confirmHost(UserRepository $userRepository,Request $request): Response
    {
        $id= $request->get('id');
        $user= $userRepository->find($id);
        $user->setConfirmedHost(1);
        $userRepository->add($user);
        $users= $userRepository->findByRoleHost();

        return $this->render('admin/_host_list.html.twig', [
            "users" => $users,
        ]);
    }

    #[Route('/admin/refuse-host', name: 'admin_refuse_host')]
    public function refuseHost(UserRepository $userRepository,Request $request): Response
    {
        $id= $request->get('id');
        $user= $userRepository->find($id);
        $user->setRoles([]);
        $userRepository->add($user);
        $users= $userRepository->findByRoleHost();

        return $this->render('admin/_host_list.html.twig', [
            "users" => $users,
        ]);
    }

    #[Route('/admin/confirm-host-list', name: 'admin_confirm_host_list')]
    public function confirmHostList(UserRepository $userRepository): Response
    {
        
        $users= $userRepository->findByRoleHost();
        
        return $this->render('/admin/hosts.html.twig', [
            "users" => $users,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Project3')
            // the name visible to end users
            ->setTitle('Atypik Home.')

            // there's no need to define the "text direction" explicitly because
            // its default value is inferred dynamically from the user locale
            ->setTextDirection('ltr')

            // set this option if you prefer the page content to span the entire
            // browser width, instead of the default design which sets a max width
            ->renderContentMaximized()

            // set this option if you prefer the sidebar (which contains the main menu)
            // to be displayed as a narrow column instead of the default expanded design
            ->renderSidebarMinimized()

            // by default, all backend URLs include a signature hash. If a user changes any
            // query parameter (to "hack" the backend) the signature won't match and EasyAdmin
            // triggers an error. If this causes any issue in your backend, call this method
            // to disable this feature and remove all URL signature checks
            ->disableUrlSignatures()

            // by default, all backend URLs are generated as absolute URLs. If you
            // need to generate relative URLs instead, call this method
            ->generateRelativeUrls();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Habitations', 'fas fa-home', House::class);
        yield MenuItem::linkToCrud('Commentaire', 'fas fa-comment', Comment::class);
        yield MenuItem::linkToCrud('R??servation', 'fas fa-list', Reservation::class);
        yield MenuItem::linkToCrud('Code Promo', 'fas fa-percent', Promotion::class);
        yield MenuItem::linkToRoute('Confirmation des h??tes', 'fas fa-check-circle', 'admin_confirm_host_list',[]);
        
    }
}