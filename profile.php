<?php
include_once('functions.php');

if (!isset($_SESSION['ruser'])) exit();

echo '<h3>Ваш профиль</h3>';

if (!isset($_POST['updatebtn'])) {

    $userid = $_SESSION['rid'];
    $connect = connect();
    $sel = 'SELECT * FROM users WHERE id=' . $userid;
    $res = mysqli_query($connect, $sel);

    while ($row = mysqli_fetch_array($res, MYSQLI_NUM)) {
        $login = $row[1];
        $phone = $row[3];
        $email = $row[4];
    }
    mysqli_free_result($res);
    ?>

    <form action="index.php?page=2" method="post">

        <div class="form-group">
            <label for="login">Имя</label>
            <input name="login" type="text" class="form-control" placeholder="<?php echo $login ?>">
        </div>

        <div class="form-group">
            <label for="phone">Телефон</label>
            <input name="phone" type="tel" class="form-control" placeholder="<?php echo $phone ?>">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input name="email" type="email" class="form-control" placeholder="<?php echo $email ?>">
        </div>

        <div class="form-group">
            <label for="pass1">Пароль</label>
            <input name="pass1" type="password" class="form-control" placeholder="****">
        </div>

        <div class="form-group">
            <label for="pass2">Повторите пароль</label>
            <input name="pass2" type="password" class="form-control" placeholder="****">
        </div>
        <button name="updatebtn" type="submit" class="btn btn-primary" >Изменить</button>

    </form>
    <hr>

    <?php
} else {
    echo '<h3><span style="color:blue;">Кнопка изменить нажата!</h3>';
    if (updateUser($_POST['login'], $_POST['phone'], $_POST['email'], $_POST['pass1'], $_POST['pass2'])) {
        echo '<h3><span style="color:green;">Данные изменены!</h3>';
    }
    else echo '<h3><span style="color:blue;">Данные не изменены!</h3>';
}

