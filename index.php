<?php 

	require_once("vendor/autoload.php");

	use \Slim\Slim;
	use \Hcode\Page;
	use \Hcode\PageAdmin;

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

		$page = new PageAdmin();

		$page -> setTpl("index");

	});

	$app -> run(); // Aqui é executado toda a aplicação

?>