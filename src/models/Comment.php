<?php

namespace Src\Models;

use PDOException;

class Comment
{
    private $pdo;
    function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    function get($id)
    {
        $queryStr = "SELECT * FROM Comment WHERE comment_id = :id";

        $stmt = $this->pdo->prepare($queryStr);

        try {
            $stmt->execute(array(
                "id" => $id
            ));

            $comment = $stmt->fetch();
            return $comment;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    function getAll($filter = "")
    {
        if ($filter == "") {
            $queryStr = "SELECT * FROM Comment";
        } else {
            $queryStr = "SELECT * FROM Comment WHERE $filter";
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
        $content = $request["content"];

        $queryStr = "INSERT INTO 
        Comment(content) VALUES
        (:content)";

        $stmt = $this->pdo->prepare($queryStr);

        try {
            $stmt->execute(array(
                "content" => $content,
            ));
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }


    function delete($id)
    {
        $queryStr = "DELETE FROM Comment WHERE comment_id = :id";

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
        $content = $request["content"];

        $queryStr = "UPDATE Comment 
            SET content=:content WHERE comment_id = :id";

        $stmt = $this->pdo->prepare($queryStr);
        try {
            $stmt->execute(array(
                "id" => $id,
                "content" => $content,
            ));
            return $id;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
