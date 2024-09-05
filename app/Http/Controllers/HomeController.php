<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\ResponseFactory;

class HomeController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ResponseFactory $responseFactory): \Inertia\Response
    {
        return $responseFactory->render('Home');
    }
}
