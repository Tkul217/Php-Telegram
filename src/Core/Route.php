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
        return $url === strtok($_SERVER['REQUEST_URI'], '?');
    }

    private function checkController($controller, $action): bool
    {
        return class_exists($controller) && method_exists($controller, $action);
    }
}