<?php

namespace Src\Services;

use Src\Models\Blog;
use Src\Config\DatabaseConnector;
use Src\Utils\Checker;
use Src\Utils\Response;
use Src\Utils\Filter;

class BlogService
{
    private $pdo;
    private $tokenService;
    private $blogModel;
    private $filter;
    function __construct()
    {
        $this->pdo = (new DatabaseConnector())->getConnection();
        $this->blogModel = new Blog($this->pdo);
        $this->tokenService = new TokenService();
        $this->filter = new Filter("title", "summary", "user_id");
    }

    function create($blog)
    {
        $token = $this->tokenService->readEncodedToken();

        if (!$token) {
            return Response::payload(404, false, "unauthorized access");
        }

        if (!Checker::isFieldExist($blog, ["title", "summary","content", "user_id"])) {
            return Response::payload(
                400,
                false,
                "title, summary, content, user_id is required"
            );
        }

        $blogId = $this->blogModel->create($blog);

        if ($blogId === false) {
            return Response::payload(500, false, array("message" => "Contact administrator (adriangallanomain@gmail.com)"));
        }

        return $blogId ? Response::payload(
            201,
            true,
            "blog created successfully",
            array("blog" => $this->blogModel->get($blogId))
        ) : Response::payload(400, False, message: "Contact administrator (adriangallanomain@gmail.com)",);
    }
    function get($blogId)
    {

        $blog = $this->blogModel->get($blogId);

        if (!$blog) {
            return Response::payload(404, false, "blog not found");
        }
        return $blog ? Response::payload(
            200,
            true,
            "blog found",
            array("blog" => $blog)
        ) : Response::payload(400, False, message: "Contact administrator (adriangallanomain@gmail.com)",);
    }

    function getAll()
    {
        $filterStr = $this->filter->getFilterStr();

        if (str_contains($filterStr, "unavailable") || str_contains($filterStr, "empty")) {
            return Response::payload(400, false, $filterStr);
        }

        $blogs = $this->blogModel->getAll($filterStr);

        if (!$blogs) {
            return Response::payload(404, false, "blogs not found");
        }
        return $blogs ? Response::payload(
            200,
            true,
            "blogs found",
            array("blog" => $blogs)
        ) : Response::payload(400, False, message: "Contact administrator (adriangallanomain@gmail.com)",);
    }

    function update($blog, $blogId)
    {
        $token = $this->tokenService->readEncodedToken();

        if (!$token) {
            return Response::payload(404, false, "unauthorized access");
        }

        $blog = $this->blogModel->update($blog, $blogId);

        if (!$blog) {
            return Response::payload(404, false, "update unsuccessful");
        }

        return $blog ? Response::payload(
            200,
            true,
            "blog updated successfully",
            array("blog" => $this->blogModel->get($blogId))
        ) : Response::payload(400, False, message: "Contact administrator (adriangallanomain@gmail.com)",);
    }
    function delete($blogId)
    {
        $token = $this->tokenService->readEncodedToken();

        if (!$token) {
            return Response::payload(404, false, "unauthorized access");
        }

        $blog = $this->blogModel->delete($blogId);

        if (!$blog) {
            return Response::payload(404, false, "deletion unsuccessful");
        }

        return $blog ? Response::payload(
            200,
            true,
            "blog deleted successfully",
        ) : Response::payload(400, False, message: "Contact administrator (adriangallanomain@gmail.com)",);
    }
}
