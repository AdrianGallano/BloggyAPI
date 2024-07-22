<?php

namespace Src\Routes\Api\V1;

use FastRoute;
use Src\Controllers\TokenController;
use Src\Controllers\UserController;
use Src\Controllers\BlogController;
use Src\Controllers\CommentController;

class Router
{
    private $dispatcher;
    public function __construct()
    {
        $this->dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {

            /* users */
            $r->addRoute('POST', '/users', [UserController::class, 'postUser']);
            $r->addRoute('GET', '/users', [UserController::class, 'getAllUser']);
            $r->addRoute('GET', '/users/{userId:\d+}', [UserController::class, 'getUser']);
            $r->addRoute('PUT', '/users/{userId:\d+}', [UserController::class, 'updateUser']);
            $r->addRoute('DELETE', '/users/{userId:\d+}', [UserController::class, 'deleteUser']);
            $r->addRoute('POST', '/users/{userId:\d+}/avatar', [UserController::class, 'uploadAvatar']);

            /* tokens */
            $r->addRoute('POST', '/token', [TokenController::class, 'postToken']);

            /* blogs */
            $r->addRoute('POST', '/blogs', [BlogController::class, 'createBlog']);
            $r->addRoute('GET', '/blogs', [BlogController::class, 'getAllBlogs']);
            $r->addRoute('GET', '/blogs/{blogId:\d+}', [BlogController::class, 'getBlog']);
            $r->addRoute('DELETE', '/blogs/{blogId:\d+}', [BlogController::class, 'deleteBlog']);
            $r->addRoute('PUT', '/blogs/{blogId:\d+}', [BlogController::class, 'updateBlog']);
            

            /* comment */
            $r->addRoute('POST', '/comments', [CommentController::class, 'createComment']);
            $r->addRoute('GET', '/comments', [CommentController::class, 'getAllComments']);
            $r->addRoute('GET', '/comments/{commentId:\d+}', [CommentController::class, 'getComment']);
            $r->addRoute('DELETE', '/comments/{commentId:\d+}', [CommentController::class, 'deleteComment']);
            $r->addRoute('PUT', '/comments/{commentId:\d+}', [CommentController::class, 'updateComment']);

            /* userblogcomment */
        });
    }

    public function handle($method, $uri)
    {
        $routeInfo = $this->dispatcher->dispatch($method, $uri);
        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                http_response_code(404);
                echo json_encode(array("error" => "Not found"));
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                http_response_code(405);
                echo json_encode(array("error" => "Method not allowed"));
                break;
            case FastRoute\Dispatcher::FOUND:
                $controllerName = $routeInfo[1][0];
                $method = $routeInfo[1][1];
                $vars = $routeInfo[2];

                $controller = new $controllerName();

                if (count($vars) == 0) {
                    $controller->$method();
                } else {
                    $controller->$method($vars);
                }
                break;
        }
    }
}
