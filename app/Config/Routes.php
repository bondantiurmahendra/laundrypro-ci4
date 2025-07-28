<?php

use CodeIgniter\Router\RouteCollection;
/**
 * @var RouteCollection $routes
 */ 
 
$routes->setAutoRoute(false); 
$routes->get('/', 'Auth::index');

$adminMenu = [
  ["path" => "index","route"=> ""],
  ["path" => "pesanan","route"=> "pesanan"],
  ["path" => "layanan","route"=> "layanan"], 
  ["path" => "pelanggan","route"=> "pelanggan"],
  ["path" => "akun","route"=> "akun"],
]; 

$userMenu = [
  ["path" => "index","route"=> ""],
  ["path" => "order","route"=> "order"],
  ["path" => "akun","route"=> "akun"],
]; 

foreach ($adminMenu as $route) {
  $routes->get("/admin/{$route['route']}", function () use ($route) {
      return view("protected/admin/{$route['path']}");
  });
}

foreach ($userMenu as $route) {
  $routes->get("/user/{$route['route']}", function () use ($route) {
      return view("protected/user/{$route['path']}");
  });
}

// API
$routes->post('/send-email', 'BrevoMain::send');
$routes->get('/imagez', 'Images::index'); 
$routes->get('checkout', 'Checkout::index'); 
$routes->post('checkout/token', 'Checkout::token');
$routes->set404Override(function () {
  return view('errors/custom_404');
});