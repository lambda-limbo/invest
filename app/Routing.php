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
            Redirection::to('internal');
        } else {
            echo $twig->render('login.twig');
        }
    });

    $router->post('/login', function() use($twig) {
        $username_css = '';
        $password_css = '';
        $error_message = '';

        if (strlen($_POST["username"]) == 0) { $username_css = 'is-invalid'; }
        if (strlen($_POST["password"]) == 0) { $password_css = 'is-invalid'; }

        $auth = Authentication::authenticate($_POST["username"], $_POST["password"]);

        if (strlen($username_css) == 0 && strlen($password_css) == 0 && !$auth) {
            $error_message = 'Usuário ou senha incorretos.';
        }

        if ($auth) {
            Redirection::to('internal');
        } else {
            echo $twig->render('login.twig', array('error' => $error_message,
                                                   'username_css' => $username_css,
                                                   'password_css' => $password_css,
                                                   'username' => $_POST["username"],
                                                   'password' => $_POST["password"]));
        }
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
    });


    // Middleware to check if the user has been logged in. For some reason I 
    // two of them because the reges (internal/.*)|(internal/) doesn't work.
    $router->before('GET|POST',  'internal/.*', function() use ($twig) {
        if (!Session::exists("USER")) {
            Redirection::out();
        }
    });

    $router->before('GET|POST',  'internal/', function() use ($twig) {
        if (!Session::exists("USER")) {
            Redirection::out();
        }
    });

    $router->mount('/internal', function() use ($router, $twig) {
        $router->get('/', function() use ($twig) {
            echo $twig->render('dashboard.twig');
        });

        $router->get('/wallet', function() use($twig) {
            echo $twig->render('wallet.twig');
        });

        $router->get('/stocks', function() use($twig) {
            echo $twig->render('stocks.twig');
        });

        $router->get('/stocks/(\d+)', function () use($twig) {
            // Returns a page containing the selected stock and information about
            // how many stocks the user has acquired and also the fields for buying 
            // and selling the stock.
        });

        
        $router->get('/reports', function() use($twig) {
            echo $twig->render('reports.twig');
        });

        
        $router->get('/exit', function() use($twig) {
            Session::clear();
            Redirection::out();
        });
    });

    $router->set404(function()  use($twig) {
        header('HTTP/1.1 404 Not Found');
        // ... do something special here
        echo $twig->render('404.twig');
    });