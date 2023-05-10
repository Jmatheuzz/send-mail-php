<?php
    require "./bibliotecas/php-mailer/Exception.php";
    require "./bibliotecas/php-mailer/OAuthTokenProvider.php";
    require "./bibliotecas/php-mailer/OAuth.php";
    require "./bibliotecas/php-mailer/PHPMailer.php";
    require "./bibliotecas/php-mailer/POP3.php";
    require "./bibliotecas/php-mailer/SMTP.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    class Message {
        private $destiny = '';
        private $subject = '';
        private $message = '';
        public $status = null;
        public function __set($atrr, $value){
            $this->$atrr = $value;
        }
        public function __get($atrr){
            return $this->$atrr;
        }
        public function validate(){
            if (empty($this->message) || empty($this->destiny) || empty($this->subject)){
                return false;
            }
            return true;
        }
        public function sendMail(){
            if (!$this->validate()) {
                $this->status = 3;
                return;
            }
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'example@gmail.com';                     //SMTP username
                $mail->Password   = 'senha';                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
                $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
                //Recipients
                $mail->setFrom('joaomatheusantunes.dev@gmail.com', 'Joao Matheus');
                $mail->addAddress($this->destiny, 'JM');     //Add a recipient
                //$mail->addReplyTo('info@example.com', 'Information');
                //$mail->addCC('cc@example.com');
                //$mail->addBCC('bcc@example.com');
        
                //Attachments
                //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
                //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
        
                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = $this->subject;
                $mail->Body    = "<b>$this->message</b>";
                $mail->AltBody = $this->message;
        
                $mail->send();
                $this->status = 1;
            } catch (Exception $e) {
                $this->status = 2;
            }
        }
    }

    $message = new Message();
    $message->__set('destiny', $_POST['destiny']);
    $message->__set('subject', $_POST['subject']);
    $message->__set('message', $_POST['message']);
    $message->sendMail();
    


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>App Mail Send</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>
<body>
    <div class="container">
        <div class="py-3 text-center">
            <img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
            <h2>Send Mail</h2>
            <p class="lead">Seu app de envio de e-mails particular!</p>
        </div>
    </div>
    <div class="row-lg">
        <div class="col-md-12">
            <?php if ($message->status === 1){ ?>
                <div class="container">
                    <h1 class="display-4 text-success">
                        Sucesso
                    </h1>
                    <p>
                        Seu email foi enviado com sucesso!!
                    </p>
                    <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                </div>
            <?php } ?>

            <?php if ($message->status === 2){ ?>
                <div class="container">
                    <h1 class="display-4 text-danger">
                        Ops...
                    </h1>
                    <p>
                        Tivemos um problema e não foi possível enviar seu email, tente novamente mais tarde.
                    </p>
                    <a href="index.php" class="btn btn-danger btn-lg mt-5 text-white">Voltar</a>
                </div>
            <?php } ?>

            <?php if ($message->status === 3){ ?>
                <div class="container">
                    <h1 class="display-4 text-warning">
                        Calma lá...
                    </h1>
                    <p>
                        Seus dados estão incompletos, por favor, preencher tudo para poder enviar seu email.
                    </p>
                    <a href="index.php?destiny=<?= $message->destiny ?>&subject=<?= $message->subject ?>&message=<?= $message->message ?>" class="btn btn-warning btn-lg mt-5 text-white">Corrigir</a>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>
