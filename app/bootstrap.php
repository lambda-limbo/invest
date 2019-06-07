<?php
    /**
     * 
     * 
     */
        

    require_once "../vendor/autoload.php";

    // Load the templates folder
    $loader = new \Twig\Loader\FilesystemLoader('../app/_layouts');

    // Load the environment and cache the results 
    $twig = new \Twig\Environment($loader, [
        // Turn off the cache when in development, in the future we have to find a new way to do this.
        //'cache' => '../app/_cache'
    ]);

    
    $router = new \Bramus\Router\Router();

    // Define all the routes of the system
    $router->get('/', function() use($twig) {
        echo $twig->render('default.twig', ['navigation' => array('quem somos' => '/about', 'investimentos' => 'investments', 'contatos' => 'contact', 'login' => 'login', 
                                            'abra sua conta' => '/signup', '' => '/')]);
    });

    $router->get('/login', function() use($twig) {
       echo $twig->render('login.twig', ['navigation' => array('quem somos' => '/about', 'investimentos' => '/investments', 'contatos' => '/contact', 'login' => 'login', 
                                         'abra sua conta' => '/signup')]);
    });

    $router->post('/login', function() use($twig) {
    });

    $router->get('/about', function() use($twig) {
        echo $twig->render('about.twig', ['navigation' => array('quem somos' => '/about', 'investimentos' => '/investments', 'contatos' => '/contact', 'login' => 'login', 
                                          'abra sua conta' => '/signup')]);
    });

    $router->set404(function()  use($twig) {
        header('HTTP/1.1 404 Not Found');
        // ... do something special here
        echo $twig->render('404.twig');
    });



