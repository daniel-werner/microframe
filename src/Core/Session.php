<?php

namespace Microframe\Core;

class Session
{

    private $user_id;
    private $username;
    private $last_login;

    public const MAX_LOGIN_AGE = 60 * 60 * 24; // 1 day

    public function __construct()
    {
        if (!session_id()) {
            session_start();
        }

        $this->checkStoredLogin();
    }

    public function login($user)
    {
        if ($user) {
            // prevent session fixation attacks
            session_regenerate_id();
            $this->user_id = $_SESSION['user_id'] = $user->id;
            $this->username = $_SESSION['username'] = $user->username;
            $this->last_login = $_SESSION['last_login'] = time();
        }

        return true;
    }

    public function isLoggedIn()
    {
        return isset($this->user_id) && $this->lastLoginIsRecent();
    }

    public function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['last_login']);
        unset($this->user_id);
        unset($this->username);
        unset($this->last_login);
    }

    private function checkStoredLogin()
    {
        if (isset($_SESSION['user_id'])) {
            $this->admin_id = $_SESSION['user_id'];
            $this->username = $_SESSION['username'];
            $this->last_login = $_SESSION['last_login'];
        }
    }

    private function lastLoginIsRecent()
    {
        if (!isset($this->last_login)) {
            return false;
        } elseif (($this->last_login + self::MAX_LOGIN_AGE) < time()) {
            $this->logout();

            return false;
        } else {
            return true;
        }
    }

    public function userId()
    {
        return $this->user_id;
    }

    public function username()
    {
        return $this->username;
    }

    public function message($msg = '')
    {
        if (!empty($msg)) {
            // Then this is a "set" message
            $_SESSION['message'] = $msg;

            return true;
        } else {

            // Then this is a "get" message
            return $_SESSION['message'] ?? '';
        }
    }

    public function clearMessage()
    {
        unset($_SESSION['message']);
    }

    public function put($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key)
    {
        return $_SESSION[$key] ?? '';
    }

    public function forget($key)
    {
        unset($_SESSION[$key]);
    }

    public function all()
    {
        return $_SESSION;
    }
}
