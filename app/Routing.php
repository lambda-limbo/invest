<?php declare(strict_types=1);

    namespace Invest;

    use Invest\Middleware\Authentication;
    use Invest\Middleware\Redirection;
    use Invest\Middleware\Session;

    // Global routing variable for the external pages
    $routes = array('quem somos' => '/about', 
                    'investimentos' => '/investments', 
                    'contatos' => '/contact', 
                    'login' => '/login',  
                    'abra sua conta' => '/sign_up');


    $internal = array('dashboard' => array('url' => '/', 'icon' => 'fas fa-home'), 
                      'carteira'=> array('url' => '/wallet', 'icon' => 'fa fa-wallet'),
                      'ativos'=> array('url' => '/stocks', 'icon' => 'fa fa-layer-group'),
                      'relatórios'=> array('url' => '/reports', 'icon' => 'fa fa-file-pdf'),
                      'sair'=> array('url' => '/exit', 'icon' => 'fa fa-sign-out-alt'));

    $twig->addGlobal('navigation', $routes);
    $twig->addGlobal('navigation_internal', $internal);
    $twig->addGlobal('base_url', $router->getBasePath());

    // Define all the routes of the system
    $router->get('/', function() use($twig) {
        echo $twig->render('home.twig');
    });

    $router->get('/login', function() use($twig) {
        if (Session::exists("USER")) {
            Redirection::to('/internal');
        } else {
            echo $twig->render('login.twig');
        }
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

    $router->get('/contact', function() use($twig) {
        echo $twig->render('contact.twig');
    });

    $router->post('/contact', function() use($twig) {
        // It's ideal that the messages are sent over the websockets endpoint.
    });

    $router->mount('/internal', function() use ($router, $twig) {
        $router->get('/', function() use ($twig) {
            if (Session::exists("USER")) {
                echo $twig->render('dashboard.twig');
            } else {
                Redirection::out();
            }
        });

        $router->get('/wallet', function() use($twig) {
            if (Session::exists("USER")) {
                echo $twig->render('wallet.twig');
            } else {
                Redirection::out();
            }
        });

        $router->get('/stocks', function() use($twig) {
            if (Session::exists("USER")) {
                echo $twig->render('stocks.twig');
            } else {
                Redirection::out();
            }
        });

        
        $router->get('/reports', function() use($twig) {
            if (Session::exists("USER")) {
                echo $twig->render('reports.twig');
            } else {
                Redirection::out();
            }
        });

        
        $router->get('/exit', function() use($twig) {
            Session::destroy("USER");
            Redirection::out();
        });
    });

    $router->set404(function()  use($twig) {
        header('HTTP/1.1 404 Not Found');
        // ... do something special here
        echo $twig->render('404.twig');
    });