<?php declare(strict_types=1);

    namespace Invest;

    use \PDO;

    use Invest\Middleware\Authentication;
    use Invest\Middleware\Redirection;
    use Invest\Middleware\Session;
    use Invest\Database\Query;


    use Invest\Database\Connection;

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

        
            $connection = Connection::get();
            $name = $_POST["name"];
            $username = $_POST["username"];
            $password = $_POST["password"];
            $cpf = $_POST["cpf"];
            $email = $_POST["email"];
            $phone = $_POST["phone"];
            $birth = $_POST["birth"];
            $wallet = 0;

            $query = "CALL P_INSERT_USER ('$name', '$username', '$password', '$cpf', '$email', '$phone', '$birth', $wallet)";
            $statement = $connection->query($query);


            echo "Incluido";

        
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
            
                $connection = Connection::get();
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
                                                        'name' => $_SESSION['USER']['name'],
                                                       'data' => array('f' => 30, 's' => 70)));
                    
                }

                else {

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
                                                        'name' => $_SESSION['USER']['name'],
                                                       'data' => array('f' => 30, 's' => 70)));
            
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
            echo $twig->render('dashboard.twig', array('username' => $_SESSION['USER']['username'],
                                                        'wallet' => $_SESSION['USER']['wallet'],
                                                        'code' => $_SESSION['USER']['code'],
                                                        'name' => $_SESSION['USER']['name'],
                                                       'data' => array('f' => 30, 's' => 70)));                               
        });

        /**
         *
         */
        

        $router->get('/wallet', function() use($twig) {
            echo $twig->render('wallet.twig');
        });

        $router->get('/stocks', function() use($twig) {
            echo $twig->render('stocks.twig');
        });

         $router->get('/users', function() use($twig) {
            $q = new Query("CALL P_SELECT_USERS");
            $q->execute();

            echo $twig->render('user.twig', array('users' => $q->fetchAll()));
        });

         $router->get('/company', function() use($twig) {
            $connection = Connection::get();

            $query = "CALL P_SELECT_COMPANIES";
            $statement = $connection->query($query);
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);

            echo $twig->render('company.twig', array('companies' => $result));
            Connection::close();
        });

         $router->post('/company', function() use($twig) {
                
                    $connection = Connection::get();
                    $nome = $_POST["name"];
                    $info = $_POST["info"];
                    $symbol = $_POST["symbol"];

                    $query = "CALL P_INSERT_COMPANY('$nome', '$info', '$symbol')";
                    $statement = $connection->query($query);

                    $query2 = "CALL P_SELECT_COMPANIES";
                    $statement2 = $connection->query($query2);
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

                    echo '<script> alert ("Empresa Adicionada com sucesso!"); location.href=("/internal/company")</script>';
                    echo $twig->render('company.twig', array('companies' => $result));
                    
                    Connection::close();
        });

        $router->get('/stocks/(\d+)', function () use($twig) {
            // Returns a page containing the selected stock and information about
            // how many stocks the user has acquired and also the fields for buying 
            // and selling the stock.
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

    $router->set404(function()  use($twig) {
        header('HTTP/1.1 404 Not Found');
        // ... do something special here
        echo $twig->render('404.twig');
    });