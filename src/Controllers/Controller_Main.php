<?php

class Controller_Main extends \App\Core\Controller
{
    public function action_index()
    {
        $this->view->generate('main_view.php', 'template_view.php');
    }
}