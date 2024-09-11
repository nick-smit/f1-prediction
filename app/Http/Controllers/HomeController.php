<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Response;
use Inertia\ResponseFactory;

class HomeController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ResponseFactory $responseFactory): Response
    {
        return $responseFactory->render('Home');
    }
}
