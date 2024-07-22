<?php

namespace Src\Models;

use PDOException;

class Blog
{
    private $pdo;
    function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    function get($id)
    {
        $queryStr = "SELECT * FROM Blog WHERE blog_id = :id";

        $stmt = $this->pdo->prepare($queryStr);

        try {
            $stmt->execute(array(
                "id" => $id
            ));

            $blog = $stmt->fetch();
            return $blog;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    function getAll($filter = "")
    {
        if ($filter == "") {
            $queryStr = "SELECT * FROM Blog";
        } else {
            $queryStr = "SELECT * FROM Blog WHERE $filter";
        }

        $stmt = $this->pdo->prepare($queryStr);

        try {
            $stmt->execute();

            $blogs = $stmt->fetchAll();
            return $blogs;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }
    function create($request)
    {
        $title = $request["title"];
        $summary = $request["summary"];
        $content = $request["content"];
        $user_id = $request["user_id"];

        $queryStr = "INSERT INTO 
        Blog(title, summary, content, user_id) VALUES
        (:title, :summary, :content, :user_id)";

        $stmt = $this->pdo->prepare($queryStr);

        try {
            $stmt->execute(array(
                "title" => $title,
                "summary" => $summary,
                "content" => $content,
                "user_id" => $user_id
            ));
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }


    function delete($id)
    {
        $queryStr = "DELETE FROM Blog WHERE blog_id = :id";

        $stmt = $this->pdo->prepare($queryStr);
        try {
            $stmt->execute(
                array(
                    "id" => $id,
                )
            );
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    function update($request, $id)
    {
        $title = $request["title"];
        $summary = $request["summary"];
        $content = $request["content"];
        $user_id = $request["user_id"];

        $queryStr = "UPDATE Blog 
            SET title=:title, summary=:summary, content=:content, user_id=:user_id WHERE blog_id = :id";

        $stmt = $this->pdo->prepare($queryStr);
        try {
            $stmt->execute(array(
                "id" => $id,
                "title" => $title,
                "summary" => $summary,
                "content" => $content,
                "user_id" => $user_id
            ));
            return $id;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
