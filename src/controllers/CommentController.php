<?php
namespace Src\Controllers;

use Src\Services\CommentService;

class CommentController
{
    private $commentService;
    function __construct()
    {
        $this->commentService = new CommentService();
    }

    function createComment()
    {

        $postData = json_decode(file_get_contents("php://input"));
        $postData = json_decode(json_encode($postData), true);
        $payload = $this->commentService->create($postData);

        if (array_key_exists("code", $payload)) {
            http_response_code($payload["code"]);
            unset($payload["code"]);
        }
        echo json_encode($payload);
    }

    function getComment($request)
    {
        $commentId = $request["commentId"];
        $payload = $this->commentService->get($commentId);

        if (array_key_exists("code", $payload)) {
            http_response_code($payload["code"]);
            unset($payload["code"]);
        }
        echo json_encode($payload);
    }

    function getAllComments()
    {
        $payload = $this->commentService->getAll();

        if (array_key_exists("code", $payload)) {
            http_response_code($payload["code"]);
            unset($payload["code"]);
        }
        echo json_encode($payload);
    }

    function deleteComment($request)
    {
        $commentId = $request["commentId"];
        $payload = $this->commentService->delete($commentId);

        if (array_key_exists("code", $payload)) {
            http_response_code($payload["code"]);
            unset($payload["code"]);
        }
        echo json_encode($payload);
    }

    function updateComment($request)
    {
        $commentId = $request["commentId"];
        $postData = json_decode(file_get_contents("php://input"));
        $postData = json_decode(json_encode($postData), true);
        $payload = $this->commentService->update($postData, $commentId);

        if (array_key_exists("code", $payload)) {
            http_response_code($payload["code"]);
            unset($payload["code"]);
        }
        echo json_encode($payload);
    }
}
