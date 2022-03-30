use App\Controller\Admin\DashboardController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
$routes->add('dashboard', '/admin')
->controller([DashboardController::class, 'index'])
;

// ...
};