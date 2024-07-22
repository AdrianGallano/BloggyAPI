<?php

namespace Src\Services;

use Src\Config\DatabaseConnector;
use Src\Models\UserBlogComment;
use Src\Utils\Response;
use Src\Utils\Checker;
use Src\Services\TokenService;
use Src\Utils\Filter;

class UserBlogCommentService
{
    private $pdo;
    private $userBlogCommentModel;
    private $tokenService;
    private $filter;
    function __construct()
    {
        $this->pdo = (new DatabaseConnector)->getConnection();
        $this->userBlogCommentModel = new UserBlogComment($this->pdo);
        $this->tokenService = new TokenService();
        $this->filter = new Filter("user_id", "blog_id", "comment_id");
    }
    function create($userBlogComment)
    {
        $token = $this->tokenService->readEncodedToken();

        if (!$token) {
            return Response::payload(404, false, "unauthorized access");
        }

        if (!Checker::isFieldExist($userBlogComment, ["user_id", "blog_id", "comment_id"])) {
            return Response::payload(
                400,
                false,
                "user_id, blog_id, and comment_id is required"
            );
        }

        $creation = $this->userBlogCommentModel->create($userBlogComment);

        return $creation ? Response::payload(
            201,
            true,
            "User blog comment creation successful",
            array("userBlogComment" => $creation)
        ) : Response::payload(400, False, message: "Contact administrator (adriangallanomain@gmail.com)",);
    }

    function get($blogId, $commentId)
    {
        $token = $this->tokenService->readEncodedToken();

        if (!$token) {
            return Response::payload(404, false, "unauthorized access");
        }

        $userBlogComment = $this->userBlogCommentModel->get($blogId, $commentId);

        if (!$userBlogComment) {
            return Response::payload(404, false, "User blog comment not found");
        }
        return $userBlogComment ? Response::payload(
            200,
            true,
            "User blog comment found",
            array("userBlogComment" => $userBlogComment)
        ) :  Response::payload(400, False, message: "Contact administrator (adriangallanomain@gmail.com)",);
    }

    function getAll()
    {
        $token = $this->tokenService->readEncodedToken();

        if (!$token) {
            return Response::payload(404, false, "unauthorized access");
        }

        $filterStr = $this->filter->getFilterStr();

        if (str_contains($filterStr, "unavailable") || str_contains($filterStr, "empty")) {
            return Response::payload(400, false, $filterStr);
        }

        $userBlogComments = $this->userBlogCommentModel->getAll($filterStr);

        if (!$userBlogComments) {
            return Response::payload(404, false, "User blog comment not found");
        }
        return $userBlogComments ? Response::payload(
            200,
            true,
            "User blog comment found",
            array("userBlogComment" => $userBlogComments)
        ) : Response::payload(400, False, message: "Contact administrator (adriangallanomain@gmail.com)",);
    }

    function delete($blogId, $commentId)
    {
        $token = $this->tokenService->readEncodedToken();

        if (!$token) {
            return Response::payload(404, false, "unauthorized access");
        }

        $userBlogComment = $this->userBlogCommentModel->delete($blogId, $commentId);

        if (!$userBlogComment) {
            return Response::payload(404, false, "deletion unsuccessful");
        }

        return $userBlogComment ? Response::payload(
            200,
            true,
            "deletion successful",
        ) : Response::payload(400, False, message: "Contact administrator (adriangallanomain@gmail.com)",);
    }
}
