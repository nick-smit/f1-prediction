<?php

namespace App\Http\Controllers;

use Inertia\ResponseFactory;

class HomeController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ResponseFactory $responseFactory)
    {
        return $responseFactory->render('Home');
    }
}
