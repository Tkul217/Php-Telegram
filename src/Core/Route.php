<?php

namespace App\Core;

class Route
{
    /**
     * @param $url
     * @param $contoller
     * @param $action
     * @return mixed|void
     */
    public function call($url, $contoller, $action) {
        if ($this->checkUrl($url) && $this->checkController($contoller, $action)) {
            $controller = new $contoller();
            $controller->$action();
        }
    }

    private function checkUrl($url): bool
    {
        return $url === $_SERVER['REQUEST_URI'];
    }

    private function checkController($controller, $action): bool
    {
        return class_exists($controller) && method_exists($controller, $action);
    }
//    public function ErrorPage404()
//    {
//        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
//        header('HTTP/1.1 404 Not Found');
//        header("Status: 404 Not Found");
//        header('Location:'.$host.'404');
//    }
}