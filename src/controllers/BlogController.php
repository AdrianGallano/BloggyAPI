<?php
namespace Src\Controllers;

use Src\Services\BlogService;

class BlogController
{
    private $blogService;
    function __construct()
    {
        $this->blogService = new BlogService();
    }

    function createBlog()
    {

        $postData = json_decode(file_get_contents("php://input"));
        $postData = json_decode(json_encode($postData), true);
        $payload = $this->blogService->create($postData);

        if (array_key_exists("code", $payload)) {
            http_response_code($payload["code"]);
            unset($payload["code"]);
        }
        echo json_encode($payload);
    }

    function getBlog($request)
    {
        $blogId = $request["blogId"];
        $payload = $this->blogService->get($blogId);

        if (array_key_exists("code", $payload)) {
            http_response_code($payload["code"]);
            unset($payload["code"]);
        }
        echo json_encode($payload);
    }

    function getAllBlogs()
    {
        $payload = $this->blogService->getAll();

        if (array_key_exists("code", $payload)) {
            http_response_code($payload["code"]);
            unset($payload["code"]);
        }
        echo json_encode($payload);
    }

    function deleteBlog($request)
    {
        $blogId = $request["blogId"];
        $payload = $this->blogService->delete($blogId);

        if (array_key_exists("code", $payload)) {
            http_response_code($payload["code"]);
            unset($payload["code"]);
        }
        echo json_encode($payload);
    }

    function updateBlog($request)
    {
        $blogId = $request["blogId"];
        $postData = json_decode(file_get_contents("php://input"));
        $postData = json_decode(json_encode($postData), true);
        $payload = $this->blogService->update($postData, $blogId);

        if (array_key_exists("code", $payload)) {
            http_response_code($payload["code"]);
            unset($payload["code"]);
        }
        echo json_encode($payload);
    }
}
