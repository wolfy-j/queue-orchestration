<?php

/**
 * This file is part of Spiral package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Controller;

use Spiral\Router\Annotation\Route;

class HomeController
{
    #[Route('/home', "index")]
    public function index()
    {
        //return new DataView(['a' => 'b']);
    }
}
