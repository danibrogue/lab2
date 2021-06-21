<?php
if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}
if($_SESSION['email']==null)
{
    $new_url = 'https://lab2W/HeadNoAuthPublic.php';
    header('Location: ' . $new_url);
    exit();
}
include('model/bd.php');
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab2</title>
    <link rel="stylesheet" type="text/css" href="css/reset.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="js/lightbox.css">
</head>
<body>
<header class="header">
    <div class="wrapper">
        <div class="header_wrapper">
            <div class="header_logo">
                <a href="HeadAuthPublic.php" class="header_logo-link">
                    <img src="../../images/svg/Lab2-logo.svg" alt="Lab2" class="header_logo-pic">
                </a>
            </div>
            <nav class="header_nav">
                <ul class="header_list">
                    <li class="header_item">
                        <?php
                        echo '<p class="header_text"> Привет, '. $_SESSION['name'].'</p>';
                        ?>
                    </li>
                    <li class="header_item">
                        <a href="logout.php" class="header_link">Выход</a><!-- ссылка на скрипт выхода и очищения данных в куки для входа-->
                    </li>
                    <li class="header_item">
                        <a href="HomePage.php" class="header_link">Профиль</a><!--ссылка на страничку профиля-->
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>
<main class="main">
    <div class="wrapper">
        <div class="imageBlock">
            <?php
            $inst = new BD();
            $num1=$inst->test();
            echo $num1;
            $tmp=BD::get_last_photo();
            echo '<ul class="photo_list">';
            foreach ($tmp as $value)
            {
                echo '<li class="photo_item">';
                    echo'<div class="photo_info">';
                        echo '<a href="'.$value[0].'" data-lightbox="image-1" data-title="'.$value[1].'">
                            <img src="'.$value[0].'" alt="" width="250px" height="230"></a>';
                        echo '<div class="infoPhoto">';
                        echo '<p> Автор: ';
                        echo '<a href="AuthUserPage.php?id='.$value[7].'" class="аuthor_link">'.$value[5].'</a></p>';
                        echo '</div>';
                        echo'<p class="infoPhoto">Описание</p>';
                        echo'<textarea class="infoPhoto" disabled>'.$value[1].'</textarea>';
                        echo'<p class="infoPhoto">Кол-во оценок: '.$value[2].'</p>';
                        echo'<p class="infoPhoto">Оценка: '.$value[3].'</p>';
                        echo'<form method="post" action="HeadAuthPublic.php">
                            <select size="1" name="rating'.$value[6].'">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option selected="" disabled="">Поставить оценку</option>
                            </select>
                            <p><input type="submit" value="отправить" name="send_rating'.$value[6].'"></p>
                        </form>';
                    echo '</div>';
                echo '</li>';
            }
            echo '</ul>';
            echo '<script src="js/lightbox-plus-jquery.js" type="text/javascript"></script>';
            foreach ($tmp as $value)
            {
                if (isset($_POST['send_rating' . $value[6] . '']))
                {
                    if($value[7]!=$_SESSION['userId'])
                    {
                        BD::Set_rating($_SESSION['email'], $value[6], $_POST['rating' . $value[6] . '']);
                    }
                }
            }
            ?>
        </div>
    </div>
</main>
</body>
</html>
