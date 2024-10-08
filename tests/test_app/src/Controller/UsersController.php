<?php
declare(strict_types=1);

namespace TestApp\Controller;

use Cake\Controller\Controller;
use CakeAttributes\Attributes\Methods\Get;

class UsersController extends Controller
{
    #[Get('/authenticate')]
    public function authenticate()
    {
    }
}
