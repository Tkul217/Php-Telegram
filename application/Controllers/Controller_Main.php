<?php

namespace Controllers;

use Core\Controller;

class Controller_Main extends Controller
{
    public function action_index()
    {
        $this->view->generate('main_view.php', 'template_view.php');
    }
}