<?php declare(strict_types=1);
    
    namespace Invest;

    use Invest\Database\Query;
    use Invest\Database\Connection;
    use Invest\Exceptions\DatabaseException;

    use Invest\Middleware\Session;
    use Invest\Middleware\Redirection;
    use Invest\Middleware\ServerError;
    use Invest\Middleware\Authentication;
    
    // Global routing variable for the external pages
    $routes = array('quem somos' => '/about', 
                    'investimentos' => '/investments', 
                    'contato' => '/contact', 
                    'login' => '/login',  
                    'abra sua conta' => '/sign_up');

    $internal = array('dashboard' => array('url' => '/', 'icon' => 'fas fa-home'), 
                      'carteira'=> array('url' => '/wallet', 'icon' => 'fa fa-wallet'),
                      'ativos'=> array('url' => '/stocks', 'icon' => 'fa fa-layer-group'),
                      'relatórios'=> array('url' => '/reports', 'icon' => 'fa fa-file-pdf'),
                      'logout'=> array('url' => '/exit', 'icon' => 'fa fa-sign-out-alt'));
    
    $admin = array('usuários' => array ('url' => '/users', 'icon' => 'fa fa-users'),
                   'ativos' => array ('url' => '/stocks', 'icon' => 'fa fa-chart-bar'),
                   'logout' => array ('url' => '/exit', 'icon' => 'fa fa-sign-out-alt'));

    $twig->addGlobal('navigation', $routes);
    $twig->addGlobal('navigation_admin', $admin);
    $twig->addGlobal('navigation_internal', $internal);
    $twig->addGlobal('base_url', $router->getBasePath());

    // Define all the routes of the system
    $router->get('/', function() use($twig) {
        echo $twig->render('home.twig');
    });

    $router->get('/login', function() use($twig) {
        if (Session::exists("USER")) {
            Redirection::to('internal');
        } else if (Session::exists('ADMIN')) {
                Redirection::to('admin');
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
            if (Session::exists("USER")) {
                Redirection::to('internal');
            } else if (Session::exists('ADMIN')) {
                    Redirection::to('admin');
            }
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
        
            $connection = Connection::get();
            $name = $_POST["name"];
            $username = $_POST["username"];
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
            $cpf = $_POST["cpf"];
            $email = $_POST["email"];
            $phone = $_POST["phone"];
            $birth = $_POST["birth"];
            $wallet = 0;
            $query = "CALL P_INSERT_USER ('$name', '$username', '$password', '$cpf', '$email', '$phone', '$birth', $wallet)";
            $statement = $connection->query($query);
            echo '<script> alert ("Cadastro efetuado com sucesso"); location.href=("/login")</script>';
            echo $twig->render('login.twig');
    });

    $router->get('/investments', function() use($twig) {
        echo $twig->render('investments.twig');
    });

    $router->get('/contact', function() use($twig) {
        echo $twig->render('contact.twig');
    });

    $router->post('/contact', function() use($twig) {
    });

    $router->post('/money', function() use($twig) {

        $wallet = $_POST["user_wallet"];
        $login = $_SESSION['USER']["username"];
        $password = $_POST["user_pass"];

        $q = new Query("SELECT * FROM TB_USER WHERE USER_LOGIN=:USER");
        $q->execute(array(':USER' => $login));
        $result = $q->fetch();
        if (count($result) === 0) { 
            echo '<script> alert ("Usuário incorreto"); location.href=("/internal")</script>';
        }
        
        if (!password_verify($password, $result['USER_PASSWORD'])) {
            echo '<script> alert ("Senha incorreta"); location.href=("/internal")</script>';
            echo $twig->render('dashboard.twig', array('username' => $_SESSION['USER']['username'],
                                                'wallet' => $_SESSION['USER']['wallet'],
                                                'code' => $_SESSION['USER']['code'],
                                                'name' => $_SESSION['USER']['name']));
            
        } else {
            $code = $_SESSION['USER']['code'];
            $nova_wallet = $_SESSION['USER']['wallet'] + $wallet;
            $q2 = new Query("CALL P_UPDATE_WALLET(:CODIGO, :WALLET)");
            $q2->execute(array(':CODIGO' => $code, ':WALLET' => $nova_wallet));
            $_SESSION['USER']['wallet'] = $nova_wallet;
            $result = $q->fetch();
            echo '<script> alert ("Dinheiro depositado com sucesso"); location.href=("/internal")</script>';

            echo $twig->render('dashboard.twig', array('username' => $_SESSION['USER']['username'],
                                                    'wallet' => $_SESSION['USER']['wallet'],
                                                    'code' => $_SESSION['USER']['code'],
                                                    'name' => $_SESSION['USER']['name']));
        }
            
    });
    
    // Middleware to check if the user has been logged in. For some reason I 
    // two of them because the reges (internal/.*)|(internal/) doesn't work.
    $router->before('GET|POST', 'internal/.*', function() use ($twig) {
        if (!Session::exists("USER")) {
            Redirection::out();
        }
    });

    $router->before('GET|POST', 'internal/', function() use ($twig) {
        if (!Session::exists("USER")) {
            Redirection::out();
        }
    });

    $router->mount('/internal', function() use ($router, $twig) {

        $router->get('/', function() use ($twig) {
            $codigo = $_SESSION['USER']['code'];

            $q1 = new Query("CALL P_SUM_BUY(:CODIGO)");
            $q1->execute(array(':CODIGO' => $codigo));
            
            $compra =  $q1->fetchAll(PDO::FETCH_ASSOC);

            $q2 = new Query("CALL P_SUM_SELL(:CODIGO)");
            $q2->execute(array(':CODIGO' => $codigo));

            $venda = $q2->fetchAll(PDO::FETCH_ASSOC);

            $valorAplicado = $compra[0]['VALOR'] -  $venda[0]['VALOR'];
            $_SESSION['USER']['aplicado'] = $valorAplicado;

            $_SESSION['USER']['total'] = $_SESSION['USER']['aplicado'] + $_SESSION['USER']['wallet'];


            $_SESSION['USER']['grafico1'] = ($_SESSION['USER']['aplicado']/$_SESSION['USER']['total'])*100;
            $_SESSION['USER']['grafico2'] = ($_SESSION['USER']['wallet']/$_SESSION['USER']['total'])*100;

            echo $twig->render('dashboard.twig', array('username' => $_SESSION['USER']['username'],
                                                        'wallet' => $_SESSION['USER']['wallet'],
                                                        'code' => $_SESSION['USER']['code'],
                                                        'name' => $_SESSION['USER']['name'],
                                                        'grafico1' =>$_SESSION['USER']['grafico1'],
                                                        'grafico2'=>$_SESSION['USER']['grafico2']));                               
        });

        $router->get('/wallet', function() use($twig) {
            echo $twig->render('wallet.twig');
        });

        $router->get('/stocks', function() use($twig) {
            $q = new Query("SELECT * FROM TB_COMPANY");
            $q->execute();

            $companies = $q->fetchAll();

            echo $twig->render('stocks.twig', array('companies' => $companies));
        });

        $router->get('/stocks/{number}', function ($stock) use($twig) {
            $q = new Query('SELECT * FROM TB_COMPANY WHERE COMPANY_PK = :PK');
            $q->execute(array(':PK' => $stock));

            $company = $q->fetch();

            echo $twig->render('stock.twig', array('company' => $company,
                                                   'user' => $_SESSION['USER']));
        });
        
        $router->get('/reports', function() use($twig) {
            
            $codigo = $_SESSION['USER']['code'];
            $q = new Query("CALL P_REPORT($codigo)");
            $q->execute();
            echo $twig->render('reports.twig', array('report' => $q->fetchAll()));
            
        });
        
        $router->get('/exit', function() use($twig) {
            Session::clear();
            Redirection::out();
        });
    });


    $router->mount('/admin', function() use($router, $twig) {
        
        $router->get('/', function() use ($twig) {
            echo $twig->render('admin_dashboard.twig', array('name' => $_SESSION['ADMIN']['name']));
        });

        $router->get('/users', function() use ($twig) {
            $q = new Query("CALL P_SELECT_USERS");

            try {
                $q->execute();

                echo $twig->render('user.twig', array('users' => $q->fetchAll()));
            } catch (DatabaseException $e) {
                echo $twig->render('admin_users.twig', ServerError::get(500, 'Internal server error', $e));
            }
        });

        $router->get('/stocks', function() use ($twig) {
            $q = new Query("SELECT * FROM TB_COMPANY;");
            $q->execute();

            $result = $q->fetchAll();

            echo $twig->render('admin_stocks.twig', array('companies' => $result));
        });

        $router->post('/stocks', function() use($twig) {
                    $name = $_POST["name"];
                    $info = $_POST["info"];
                    $symbol = $_POST["symbol"];

                    $q = new Query("CALL P_INSERT_COMPANY(:NAME, :INFO, :SYMBOL)");
                    
                    try {
                        $q->execute(array(':NAME' => $name, ':INFO' => $info, ':SYMBOL' => $symbol));

                        Redirection::to('/admin/stocks');
                    } catch (DatabaseException $e) {
                        echo $twig->render('/stocks', ServerError::get(500, 'Internal server error', $e));
                    }
        });

        $router->get('/exit', function() use ($twig) {
            Session::clear();
            Redirection::out();
        });
    });

    use GuzzleHttp\Client;

    $router->get("/company/{symbol}", function($symbol) {
        $api_key = "DLTX-GLV63LGIO38K";
        $uri = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=$symbol&outputsize=full&apikey=$api_key";

        $client = new Client();
        $res = $client->get($uri);

        echo $res->getBody();
    });

    $router->set404(function()  use($twig) {
        header('HTTP/1.1 404 Not Found');
        // ... do something special here
        echo $twig->render('404.twig');
    });