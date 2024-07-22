<?php

namespace Src\Services;

use Src\Models\Authentication;
use Src\Models\User;
use Src\Config\DatabaseConnector;
use Src\Utils\Checker;
use Src\Utils\Response;
use Src\Utils\Filter;

class UserService
{
    private $authenticationModel;
    private $pdo;
    private $userModel;
    private $tokenService;
    private $filter;

    function __construct()
    {
        $this->pdo = (new DatabaseConnector())->getConnection();
        $this->authenticationModel = new Authentication($this->pdo);
        $this->userModel = new User($this->pdo);
        $this->tokenService = new TokenService();
        $this->filter = new Filter("username", "first_name", "last_name", "email");
    }

    function register($user)
    {
        if (!(Checker::isFieldExist($user, ["username", "first_name", "last_name", "email", "password", "confirm_password"]))) {
            return Response::payload(
                400,
                false,
                "username, first_name, last_name, email, password, confirm_password is required"
            );
        }

        $errors = $this->validate($user);

        if (count($errors) > 0) {
            return Response::payload(
                400,
                false,
                "registration unsuccessful",
                errors: $errors
            );
        }

        $creation = $this->userModel->create($user);
        return $creation ? Response::payload(
            201,
            true,
            "registration successful",
            array("user_id" => $this->userModel->get($creation)),
            $errors
        ) : Response::payload(
            400,
            False,
            message: "Contact administrator (adriangallanomain@gmail.com)",
        );
    }

    function getInformation($id)
    {
        $token = $this->tokenService->readEncodedToken();

        if (!$token) {
            return Response::payload(404, false, "unauthorized access");
        }

        $user = $this->userModel->get($id);

        if (!$user) {
            return Response::payload(404, false, "user not found");
        }

        return Response::payload(200, true, "found user", array("user" => $user));
    }

    function getAllUser()
    {
        $token = $this->tokenService->readEncodedToken();

        if (!$token) {
            return Response::payload(404, false, "unauthorized access");
        }

        $filterStr = $this->filter->getFilterStr();

        if (str_contains($filterStr, "unavailable") || str_contains($filterStr, "empty")) {
            return Response::payload(400, false, $filterStr);
        }

        $user = $this->userModel->getAll($filterStr);

        if (!$user) {
            return Response::payload(404, false, "user not found");
        }

        return Response::payload(200, true, "found user", array("user" => $user));
    }
    function deleteUser($id)
    {
        $matches = $this->tokenService->isTokenMatch($id);
        if (!$matches) {
            return Response::payload(401, false, "unauthorized access");
        }

        $isDeleted = $this->userModel->delete($id);

        if (!$isDeleted) {
            return Response::payload(500, false, "Deletion Unsuccessful");
        }

        return Response::payload(200, true, "Deletion successful");
    }

    function updateUser($id, $newUserInfo)
    {
        $matches = $this->tokenService->isTokenMatch($id);
        if (!$matches) {
            return Response::payload(401, false, "unauthorized access");
        }

        if (count($newUserInfo) < 1) {
            return Response::payload(400, false, "no fields found");
        }

        $errors = $this->validate($newUserInfo);

        if (count($errors) > 0) {
            return Response::payload(400, false, "Update Unsuccessful", errors: $errors);
        }

        if (!$this->userModel->get($id)) {
            return Response::payload(404, false, "User not found");
        }

        $updated_user = $this->userModel->update($id, $newUserInfo);
        return $updated_user ? Response::payload(200, true, "Update successful", array("user" => $this->userModel->get($id)))
            : Response::payload(400, False, message: "Contact administrator (adriangallanomain@gmail.com)",);
    }

    function uploadAvatar($userId, $files)
    {
        $matches = $this->tokenService->isTokenMatch($userId);
        if (!$matches) {
            return Response::payload(401, false, "unauthorized access");
        }

        $this->userModel->uploadAvatar($userId, $files);
        return Response::payload(200, true, "Image uploaded successfully", array("user" => $this->userModel->get($userId)));
    }
    function validate($user)
    {
        $errors = array();

        if (Checker::isFieldExist($user, ["username"])) {
            $isUsernameExist = $this->UsernameExist($user["username"]);
            $validateUsername = $this->validateUsernameFormat($user["username"]);

            if ($isUsernameExist) $errors["username"] = $isUsernameExist;
            if ($validateUsername) $errors["username"] = $validateUsername;
        }
        if (Checker::isFieldExist($user, ["first_name"])) {
            $validateFirstName = $this->validateFirstNameFormat($user["first_name"]);
            if ($validateFirstName) $errors["first_name"] = $validateFirstName;
        }
        if (Checker::isFieldExist($user, ["last_name"])) {
            $validateLastName = $this->validateLastNameFormat($user["last_name"]);
            if ($validateLastName) $errors["last_name"] = $validateLastName;
        }
        if (Checker::isFieldExist($user, ["email"])) {
            $isEmailExist = $this->EmailExist($user["email"]);
            $validateEmail = $this->validateEmailFormat($user["email"]);

            if ($isEmailExist) $errors["email"] = $isEmailExist;
            if ($validateEmail) $errors["email"] = $validateEmail;
        }
        if (Checker::isFieldExist($user, ["password"])) {
            $validatePassword = $this->validatePasswordFormat($user["password"]);
            $isConfirmPasswordMatch = $this->confirmPasswordDoesNotMatch($user["password"], $user["confirm_password"]);

            if ($validatePassword) $errors["password"] = $validatePassword;
            if ($isConfirmPasswordMatch) $errors["password1"] = $isConfirmPasswordMatch;
        }

        return $errors;
    }

    function UsernameExist($username)
    {
        $username = $this->authenticationModel->get("username", $username);
        return $username == true ? "username already exist" : false;
    }

    function EmailExist($email)
    {
        $email = $this->authenticationModel->get("email", $email);
        return $email == true ? "email already exist" : false;
    }

    function validateUsernameFormat($username)
    {
        return preg_match('/^[a-zA-Z0-9_]+$/', $username) ? null : "username should contain only letters, numbers, and underscores";
    }
    function validateFirstNameFormat($first_name)
    {
        return preg_match('/^[a-zA-Z ]+$/', $first_name) ? null : "should contain only letters and spaces";
    }
    function validateLastNameFormat($last_name)
    {
        return preg_match('/^[a-zA-Z ]+$/', $last_name) ? null : "should contain only letters and spaces";
    }
    function validateEmailFormat($email)
    {
        return preg_match('/^[\w.-]+@[a-zA-Z\d.-]+\.[a-zA-Z]{2,}$/', $email) ? null : "please enter a valid email address";
    }
    function validatePasswordFormat($password)
    {
        $requirements = "password should be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character";
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password) ? null : $requirements;
    }
    function confirmPasswordDoesNotMatch($password, $password2)
    {
        return $password !== $password2 ? "password does not match" : false;
    }
}
