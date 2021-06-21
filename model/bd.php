<?php
$connection = new PDO('pgsql:host=localhost;port=5435;user=postgres;password=290677;dbname=lab2');
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
class BD
{
    public function test(){
        return null;
    }

    static function get_last_photo()
    {
        global $connection;
        $prepared = $connection->prepare('select photo.path,photo.description,photo.quant_rating,photo.rating,
        photo.last_update,users.name,photo.id_photo, photo.user_id
        from photo,users where photo.user_id=users.id
        order by last_update desc limit 20');//вывести последние 20 строк
        $result = $prepared->execute();

        if ($result)
        {
            $data = $prepared->fetchAll();
        }
        return $data;
    }

    static function get_user_photo($id)
    {
        global $connection;
        $prepared = $connection->prepare('select photo.path,photo.description,photo.quant_rating,photo.rating,
        photo.last_update,users.name,photo.id_photo, photo.user_id
        from photo,users where photo.user_id=users.id and users.id='.$id);
        $result = $prepared->execute();

        if ($result)
        {
            $data = $prepared->fetchAll();
        }
        return $data;
    }

    static function get_Name($id)
    {
        global $connection;
        $prepared = $connection->prepare('select name from users where id='.$id);
        $result = $prepared->execute();
        if ($result)
        {
            $data = $prepared->fetchColumn();
        }
        return $data;
    }

    static function get_Id($email)
    {
        global $connection;
        $prepared = $connection->prepare('select id from users where email=\''.$email.'\'');
        $result = $prepared->execute();
        if ($result)
        {
            $data = $prepared->fetchColumn();
        }
        return $data;
    }

    static function auth_User($email,$password)
    {
        global $connection;
        //$login=$_POST['EmailBox'];
        //$_POST['PassBox']=hash (  'sha256', $_POST['PassBox'], false);
        $password=hash (  'sha256', $password, false);
        $prepared = $connection->prepare('select count(email) from users 
            where email=\''.$email.'\' and pass=\''.$password.'\'');
        $result = $prepared->execute();
        if ($result)
        {
            $data = $prepared->fetchColumn();
        }
        return $data;
    }

    static function reg_User($name,$email,$password)
    {
        global $connection;
        $prepared = $connection->prepare('select count(email) from users where email=\''.$email.'\'');
        $result = $prepared->execute();
        if ($result)
        {
            $data = $prepared->fetchColumn();
        }
        if($data==1)
        {
            $data=0;
            return $email;
        }
        else
        {
            $password=hash (  'sha256', $password, false);
            $prepared = $connection->prepare('insert into users (name,email,pass) values (\''.$name.'\',\''.$email.'\',\''.$password.'\')');
            $result = $prepared->execute();
            if ($result)
            {
                $data=1;
                return $data;
            }
        }
    }

    static function get_Count_photo($id)
    {
        global $connection;
        $prepared = $connection->prepare('select count(photo.id_photo) from photo,users 
            where photo.user_id=users.id and users.id='.$id);
        $result = $prepared->execute();
        if ($result)
        {
            $data = $prepared->fetchColumn();
        }
        return $data;
    }

    static function Add_photo($email,$description,$path)
    {
        global $connection;
        try
        {
            $prepared = $connection->prepare('insert  into photo (user_id, path,description, quant_rating, rating, last_update)
            values
            (
                (select id from users where email=\'' . $email . '\'),
                \'images/svg/' . $path . '\',        
                \'' . $description . '\',
                0,
                0,
                current_date
            )');
            $prepared->execute();
        }

        catch (PDOException $e)
        {
            echo $e;
        }
    }


    static function Set_rating($email, $id_photo, $rating)
    {
        if($email==null)
        {
            return;
        }
        global $connection;
        if ($rating == '')
        {
            return;
        }
        $prepared = $connection->prepare('select count(rating_photo.user_id) from rating_photo,users
        where users.id=rating_photo.user_id and users.email=\'' . $email . '\' and
        rating_photo.photo_id=\'' . $id_photo . '\'');
        $result = $prepared->execute();
        if ($result)
        {
            $data = $prepared->fetchColumn();
        }
        if ($data == 0)
        {
            try
            {
                $prepared = $connection->prepare('insert  into rating_photo (user_id,photo_id,rating)
                    values
                    (
                        (select id from users where email=\'' . $email . '\'),
                        \'' . $id_photo . '\',        
                        \'' . $rating . '\'
                    )');
                $prepared->execute();
                BD::Set_Info_rating($id_photo);
            }
            catch (PDOException $e)
            {
                echo $e;
            }
        }
        else
        {
            try
            {
                $prepared = $connection->prepare('update rating_photo set rating=\''.$rating.'\'
                    where user_id=(select id from users where email=\''.$email.'\') and 
                    photo_id=\''.$id_photo.'\'');
                $prepared->execute();
                BD::Update_Info_rating($id_photo);
            }

            catch (PDOException $e)
            {
                echo $e;
            }
        }
    }

    static function Set_Info_rating($id_photo)
    {
        global $connection;
        try
        {
            $prepared = $connection->prepare('update photo set quant_rating=quant_rating+1 where id_photo=\''.$id_photo.'\'');
            $prepared->execute();
            $prepared = $connection->prepare('update photo set rating=(select round(CAST(float8 (sum(rating)::real/count(rating)::real) 
                as numeric),2) from rating_photo where photo_id=\''.$id_photo.'\') where id_photo=\''.$id_photo.'\'');
            $prepared->execute();
        }
        catch (PDOException $e)
        {
            echo $e;
        }
    }

    static function Update_Info_rating($id_photo)
    {
        global $connection;
        try
        {
            $prepared = $connection->prepare('update photo set rating=(select round(CAST(float8 (sum(rating)::real/count(rating)::real) 
                as numeric),2) from rating_photo where photo_id=\''.$id_photo.'\') where id_photo=\''.$id_photo.'\'');
            $prepared->execute();
        }
        catch (PDOException $e)
        {
            echo $e;
        }
    }
}