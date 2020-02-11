<?php
function login($username, $password, $last_login){
    $pdo = Database::getInstance()->getConnection();
    //check existance
    $check_exist_query = 'SELECT COUNT(*) FROM tbl_user WHERE user_name= :username';
    //prevent a sql injection
    $user_set = $pdo->prepare($check_exist_query);
    $user_set->execute(
        array(
            ':username' => $username,
        )
    );

    if($user_set->fetchColumn()>0){
        //user exists
        $get_user_query = 'SELECT * FROM tbl_user WHERE user_name = :username';
        $get_user_query .= ' AND user_pass = :password';
        $user_check = $pdo->prepare($get_user_query);
        $user_check->execute(
            array(
                ':username'=>$username,
                ':password'=>$password
            )
        );

        while($found_user = $user_check->fetch(PDO::FETCH_ASSOC)){
            $id = $found_user['user_id'];
            //Logged in!
            $message = 'Welcome!';
            

            //TODO: finish the following lines so that when user logged in
            //The user_ip column get updated by the $ip
            $update_query = "UPDATE `tbl_user` SET `last_activity` = $last_login WHERE user_id = :id";
            $update_set = $pdo->prepare($update_query);
            $update_set->execute(
                array(
                    ':id'=>$id,
                    ':last_login'=>$last_login
                )
            );
        }

        // while($found_user = $user_check->fetch(PDO::FETCH_ASSOC)){
        //     $id = $found_user['user_id'];
        //     //Logged in!
        //     $message = 'Welcome!';

        //     //TODO: finish the following lines so that when user logged in
        //     //The user_ip column get updated by the $ip
        //     $update_query = "UPDATE `tbl_user` SET `last_activity` = $last_login WHERE user_id = :id";
        //     $update_set = $pdo->prepare($update_query);
        //     $update_set->execute(
        //         array(
        //             ':id'=>$id,
        //             ':last_login'=>$last_login
        //         )
        //     );
        // }

        if(isset($id)){
            redirect_to('index.php');
        }

    }else{
        //User doesnt exists
        $message = 'User doesnt exist';
    }

    //log user in
    return $message;
}