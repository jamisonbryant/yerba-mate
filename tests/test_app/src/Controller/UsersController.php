<?php
declare(strict_types=1);

namespace TestApp\Controller;

use Cake\Controller\Controller;
use CakeAttributes\Attributes\Methods\Get;
use CakeAttributes\Attributes\Methods\Post;

class UsersController extends Controller
{
    #[Get('/users')]
    public function index()
    {
    }

    #[Get('/users/:id')]
    public function view(?int $id = null)
    {
    }

//    #[Get('/users/add')]
    #[Post('/users/add')]
    public function add()
    {
    }

    #[Get('/users/edit/:id')]
//    #[Post('/users/edit/:id')]
//    #[Patch('/users/edit/:id')]
//    #[Put('/users/edit/:id')]
    public function edit(?int $id = null)
    {
    }

    #[Post('/users/delete/:id')]
//    #[Delete('/users/delete/:id')]
    public function delete(?int $id = null)
    {
    }
}
