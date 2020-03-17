<?php 
	session_start();
	require_once("vendor/autoload.php");

	use Hcode\DB\Sql;
	use Hcode\DB\Page;
	use Hcode\DB\PageAdmin;
	use Hcode\Model\User;
	use Hcode\Model\Category;

	$app = new \Slim\Slim();

	$app->config('debug', true);

	$app->get('/', function() {
		
		$page = new Page();

		$page->setTpl("index");

	});

	$app->get('/admin', function() {
		
		User::verifyLogin();
		
		$page = new PageAdmin();

		$page->setTpl("index");

	});

	$app->get('/admin/login', function() {
		
		$page = new PageAdmin([
			"header"=>false,
			"footer"=>false
		]);

		$page->setTpl("login");

	});

	$app->post('/admin/login', function() {
		User::login	($_POST["login"],$_POST["password"]);

		header("Location: /admin");
		exit;

	});

	$app->get('/admin/logout', function() {
		User::logout();
		
		header("Location: /admin/login");
		exit;
	

	});

	$app->get('/admin/users', function() {

		User::verifyLogin();
		$users = User::listAll();

		$page = new PageAdmin();

		$page->setTpl("users",array(
			"users"=>$users
		));

	});

	$app->get('/admin/users/create', function() {

		User::verifyLogin();
	
		$page = new PageAdmin();

		$page->setTpl("users-create");

	});

	// Rota para exclusão do registro
	$app->get('/admin/users/:iduser/delete', function($iduser) {

		User::verifyLogin();

		$user = new User();

		$user->get((int)$iduser);

		$user->delete();

		header("Location: /admin/users");
		exit;

	});

	$app->get('/admin/users/:iduser', function($iduser) {

		User::verifyLogin();

		$user = new User();

		$user->get((int)$iduser);


		$page = new PageAdmin();

		$page->setTpl("users-update",array(
			"user"=>$user->getValues()
		));


	});

	// Rota para criação do registro
	$app->post('/admin/users/create', function() {

		User::verifyLogin();

		$user = new User();
		$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

		$user->setData($_POST);

		$user->save();

		header("Location: /admin/users");
		exit;

	});

	// Rota para update do registro
	$app->post('/admin/users/:iduser', function($iduser) {

		User::verifyLogin();

		$user = new User();

		$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

		$user->get((int)$iduser);

		$user->setData($_POST);

		$user->update($iduser);

		header("Location: /admin/users");
		exit;


	});

	$app->get('/admin/forgot', function()
	{
		$page = new PageAdmin([
			"header"=>false,
			"footer"=>false
		]);

		$page->setTpl("forgot");

	});

	$app->post('/admin/forgot', function()
	{

		$result = User::getforgot($_POST["email"]);

		$page->setTpl("forgot");

	});

	$app->get('/admin/categories', function()
	{
		$categories = Category::listAll();

		$page = new PageAdmin();

		$page->setTpl("categories",[
			'categories'=>$categories
		]);

	});

	$app->get('/admin/categories/create', function()
	{

		$page = new PageAdmin();

		$page->setTpl("categories-create");

	});

	$app->post('/admin/categories/create', function()
	{

		$category = new Category();
		
		$category->setData($_POST);

		$category->save();

		header("Location: /admin/categories");
		exit;


	});

	// Rota para exclusão do registro de categorias
	$app->get('/admin/categories/:idcategory/delete', function($idcategory) {

		User::verifyLogin();

		$category = new Category();

		$category->get((int)$idcategory);

		$category->delete();

		header("Location: /admin/categories");
		exit;

	});
	
	// Edição de categorias
	$app->get('/admin/categories/:idcategory', function($idcategory) {

		User::verifyLogin();

		$category = new Category();

		$category->get((int)$idcategory);

		$page = new PageAdmin();

		$page->setTpl("categories-update",array(
			"category"=>$category->getValues()
		));


	});

	$app->post('/admin/categories/:idcategory', function($idcategory) {

		User::verifyLogin();

		$category = new Category();

		$category->setData($_POST);

		$category->update($idcategory);

		header("Location: /admin/categories");
		exit;



	});

	$app->run();
?>