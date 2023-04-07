<?php

// Include bootstrap file
// require_once __DIR__ . '/../bootstrap.php';

// Define routes
// $routes = [
//   ['GET', '/', 'home'],
//   ['GET', '/about', 'about'],
//   ['GET', '/contact', 'contact'],
//   ['GET', '/api/hi', 'api'],
// ];

// // Match the request to a route
// $request_method = $_SERVER['REQUEST_METHOD'];
// $request_uri = $_SERVER['REQUEST_URI'];

// foreach ($routes as $route) {
//   list($method, $uri, $function) = $route;
//   if ($method == $request_method && $uri == $request_uri) {
//     // Call the appropriate function
//     call_user_func($function);
//     exit;
//   }
// }

// // If no route was found, return a 404 error
// http_response_code(404);
// echo 'Not Found';

// // Define the home function
// function home() {
//   echo 'Welcome to the homepage!';
// }

// // Define the about function
// function about() {
//   echo 'This is the about page.';
// }

// // Define the contact function
// function contact() {
//   echo 'You can contact us at contact@example.com.';
// }

// function api() {
//   echo 'test';
// }

$routes = [
  'GET /api/greeting' => function($params) {
      $name = $params['name'] ?? 'World';
      $greeting = sprintf('Hello, %s!', $name);
      header('Content-Type: application/json');
      echo json_encode(['message' => $greeting]);
  },
  'GET /' => function() {
    echo 'test';
  }
  // Add more routes here
];

// Get request method and URI
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Find matching route
$handler = null;
foreach ($routes as $route => $func) {
  $parts = explode(' ', $route);
  $routeMethod = $parts[0];
  $routeUri = $parts[1];
  if ($requestMethod === $routeMethod && $requestUri === $routeUri) {
      $handler = $func;
      break;
  }
}

// Call route handler if found
if ($handler !== null) {
  $params = [];
  if ($requestMethod === 'GET') {
      $params = $_GET;
  }
  call_user_func($handler, $params);
} else {
  header('HTTP/1.1 404 Not Found');
  echo '404 Not Found';
}

// use App\Http\Router

// $router = new Router();

// $router->get('/', function () use ($router) {
//   return $app->version();
// });