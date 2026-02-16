<?php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = strtoupper($_SERVER['REQUEST_METHOD']);

$routes = [];
$routes['/']['GET'] = "home@Home";
$routes['/users']['GET'] = "users@Users";
$routes['/users/edit/{id}']['GET'] = "users_edit@Users Edit";

$found = false;

foreach ($routes as $route => $methods) {

    if (!isset($methods[$method])) {
        continue;
    }

    // Convert {id} to regex pattern
    $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $route);
    $pattern = "#^" . $pattern . "$#";

    if (preg_match($pattern, $uri, $matches)) {

        array_shift($matches); // remove full match

        $pageData = $methods[$method];
        [$page, $title] = explode("@", $pageData);

        view($page, [
            'title' => $title,
            'params' => $matches
        ]);

        $found = true;
        break;
    }
}

if (!$found) {
    http_response_code(404);
    echo "404 Page could not be found.";
}


function view($view, $data = [])
{
    render($view, $data);
}

function render($view, $data = [])
{
    extract($data);

    ob_start();
    require "{$view}.php";
    $content = ob_get_clean();

    require "default.php";
}
