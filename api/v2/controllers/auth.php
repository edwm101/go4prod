<?php



$router->get('validation', function () {
    global $result;
    $result["info"] = Auth::authData();
    $result["refresh_token"] = Auth::createToken(Auth::authData());
});


$router->post(
    'signin',
    function () {
        global $db, $result, $_post;

        $email    = @$_post->email;
        $password = @$_post->password;
        $recaptchaToken = @$_post->recaptchaToken;

        // if (!Func::recaptcha($recaptchaToken))
        //     Func::error("RECAPTCHA_ERROR", 451, );


        if (!$data = $db->select(TOKEN_STATEMENT . ",password")->from("user")->Where("email=?", $email)->limit(1)->execute()->fetchObject()) {
            Func::error("INVALID_EMAIL", 401);
        }

        if (!password_verify($password, $data->password)) {
            Func::error("INVALID_PASSWORD", 401);
        }

        if ($restaurant_id = $db->select('r.id')
            ->from("restaurant r")
            ->join("JOIN user_restaurant ur ON ur.restaurant_id = r.id ")
            ->where("ur.user_id=?", $data->id)
            ->orderBy("r.id asc")
            ->limit(1)
            ->execute()->fetchColumn()
        ) {
            $data->{"restaurant_id"} = $restaurant_id;
        }


        unset($data->password);
        $result['info']  = $data;
        $result["token"] = Auth::createToken($data);
    }
);

$router->post('signup', function () {

    require 'services/mailer/autoload.php';

    global $db, $result, $_post;

    $first_name = $_post->first_name;
    $last_name = $_post->last_name;
    $email = $_post->email;
    $password = $_post->password;
    // $recaptchaToken = $_post->recaptchaToken;

    try {

        // if (!Func::recaptcha($recaptchaToken))
        //     Func::error("RECAPTCHA_ERROR", 451);

        if ($db->select("email")->from("user")->Where("email=?", $email)->limit(1)->execute()->fetchColumn())
            Func::error("EMAIL_EXISTS", 400);

        $date = new DateTime('NOW');
        $date = $date->format('Y-m-d H:i:s');
        $time = Func::time();



        $info = array(
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email" => $email,
            "created_at" => $date,
            "last_login" => $time,
            "password" => ShQuery::fHash($password),
            "role" => "user"
        );

        $db->insert("user", $info);

        $tokenInfo = array(
            "id" => $db->lastInsertId(),
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email" => $email,
            "role" => "user"
        );

        $result["token"] = Auth::createToken($tokenInfo);
        $result['info']  = $tokenInfo;

        /**** E-mail Class in github https://github.com/PHPMailer/PHPMailer ****/
        $mail->setFrom('med@bensassi.net', 'MedBn\'s');
        $mail->addAddress('bensassi.medamine@gmail.com', 'Med amine');
        $mail->addAddress($email, $first_name . " " . $last_name);
        $mail->Subject = 'Inscription au restoKing';
        $mail->msgHTML("<body> <h2>" . $first_name . " <br> Bienvenue chez nous :D</h2> </body>");
        //  $mail->addAttachment('../../static/images/couscous-2hmvJKPKOob.jpg');

        if (!$mail->send()) {
            $result["email_status"] = 'FAILED';
            $result["email_error"] = $mail->ErrorInfo;
        } else {
            $result["email_status"] = 'OK';
        }
        /**** E-mail ****/
    } catch (PDOException $e) {
        Func::error("PDO_EXCEPTION", 400, $e->getMessage());
    }
});

$router->put(
    'update',
    function () {
        global $_post, $db, $result;

        $first_name = $_post->first_name;
        $last_name  = $_post->last_name;

        $info = array(
            "first_name" => $first_name,
            "last_name" => $last_name,
        );

        $db->update("user", $info, "id = ?", USER_ID);

        $info = ShQuery::info(TOKEN_STATEMENT, "user", USER_ID);

        $result["info"] = $info;
        $result["token"] = (Auth::createToken($info));
    }
);
