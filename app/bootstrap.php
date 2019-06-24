<?php
    /**
     * The initial file that serves the client with pages of the system.
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

    // Global routing variable
    $routes = array('quem somos' => '/about', 
                    'investimentos' => '/investments', 
                    'contatos' => '/contact', 
                    'login' => '/login',  
                    'abra sua conta' => '/sign_up');

    $internal = array('dashboard' => '/dashboard', 
                      'carteira'=>'/wallet',
                      'ativos'=>'/stocks',
                      'relatorios'=>'/reports',
                      'sair'=>'/exit');

    $twig->addGlobal('navigation', $routes);
    $twig->addGlobal('navigation_internal', $internal);
    $twig->addGlobal('base_url', $router->getBasePath());

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


    $router->mount('/internal', function () use ($router, $twig) {
        $router->get('/dashboard', function() use($twig) {
            echo $twig->render('dashboard.twig');
        });

        $router->get('/wallet', function() use($twig) {
            echo $twig->render('wallet.twig');
        });

        $router->get('/stocks', function() use($twig) {
            echo $twig->render('stocks.twig');
        });

        
        $router->get('/reports', function() use($twig) {
            echo $twig->render('reports.twig');
        });

        
        $router->get('/exit', function() use($twig) {
            echo $twig->render('exit.twig');
        });
    });

    

    $router->set404(function()  use($twig) {
        header('HTTP/1.1 404 Not Found');
        // ... do something special here
        echo $twig->render('404.twig');
    });



