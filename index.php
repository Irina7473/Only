<?php
session_start();
include_once('pages/functions.php');

$page = false;
if (isset($_GET['page']))  $page = $_GET['page'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль пользователя</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="container">
        <div class="row">

            <!-- Вход-выход -->
            <header class="col-sm-12 col-md-12 col-lg-12">
                <?php include_once("pages/login.php");?>
            </header>

            <!-- Верхнее меню -->
            <nav class="col-12">
                <?php include_once('pages/menu.php'); ?>
            </nav>

            <!-- Контент страницы -->
            <section class="col-12">
                <?php
                if ($page) {
                    if ($page == 1) include_once('pages/site.php');
                    if ($page == 2) if(isset($_SESSION['ruser'])) include_once('pages/profile.php');
                    if ($page == 3) include_once('pages/registration.php');
                    if ($page == 4) if(isset($_SESSION['radmin'])) include_once('##');
                }
                ?>
            </section>

            <footer>@Irina Sirenko</footer>
        </div>
    </div>

</body>

</html>