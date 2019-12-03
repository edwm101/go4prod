<?php



App::get('validation', function () {
    $token =  Auth::authData();
    App::$response["info"] = $token;
    App::$response["refresh_token"] = Auth::createToken($token);
});


App::post('signin', function () {

    $email    = @App::$request["email"];
    $password = @App::$request["password"];
    // $recaptchaToken = @App::$request["recaptchaToken"];

    // if (!Func::recaptcha($recaptchaToken))
    //     Func::error("RECAPTCHA_ERROR", 451);


    if (!$data = App::db()->select(TOKEN_STATEMENT . ",password")->from("user")->Where("email=?", $email)->limit(1)->execute()->fetchObject()) {
        Func::error("INVALID_EMAIL", 401);
    }

    if (!password_verify($password, $data->password)) {
        Func::error("INVALID_PASSWORD", 401);
    }


    unset($data->password);
    App::$response['info']  = $data;
    App::$response["token"] = Auth::createToken($data);
});

App::post('signup', function () {

    require 'services/mailer/autoload.php';


    $first_name = App::$request["first_name"];
    $last_name = App::$request["last_name"];
    $email = App::$request["email"];
    $password = App::$request["password"];
    // $recaptchaToken = App::$request["recaptchaToken"];

    try {

        // if (!Func::recaptcha($recaptchaToken))
        //     Func::error("RECAPTCHA_ERROR", 451);

        if (App::db()->select("email")->from("user")->Where("email=?", $email)->limit(1)->execute()->fetchColumn())
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

        App::db()->insert("user", $info);

        $tokenInfo = array(
            "id" => App::db()->lastInsertId(),
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email" => $email,
            "role" => "user"
        );

        App::$response["token"] = Auth::createToken($tokenInfo);
        App::$response['info']  = $tokenInfo;

        /**** E-mail Class in github https://github.com/PHPMailer/PHPMailer ****/
        $mail->setFrom('med@bensassi.net', 'MedBn\'s');
        $mail->addAddress('bensassi.medamine@gmail.com', 'Med amine');
        $mail->addAddress($email, $first_name . " " . $last_name);
        $mail->Subject = 'Inscription au restoKing';
        $mail->msgHTML("<body> <h2>" . $first_name . " <br> Bienvenue chez nous :D</h2> </body>");
        //  $mail->addAttachment('../../static/images/couscous-2hmvJKPKOob.jpg');

        // if (!$mail->send()) {
        //     App::$response["email_status"] = 'FAILED';
        //     App::$response["email_error"] = $mail->ErrorInfo;
        // } else {
        //     App::$response["email_status"] = 'OK';
        // }
        /**** E-mail ****/
    } catch (PDOException $e) {
        Func::error("PDO_EXCEPTION", 400, $e->getMessage());
    }
});

App::put('update', function () {

    $first_name = @App::$request["first_name"];
    $last_name  = @App::$request["last_name"];

    Func::emptyCheck([$first_name, $last_name]);

    $info = array(
        "first_name" => $first_name,
        "last_name" => $last_name,
    );

    App::db()->update("user", $info, "id = ?", TOKEN_ID);

    $info = ShQuery::info(TOKEN_STATEMENT, "user", TOKEN_ID);

    App::$response["info"] = $info;
    App::$response["token"] = (Auth::createToken($info));
});
