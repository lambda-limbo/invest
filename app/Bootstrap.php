<?php
    namespace Invest;

    require_once "../vendor/autoload.php";

    // Initialize the session of the server
    session_start();

    // Load the templates folder
    $loader = new \Twig\Loader\FilesystemLoader('../app/_layouts');

    // Load the environment and cache the results 
    $twig = new \Twig\Environment($loader, [
        // Turn off the cache when in development, in the future we have to find a new way to do this.
        //'cache' => '../app/_cache'
    ]);

    $router = new \Bramus\Router\Router();

    require_once 'Routing.php';
