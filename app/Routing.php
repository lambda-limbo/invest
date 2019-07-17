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
        } 
        else if (Session::exists('ADMIN')) {
                Redirection::to('admin');
        }
        else {
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
        } 
        else if (Session::exists('ADMIN')) {
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
                                                        'name' => $_SESSION['USER']['name']));
                    
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
        /**
         *
         */
        
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
            $q->execute();

            echo $twig->render('admin_users.twig', array('users' => $q->fetchAll()));
        });

        $router->post('/stocks', function() use($twig) {
                
                    $connection = Connection::get();
                    $nome = $_POST["name"];
                    $info = $_POST["info"];
                    $symbol = $_POST["symbol"];
                    $query = "CALL P_INSERT_COMPANY('$nome', '$info', '$symbol')";
                    $statement = $connection->query($query);
                    $query2 = "CALL P_SELECT_COMPANIES";
                    $statement2 = $connection->query($query2);
                    $result = $statement2->fetchAll(PDO::FETCH_ASSOC);   
                    echo '<script> alert ("Empresa Adicionada com sucesso!"); location.href=("/admin/stocks")</script>';
                    echo $twig->render('admin_stocks.twig', array('companies' => $result));
                    
                    Connection::close();
        });

        $router->get('/stocks', function() use($twig) {
            $connection = Connection::get();
            $query = "CALL P_SELECT_COMPANIES";
            $statement = $connection->query($query);
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            echo $twig->render('admin_stocks.twig', array('companies' => $result));
            Connection::close();
        });

        $router->get('/stocks/{number}', function($number) use($twig) {
            $q = new Query('SELECT * FROM TB_COMPANY WHERE COMPANY_PK = :PK');
            $q->execute(array(':PK' => $number));

            $company = $q->fetch();
            echo $twig->render('admin_stock.twig', array('COMPANY' => $company));
        });

        $router->post('/stocks/{number}', function ($number) use($twig) {
            echo $_POST['company_name'];
            if($_POST['action'] == "EDITAR"){
                $q = new Query('UPDATE TB_COMPANY SET COMPANY_NAME = :NOME, COMPANY_INFO = :INFO, COMPANY_SYMBOL = :SYMBOL
                    WHERE COMPANY_PK = :PK');
            $q->execute(array(':PK' => $number, ':NOME' => $_POST['company_name'], ':INFO'=>$_POST['company_info'],
                             ':SYMBOL' =>$_POST['company_symbol']));

            Redirection::to('/localhost/admin/stocks');

            }
            
            else if($_POST['action']=="REMOVER"){
                // DELETE FROM nome_tabela WHERE condição
                $q = new Query('DELETE FROM TB_COMPANY WHERE COMPANY_PK = :PK');
                $q->execute(array(':PK' => $number));

                Redirection::to('/localhost/admin/stocks');
            }
        });

        $router->get('/users/{number}', function ($number) use($twig) {
            $q = new Query('SELECT * FROM TB_USER WHERE USER_PK = :PK');
            $q->execute(array(':PK' => $number));

            $user = $q->fetch();
            echo $twig->render('admin_user.twig', array('USER' => $user));

        });


        $router->get('/users/{number}', function ($number) use($twig) {
            $q = new Query('SELECT * FROM TB_USER WHERE USER_PK = :PK');
            $q->execute(array(':PK' => $number));

            $user = $q->fetch();
            echo $twig->render('admin_user.twig', array('USER' => $user));

        });

        $router->post('/users/{number}', function ($number) use($twig) {

            // UPDATE `invest_database`.`TB_USER` SET `USER_NAME` = 'rrrw', `USER_EMAIL` = 'errre', `USER_PHONE` = '1332', `USER_ADM` = '1' WHERE (`USER_PK` = '1');
            if($_POST['action'] == "EDITAR"){
                $q = new Query('UPDATE TB_USER SET USER_NAME = :NOME, USER_EMAIL = :EMAIL, USER_PHONE = :PHONE, USER_ADM = :ADM 
                WHERE USER_PK = :PK');
            $q->execute(array(':PK' => $number, ':NOME' => $_POST['user_name'], ':EMAIL'=>$_POST['user_email'],
                             ':PHONE' =>$_POST['user_phone'], ':ADM' => $_POST['user_adm'],));

            Redirection::to('/localhost/admin/users');

            }
            
            else if($_POST['action']=="REMOVER"){
                // DELETE FROM nome_tabela WHERE condição
                $q = new Query('DELETE FROM TB_USER WHERE USER_PK = :PK');
                $q->execute(array(':PK' => $number));

                Redirection::to('/localhost/admin/users');
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
        
        $body = (string) $res->getBody();
        //echo $body;
        $json = json_decode($body, true);
        //print_r($json['Meta Data']);
        //print_r($json['Time Series (Daily)']);
        //print_r($json);
        $start_date = '2019-07-16';
        

        $q = new Query('SELECT COMPANY_PK FROM TB_COMPANY WHERE COMPANY_SYMBOL = :SYMBOL');
        $q->execute(array(':SYMBOL'=>$symbol));

        $company = $q->fetch();
        $number =  $company['COMPANY_PK'];
        

        //echo($json['Time Series (Daily)']['2019-07-16']['1. open']);
        foreach ($json['Time Series (Daily)'] as $stock){
            //print_r($stock);
            //echo($stock['3. low']);

            $low = ($stock['3. low']);
            $open = ($stock['1. open']);
            $high = ($stock['2. high']);
            $close = ($stock['4. close']);
            echo ("open = $open");
            echo "<br>";
            echo ("high = $high");
            echo "<br>";
            echo ("low = $low");
            echo "<br>";
            echo ("close = $close");
            echo "<br>";

            //INSERT INTO `invest_database`.`TB_COMPANY_HISTORY` (`COMPANY_HISTORY_MINIMIUM`, `COMPANY_HISTORY_MAXIMIUM`, `COMPANY_HISTORY_DATE`, `COMPANY_HISTORY_OPENING_VALUE`, `COMPANY_HISTORY_CLOSE_VALUE`, `COMPANY_PK`) VALUES ('1', '1', '1', '1', '1', '1');/*
            $q1 = new Query('INSERT INTO TB_COMPANY_HISTORY (COMPANY_HISTORY_MINIMIUM, COMPANY_HISTORY_MAXIMIUM, 
                COMPANY_HISTORY_DATE, COMPANY_HISTORY_OPENING_VALUE, COMPANY_HISTORY_CLOSE_VALUE, COMPANY_PK) 
                    VALUES
                (:LOW, :HIGH, :DATA, :OPEN, :CLOSE, :PK);');
                
            $q1->execute(array(':LOW' => $low, ':HIGH' => $high, ':DATA'=>$start_date,
                             ':OPEN' =>$open, ':CLOSE' => $close, ':PK' => $number));
            
            $start_date=date('Y/m/d', strtotime('-1 day'));

            echo "<br>";
            
        }
    
    });

    $router->set404(function()  use($twig) {
        header('HTTP/1.1 404 Not Found');
        // ... do something special here
        echo $twig->render('404.twig');
    });