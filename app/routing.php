<?php
    namespace Invest;

    // Global routing variable for the external pages
    $routes = array('quem somos' => '/about', 
                    'investimentos' => '/investments', 
                    'contatos' => '/contact', 
                    'login' => '/login',  
                    'abra sua conta' => '/sign_up');


    $twig->addGlobal('navigation', $routes);

    // Define all the routes of the system
    $router->get('/', function() use($twig) {
        echo $twig->render('home.twig');
    });

    $router->get('/login', function() use($twig) {
       echo $twig->render('login.twig');
    });

    $router->post('/login', function() use($twig) {
    });

    $router->get('/about', function() use($twig) {
        echo $twig->render('about.twig');
    });

    $router->get('/sign_up', function() use($twig) {
        echo $twig->render('sign_up.twig');
    });

    $router->post('/sign_up', function() use($twig) {
        
    });

    $router->get('/investments', function() use($twig) {
        echo $twig->render('investments.twig');
    });

    $router->get('/investments', function() use($twig) {
        echo $twig->render('contact.twig');
    });


    $router->set404(function()  use($twig) {
        header('HTTP/1.1 404 Not Found');
        // ... do something special here
        echo $twig->render('404.twig');
    });