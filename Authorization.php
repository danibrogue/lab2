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
                    echo '<form method="POST"  name="authorization" action="Authorization.php">';
                        echo '<p> Авторизация</p>';
                        echo '<p>Электронная почта</p>';
                        echo'<INPUT type="text" name="EmailBox" size="20px" maxlength="32">';
                        echo '<p>Пароль</p>';
                        echo'<INPUT type="text" name="PassBox" size="20px" maxlength="32">';
                        echo '<input type="submit" value="Авторизация" name="auth_but">';
                    echo '</form>';
                    if(isset($_POST['auth_but']))
                    {
                        if(BD::auth_User($_POST['EmailBox'],$_POST['PassBox']))
                        {
                            $_SESSION['email']=$_POST['EmailBox'];
                            $_SESSION['userId']=BD::get_Id($_SESSION['email']);
                            $_SESSION['name']=BD::get_Name($_SESSION['userId']);
                            unset($_POST['EmailBox']);
                            unset($_POST['PassBox']);
                            //Переход на домашнюю страничку
                            //header("Refresh:0");
                            echo '<script>document.location.href="HomePage.php"</script>';
                        }
                        else
                        {
                            echo '<p class="WarningText">Неверный email/пароль</p>';
                        }

                    }
                ?>
            </div>
        </div>
    </main>
</body>
</html>