<?php

namespace Src\Controllers;

use Src\Services\UserBlogCommentService;


class UserBlogCommentController
{
    private $userBlogCommentService;
    function __construct()
    {
        $this->userBlogCommentService = new UserBlogCommentService();
    }

    function createUserBlogComment()
    {

        $postData = json_decode(file_get_contents("php://input"));
        $postData = json_decode(json_encode($postData), true);
        $payload = $this->userBlogCommentService->create($postData);

        if (array_key_exists("code", $payload)) {
            http_response_code($payload["code"]);
            unset($payload["code"]);
        }
        echo json_encode($payload);
    }

    function getAllUserBlogComments()
    {
        $payload = $this->userBlogCommentService->getAll();

        if (array_key_exists("code", $payload)) {
            http_response_code($payload["code"]);
            unset($payload["code"]);
        }
        echo json_encode($payload);
    }

    function getUserBlogComment($request)
    {
        $blogId = $request["blogId"];
        $commentId = $request["commentId"];
        $payload = $this->userBlogCommentService->get($blogId, $commentId);

        if (array_key_exists("code", $payload)) {
            http_response_code($payload["code"]);
            unset($payload["code"]);
        }
        echo json_encode($payload);
    }

    function deleteUserBlogComment($request)
    {
        $blogId = $request["blogId"];
        $commentId = $request["commentId"];
        $payload = $this->userBlogCommentService->delete($blogId, $commentId);

        if (array_key_exists("code", $payload)) {
            http_response_code($payload["code"]);
            unset($payload["code"]);
        }
        echo json_encode($payload);
    }

    
}