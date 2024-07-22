<?php

namespace Src\Models;

use PDOException;

class UserBlogComment
{
    private $pdo;
    function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    function create($request)
    {
        $user_id = $request["user_id"];
        $blog_id = $request["blog_id"];
        $comment_id = $request["comment_id"];

        $queryStr = "INSERT INTO 
        UserBlogComment(user_id, blog_id, comment_id) VALUES
        (:user_id, :blog_id, :comment_id)";

        $stmt = $this->pdo->prepare($queryStr);

        try {
            $stmt->execute(array(
                "user_id" => $user_id,
                "blog_id" => $blog_id,
                "comment_id" => $comment_id
            ));
            return array(
                "user_id" => $user_id,
                "blog_id" => $blog_id,
                "comment_id" => $comment_id
            );
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    function get($blog_id, $comment_id)
    {
        $queryStr = "SELECT 
            Comment.content as comment_content, 
            Comment.is_edited as comment_is_edited,
            Comment.created_at as comment_created_at,
            User.username, User.status, User.email, User.image_name, 
            Blog.title as blog_title,Blog.content as blog_content,
            Blog.summary as blog_summary,Blog.created_at as blog_created_at,
            Blog.updated_at as blog_updated_at FROM UserBlogComment
            JOIN Comment ON UserBlogComment.comment_id = Comment.comment_id
            JOIN User ON UserBlogComment.user_id = User.user_id
            JOIN Blog ON UserBlogComment.blog_id = Blog.blog_id WHERE UserBlogComment.blog_id = :blog_id AND UserBlogComment.comment_id = :comment_id";
        $stmt = $this->pdo->prepare($queryStr);

        try {
            $stmt->execute(array(
                "comment_id" => $comment_id,
                "blog_id" => $blog_id
            ));

            $workspace = $stmt->fetch();

            return $workspace;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    function getAll($filterStr = "")
    {
        if ($filterStr == "") {
            $queryStr = "SELECT 
            Comment.content as comment_content, 
            Comment.is_edited as comment_is_edited,
            Comment.created_at as comment_created_at,
            User.username, User.status, User.email, User.image_name, 
            Blog.title as blog_title,Blog.content as blog_content,
            Blog.summary as blog_summary,Blog.created_at as blog_created_at,
            Blog.updated_at as blog_updated_at FROM UserBlogComment
            JOIN Comment ON UserBlogComment.comment_id = Comment.comment_id
            JOIN User ON UserBlogComment.user_id = User.user_id
            JOIN Blog ON UserBlogComment.blog_id = Blog.blog_id";
        } else {
            $queryStr = "SELECT 
            Comment.content as comment_content, 
            Comment.is_edited as comment_is_edited,
            Comment.created_at as comment_created_at,
            User.username, User.status, User.email, User.image_name, 
            Blog.title as blog_title,Blog.content as blog_content,
            Blog.summary as blog_summary,Blog.created_at as blog_created_at,
            Blog.updated_at as blog_updated_at FROM UserBlogComment
            JOIN Comment ON UserBlogComment.comment_id = Comment.comment_id
            JOIN User ON UserBlogComment.user_id = User.user_id
            JOIN Blog ON UserBlogComment.blog_id = Blog.blog_id WHERE UserBlogComment.$filterStr";
        }
        try {
            $stmt = $this->pdo->prepare($queryStr);
            $stmt->execute();
            $workspace = $stmt->fetchAll();
            return $workspace;
        } catch (PDOException $e) {
            var_dump($e);
            error_log($e->getMessage());
            return null;
        }
    }

    function delete($blog_id, $comment_id)
    {
        $queryStr = "DELETE FROM UserBlogComment WHERE blog_id = :blog_id AND comment_id = :comment_id";

        $stmt = $this->pdo->prepare($queryStr);
        try {
            $stmt->execute(
                array(
                    "blog_id" => $blog_id,
                    "comment_id" => $comment_id
                )
            );
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
