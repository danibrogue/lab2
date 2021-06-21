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
                        echo '<p class="header_text"> Привет,'. $_SESSION['name'].'</p>';
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
        <div>
            <nav>
                <ul class="Send_list">
                    <form enctype="multipart/form-data" method="post" action="SendPhoto.php">
                        <p><b class="Thumbnail">Загрузка фото</b></p>
                        <p>Описание</p><!-- описание и другое инфо об фото-->
                        <p><textarea name="description" maxlength="150"> </textarea></p>
                        <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
                        <p><input type="file" name="photoFile"  multiple="false" accept="image/jpeg"></p>
                        <p><input type="submit" value="Отправить" name="SendInfo"></p>
                    </form>
                </ul>
            </nav>
            <?php
            if(isset($_POST['SendInfo']))
            {
                $tmp= new SplFileInfo($_FILES["photoFile"]["name"]);
                if($_FILES['photoFile']['size']<=3000000 && $tmp->getExtension()=='jpg')
                {
                    if(trim(strlen($_POST['description']))<=150 && trim(strlen($_POST['description']))>1)
                    {
                        $uploads_dir='S:\OpenServer\domains\lab2W\images\svg';

                            $tmp_name = $_FILES["photoFile"]["tmp_name"];
                            $name = basename($_FILES["photoFile"]["name"]);
                            move_uploaded_file($tmp_name, "$uploads_dir/$name");
                            BD::Add_photo($_SESSION['email'],$_POST['description'],$_FILES['photoFile']['name']);
                            echo '<p class="GreenText"> Добавлено! </p>';
                    }
                    else
                    {
                        echo '<p class="WarningText">Описание фото не должно быть пустым и превышать 150 символов</p>';
                    }
                }
                else
                {
                    echo '<p class="WarningText"> Размер файла не должен превышать 3мб и иметь расширение ".jpg"</p>';
                }
            }
            ?>
        </div>
    </div>
</main>
</body>
</html>