<?php

namespace App\Http\Controllers\Frontend;

class HomeController extends FrontendController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
    	dd("ahfjdshk");
        parent::index();

        return $this->viewRender();
    }
}
