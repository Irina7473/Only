<ul class="nav nav-tabs nav-justified">

    <li <?php echo ($page == 1) ? "class='active'" : "" ?>>
        <a href="index.php?page=1">Главная</a>
    </li>

    <?php
    if(isset($_SESSION['ruser']))
    {
        if($page==2) $c='active';
        else $c='';
        echo '<li class="'.$c.'"><a href="index.php?page=2">Личный кабинет</a></li>';
    }

    if(!isset($_SESSION['ruser']))
    {
        if($page==3) $c='active';
        else $c='';
        echo '<li class="'.$c.'"><a href="index.php?page=3">Регистрация</a></li>';
    }

    ?>
</ul>