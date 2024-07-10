<?php

class SessionManager {

    public $username;

    public function manageSession() {

        session_start();

        $this->username = isset($_SESSION['username']) ? $_SESSION['username'] : "Invitado";

        if (isset($_POST['logout'])) {
            session_destroy();
            header("Location: login.php");
            exit;
        }

    }

    public function login() {
        
        session_start();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            if ( isset($_POST['invitado']) ) {
                header('Location: index.php');
                exit;
            }

            if ($username === 'admin' && $password === 'admin') {
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                header('Location: index.php');
                exit;
            } else {
                return false;
            }
        }

    }

}

?>