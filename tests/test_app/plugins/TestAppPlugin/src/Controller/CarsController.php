<?php
declare(strict_types=1);

namespace TestApp\Controller;

use Cake\Controller\Controller;
use CakeAttributes\Attributes\Methods\Get;
use CakeAttributes\Attributes\Methods\Post;

class CarsController extends Controller
{
    #[Get('/cars')]
    public function index()
    {
    }

    #[Get('/cars/:id')]
    public function view(?int $id = null)
    {
    }

//    #[Get('/cars/add')]
    #[Post('/cars/add')]
    public function add()
    {
    }

    #[Get('/cars/edit/:id')]
//    #[Post('/cars/edit/:id')]
//    #[Patch('/cars/edit/:id')]
//    #[Put('/cars/edit/:id')]
    public function edit(?int $id = null)
    {
    }

    #[Post('/cars/delete/:id')]
//    #[Delete('/cars/delete/:id')]
    public function delete(?int $id = null)
    {
    }
}
