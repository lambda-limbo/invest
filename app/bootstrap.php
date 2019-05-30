<?php
    require_once "../vendor/autoload.php";

    // Load the templates folder
    $loader = new \Twig\Loader\FilesystemLoader('../app/_layouts');
    // Load the environment and cache the results 
    $twig = new \Twig\Environment($loader, [
        'cache' => '../app/_cache'
    ]);

    
    $router = new \Bramus\Router\Router();

    // Define all the routes of the system
    $router->get('/', function() use($twig) {
        echo $twig->render('default.twig', ['navigation' => array('HOME', 'CONTACT', 'LOGIN', 'SIGN UP')]);
    });


