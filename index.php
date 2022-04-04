<?php 

	session_start();
	require_once("vendor/autoload.php");

	use \Slim\Slim;
	use \Hcode\Page;
	use \Hcode\PageAdmin;
	use \Hcode\Model\User;

	$app = new Slim();

	$app -> config('debug', true);
	// Daqui pra cima é configuração das páginas

	// Daqui pra baixo é o que vai ser renderizado
	$app -> get('/', function() {

		$page =new Page(); // Aqui ele chama o construct

		$page -> setTpl("index");

		// Aqui ele chama o destruct e limpa a memoria
		
		// $sql = new \Hcode\DB\Sql();
		// $results = $sql -> select("SELECT * FROM tb_users");
		// echo json_encode($results); // APENAS PARA TESTE

	});

	$app -> get('/admin', function() {

		User::verifyLogin();

		$page = new PageAdmin();

		$page -> setTpl("index");

	});

	$app -> get('/admin/login', function() {

		// Precisamos passar alguns parametros para desativar os headers e footers automaticos, header e footer nessa pagina são outros, ele já ta na pagina de login, ele não vai ta separado, APENAS NO CASO DO LOGIN
		$page = new PageAdmin([
			"header" => false, // Necessario habilitar na classe page no atributo $default
			"footer" => false
		]);

		$page -> setTpl("login");

	});

	$app -> post('/admin/login', function() {

		// Validação do login
		User::login($_POST["login"], $_POST["password"]); // Se não der nenhum erro podemos redirecionar para a administração
		// Método estático pois vai ser usado só aqui

		header("Location: /admin");
		exit; // Para a execução aqui

	});

	$app -> get('/admin/logout', function() { // Rota logout do admin

		User::logout();

		header("Location: /admin/login");
		exit;

	});

	$app -> run(); // Aqui é executado toda a aplicação

?>