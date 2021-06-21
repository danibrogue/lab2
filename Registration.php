<?php
if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}
if($_SESSION['email']!=null)
{
    $new_url = 'https://lab2W/HomePage.php';
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
                <a href="HeadNoAuthPublic.php" class="header_logo-link">
                    <img src="../../images/svg/Lab2-logo.svg" alt="Lab2" class="header_logo-pic">
                </a>
            </div>
            <nav class="header_nav">
                <ul class="header_list">
                    <li class="header_item">
                        <a href="Registration.php" class="header_link">Регистрация</a>
                    </li>
                    <li class="header_item">
                        <a href="Authorization.php" class="header_link">Авторизация</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>
<main class="main">
    <div class="wrapper">
        <div class="AuthBlock">
            <?php
            echo '<form method="POST"  name="registration" action="Registration.php">';
            echo '<p> Регистрация</p>';
            echo '<p>Имя</p>';
            echo'<INPUT type="text" name="NameBox" size="20px" maxlength="32">';
            echo '<p>Электронная почта</p>';
            echo'<INPUT type="text" name="EmailBox" size="20px" maxlength="32">';
            echo '<p>Пароль</p>';
            echo'<INPUT type="text" name="PassBox" size="20px" maxlength="32">';
            echo '<p>Подтверждение пароля</p>';
            echo'<INPUT type="text" name="PassCheckBox" size="20px" maxlength="32">';
            echo '<input type="submit" value="Регистрация" name="reg_but">';
            echo '</form>';
            $mp=0;
            if(isset($_POST['reg_but']))
            {
                if ($_POST['PassBox'] != $_POST['PassCheckBox'])
                {
                    echo '<p class="WarningText">Пароли не совпадают!</p>';
                }
                else
                {
                    $tmp = BD::reg_User($_POST['NameBox'],$_POST['EmailBox'],$_POST['PassBox']);
                    if ($tmp == 1)
                    {
                        //echo '<p class="GreenText">Аккаунт зарегистрирован!</p>';
                        $_SESSION['email']=$_POST['EmailBox'];
                        $_SESSION['userId']=BD::get_Id($_SESSION['email']);
                        $_SESSION['name']=$_POST['NameBox'];
                        unset($_POST['EmailBox']);
                        unset($_POST['PassBox']);
                        unset($_POST['PassCheckBox']);
                        unset($_POST['NameBox']);
                        //Переход на HomePage
                        echo '<script>document.location.href="HomePage.php"</script>';
                    }
                    else
                    {
                        echo '<p class="WarningText">email: ' . $tmp . '- уже зарегистрирован!</p>';
                    }

                }
            }
            ?>
        </div>
    </div>
</main>
</body>
</html>
