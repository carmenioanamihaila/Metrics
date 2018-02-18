<?php
require_once __DIR__.'/../vendor/autoload.php';
use App\RoutesLoader;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Silex\Application;
/**
 * Display all routes registered in a Silex application.
 * Important: the Silex application must have flushed its controllers before.
 *
 * @param Application $app
 * @param OutputInterface $output
 */
function displayRoutes(Application $app, OutputInterface $output = null) {
    if (null === $output) {
        $output = new ConsoleOutput();
    }
    $table = new Table($output);
    $table->setStyle('borderless');
    $table->setHeaders(array(
        'methods',
        'path'
    ));
    foreach ($app['routes'] as $route) {
        $table->addRow(array(
            implode('|', $route->getMethods()),
            $route->getPath(),
        ));
    }
    $table->render();
}
// Use it:
$silexApp = new Silex\Application(); // Your app
$routesLoader = new RoutesLoader($silexApp);
$routesLoader->bindRoutesToControllers();
// Dont forget:
$silexApp->flush();
// Display routes in console:
displayRoutes($silexApp);