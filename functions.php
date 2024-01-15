<?php

/* Подключение к БД  */
function connect(
    $host = 'localhost',
    $user = 'root',
    $pass = '',
    $dbname = 'profile')
{
    $link = new mysqli($host, $user, $pass, $dbname) or die('Ошибка подключения к БД');
    mysqli_select_db($link, $dbname) or die('Ошибка открытия БД');
    mysqli_query($link, "set names 'utf8'");

    return $link;
}


/* Регистрация */
function register($login, $phone, $email, $pass1, $pass2)
{
    $login = trim(htmlspecialchars($login));
    $phone = trim(htmlspecialchars($phone));
    $email = trim(htmlspecialchars($email));
    $pass1 = trim(htmlspecialchars($pass1));
    $pass2 = trim(htmlspecialchars($pass2));

    if ($login == '' || $phone == '' || $email == '' || $pass1 == '' || $pass2 == '') {
        echo '<h3><span style="color:red;">Заполните все поля!</h3>';
        return false;
    }

    if ($pass1 != $pass2) {
        echo '<h3><span style="color:red;">Пароль и подтверждение не совпадают</h3>';
        return false;
    }

    if (strlen($login) < 3 || strlen($login) > 30) {
        echo '<h3><span style="color:red;">Должно быть от 3 до 30 символов в имени!</h3>';
        return false;
    }

    if (strlen($pass1) < 3 || strlen($pass1) > 30) {
        echo '<h3><span style="color:red;">Должно быть от 3 до 30 символов в пароле!</h3>';
        return false;
    }

    $connect = connect();

    $sel = 'select * from users where login="' . $login . '"';
    $res = mysqli_query($connect, $sel);
    if (mysqli_fetch_array($res, MYSQLI_NUM)) {
        echo '<h3><span style="color:red;">Пользователь с таким именем существует!</h3>';
        mysqli_free_result($res);
        return false;
    }

    $sel = 'select * from users where phone="' . $phone . '" or email="' . $email . '"';
    $res = mysqli_query($connect, $sel);
    if (mysqli_fetch_array($res, MYSQLI_NUM)) {
        echo '<h3><span style="color:red;">Пользователь с таким телефоном или (и) почтой существует!</h3>';
        mysqli_free_result($res);
        return false;
    }
    mysqli_free_result($res);

    $ins = "INSERT INTO users(login, pass, phone, email) VALUES('" . $login . "', '" . md5($pass1) . "', '" . $phone . "', '" . $email . "')";
    if (mysqli_query($connect, $ins)) return true;
    else echo '<h3><span style="color:red;">' . mysqli_error($connect) . '</h3>';

}

/* Авторизация */
function login($login, $pass)
{
    $connect = connect();
    $login = trim(htmlspecialchars($login));
    $pass = trim(htmlspecialchars($pass));

    if (is_numeric($login)) {
        $phone = $login;
        $sel = 'select * from users where phone="' . $phone . '" and pass="' . md5($pass) . '"';
    }
    else {
        $email = $login;
        $sel = 'select * from users where email="' . $email . '" and pass="' . md5($pass) . '"';
    }

    $res = mysqli_query($connect, $sel);
    if ($row = mysqli_fetch_array($res, MYSQLI_NUM)) {
        $_SESSION['rid'] = $row[0];
        $_SESSION['ruser'] = $row[1];
        if ($row[5] == 1) $_SESSION['radmin'] = $row[1];
        mysqli_free_result($res);
        return true;
    }
    mysqli_free_result($res);

    return false;

}

/* Изменение данных пользователя */
function updateUser($login, $phone, $email, $pass1, $pass2)
{
    $upd = '';
    $userid = $_SESSION['rid'];

    $login = trim(htmlspecialchars($login));
    $phone = trim(htmlspecialchars($phone));
    $email = trim(htmlspecialchars($email));
    $pass1 = trim(htmlspecialchars($pass1));
    $pass2 = trim(htmlspecialchars($pass2));

    if ($login == '' && $phone == '' && $email == '' && $pass1 == '' && $pass2 == '') {
        echo '<h3><span style="color:darkblue;">empty</h3>';
        return false;
    }

    $connect = connect();

    if (!empty($login)) {

        if (strlen($login) < 3 || strlen($login) > 30) {
            echo '<h3><span style="color:red;">Должно быть от 3 до 30 символов в имени!</h3>';
            return false;
        }

        $sel = 'select * from users where login="' . $login . '" and id!="' . $userid . '"';
        $res = mysqli_query($connect, $sel);
        if (mysqli_fetch_array($res, MYSQLI_NUM)) {
            echo '<h3><span style="color:red;">Пользователь с таким именем существует!</h3>';
            mysqli_free_result($res);
            return false;
        }
        mysqli_free_result($res);

        $upd = ' login="' . $login . '"';
    }

    if ($pass1 != '' && $pass2 != '') {

        if (strlen($pass1) < 3 || strlen($pass1) > 30) {
            echo '<h3><span style="color:red;">Должно быть от 3 до 30 символов в пароле!</h3>';
            return false;
        }
        if ($pass1 != $pass2) {
            echo '<h3><span style="color:red;">Пароль и подтверждение не совпадают</h3>';
            return false;
        }

        if ($upd != '') $upd = $upd . ', pass="' . md5($pass1) . '"';
        else $upd = ' pass="' . md5($pass1) . '"';
    }

    if ($phone != '') {

        $sel = 'select * from users where phone="' . $phone . '" and id!="' . $userid . '"';
        $res = mysqli_query($connect, $sel);
        if (mysqli_fetch_array($res, MYSQLI_NUM)) {
            echo '<h3><span style="color:red;">Пользователь с таким телефоном существует!</h3>';
            mysqli_free_result($res);
            return false;
        }
        mysqli_free_result($res);

        if ($upd != '') $upd = $upd . ', phone="' . $phone . '"';
        else $upd = ' phone="' . $phone . '"';
    }

    if ($email != '') {

        $sel = 'select * from users where email="' . $email . '" and id!="' . $userid . '"';
        $res = mysqli_query($connect, $sel);
        if (mysqli_fetch_array($res, MYSQLI_NUM)) {
            echo '<h3><span style="color:red;">Пользователь с такой почтой существует!</h3>';
            mysqli_free_result($res);
            return false;
        }
        mysqli_free_result($res);

        if ($upd != '') $upd = $upd . ', email="' . $email . '"';
        else $upd = ' email="' . $email . '"';
    }

    $upd = 'UPDATE users SET ' . $upd . ' WHERE id="' . $userid . '"';
    if (mysqli_query($connect, $upd)) return true;
    else echo '<h3><span style="color:red;">' . mysqli_error($connect) . '</h3>';

}