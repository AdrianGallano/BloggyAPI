<?php

namespace Src\Services;

use Src\Models\Comment;
use Src\Config\DatabaseConnector;
use Src\Utils\Checker;
use Src\Utils\Response;
use Src\Utils\Filter;

class CommentService
{
    private $pdo;
    private $tokenService;
    private $commentModel;
    private $filter;
    function __construct()
    {
        $this->pdo = (new DatabaseConnector())->getConnection();
        $this->commentModel = new Comment($this->pdo);
        $this->tokenService = new TokenService();
        $this->filter = new Filter("content");
    }

    function create($comment)
    {
        $token = $this->tokenService->readEncodedToken();

        if (!$token) {
            return Response::payload(404, false, "unauthorized access");
        }

        if (!Checker::isFieldExist($comment, ["content"])) {
            return Response::payload(
                400,
                false,
                "content is required"
            );
        }

        $commentId = $this->commentModel->create($comment);

        if ($commentId === false) {
            return Response::payload(500, false, array("message" => "Contact administrator (adriangallanomain@gmail.com)"));
        }

        return $commentId ? Response::payload(
            201,
            true,
            "comment created successfully",
            array("comment" => $this->commentModel->get($commentId))
        ) : Response::payload(400, False, message: "Contact administrator (adriangallanomain@gmail.com)",);
    }
    function get($commentId)
    {
        $comment = $this->commentModel->get($commentId);

        if (!$comment) {
            return Response::payload(404, false, "comment not found");
        }
        return $comment ? Response::payload(
            200,
            true,
            "comment found",
            array("comment" => $comment)
        ) : Response::payload(400, False, message: "Contact administrator (adriangallanomain@gmail.com)",);
    }

    function getAll()
    {
        $filterStr = $this->filter->getFilterStr();

        if (str_contains($filterStr, "unavailable") || str_contains($filterStr, "empty")) {
            return Response::payload(400, false, $filterStr);
        }

        $comments = $this->commentModel->getAll($filterStr);

        if (!$comments) {
            return Response::payload(404, false, "comments not found");
        }
        return $comments ? Response::payload(
            200,
            true,
            "comments found",
            array("comment" => $comments)
        ) : Response::payload(400, False, message: "Contact administrator (adriangallanomain@gmail.com)",);
    }

    function update($comment, $commentId)
    {
        $token = $this->tokenService->readEncodedToken();

        if (!$token) {
            return Response::payload(404, false, "unauthorized access");
        }

        $comment = $this->commentModel->update($comment, $commentId);

        if (!$comment) {
            return Response::payload(404, false, "update unsuccessful");
        }

        return $comment ? Response::payload(
            200,
            true,
            "comment updated successfully",
            array("comment" => $this->commentModel->get($commentId))
        ) : Response::payload(400, False, message: "Contact administrator (adriangallanomain@gmail.com)",);
    }
    function delete($commentId)
    {
        $token = $this->tokenService->readEncodedToken();

        if (!$token) {
            return Response::payload(404, false, "unauthorized access");
        }

        $comment = $this->commentModel->delete($commentId);

        if (!$comment) {
            return Response::payload(404, false, "deletion unsuccessful");
        }

        return $comment ? Response::payload(
            200,
            true,
            "comment deleted successfully",
        ) : Response::payload(400, False, message: "Contact administrator (adriangallanomain@gmail.com)",);
    }
}
