<?php
require 'Sql.php';

class Calls
{
    private array $post_data = [
        'full_name' => '',
        'login' => '',
        'password' => '',
        'date_of_birth' => '',
        'is_active' => 'true'
    ];
    static public string $ErrIfStingEmpty = '';

    private function CompleteAuthenticate($user)
    {
        $_SESSION['id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['login'] = $user['login'];
        $_SESSION['date_of_birth'] = $user['date_of_birth'];
    }

    public function Registration(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $name = trim($_POST['full_name']);
            $login = trim($_POST['login']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $DOfBr = trim($_POST['date_of_birth']);

            if ($name === '' || $login === '' || $password === '' || $DOfBr === '') {
                self::$ErrIfStingEmpty = 'Не все поля заполнены';
            } elseif (mb_strlen($login) < 3) {
                self::$ErrIfStingEmpty = 'Логин должен быть длинее 3-х символов';
            } elseif (!empty($sql = (new Sql())->selectOne('Users', 'login', $login))) {
                self::$ErrIfStingEmpty = 'Пользователь с таким логином уже зарегистрирован';
            } else {
                $this->post_data['full_name'] = $name;
                $this->post_data['login'] = $login;
                $this->post_data['password'] = $password;
                $this->post_data['date_of_birth'] = $DOfBr;

                // return $this->pdo->lastInsertId();
                $id = (new Sql())->insertIntoTable('Users', $this->post_data);
                $user = (new Sql())->selectOne('Users', 'id', $id);
                $this->CompleteAuthenticate($user);
                header('location: ' . 'Pages/Lk.php');
                exit();
            }
        }
    }

    public function Authentication(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if ($_POST['login'] === '' || $_POST['password'] === '') {
                self::$ErrIfStingEmpty = 'Вы заполнили не все поля';
            } elseif ($_POST['login'] !== '' && $_POST['password'] !== '') {
                $user = (new Sql())->selectOne('Users', 'login', trim($_POST['login']));
                if (!empty($user)) {
                    try {
                        if (password_verify(trim($_POST['password']), $user['password'])) {
                            $this->CompleteAuthenticate($user);
                            header('location: ' . 'Lk.php');
                            exit();
                        } else {
                            self::$ErrIfStingEmpty = 'Неверное имя пользователя или пароль';
                        }
                    } catch (Exception) {
                    }
                } else {
                    self::$ErrIfStingEmpty = 'Неверное имя пользователя или пароль';
                }
            }
        }
    }

    public function ChangeUserData($data): void
    {
        (new Sql())->updateIntoTable('Users', $data, $_SESSION['id']);
        $user = (new Sql())->selectOne('Users', 'id', $_SESSION['id']);
        $this->CompleteAuthenticate($user);
    }

    public function CustomSelect($data): void
    {
        (new Sql())->selectWhere('Users', $data);
    }

    #[\JetBrains\PhpStorm\NoReturn]
    public function Logout(): void
    {
        (new Sql())->SetOnlineStatus('Users', $_SESSION['id'], 'false');
        session_destroy();
        exit();
    }

    public function selectOnline(): void
    {
        (new Sql())->selectOnline();
    }

    public function PutInCsv(): void
    {
        (new Sql())->PutInCsv();
    }

}

try {
    $data = json_decode(file_get_contents('php://input'), true);
} catch (Exception $e) {

}


if (isset($_POST['submit-reg-form'])) {
    (new Calls())->Registration();
}
if (isset($data['playload']) && $data['playload'] === 'exit-from-lk') {
    (new Calls())->Logout();
}
if (isset($_POST['submit-auth-form'])) {
    (new Calls())->Authentication();
}
if (isset($data['playload']) && $data['playload'] === 'DataChange') {
    (new Calls())->ChangeUserData($data['data']);
}
if (isset($data['playload']) && $data['playload'] === 'custom-select') {
    (new CAlls())->CustomSelect($data['data']);
}
if (isset($data['playload']) && $data['playload'] === 'select-who-online') {
    (new CAlls())->selectOnline();
}
if (isset($data['playload']) && $data['playload'] === 'put-in-csv') {
    (new CAlls())->PutInCsv();
}