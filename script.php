<?php
$connect = "outlook.office365.com:993/imap/ssl/novalidate-cert";

$box = readline("Entre com qual caixa de email quer deletar: ");

$user = readline("Entre com o email: ");

$pass = readline("Entre com o password");

$days = readline("Quantos dias quer deletar: ");



$mbox = imap_open( "{".$connect ."}". $box, $user, $pass, OP_DEBUG)
or die("Can't connect: " . imap_last_error());


    if($mbox != false){

        $numMsg = imap_num_msg($mbox);
            
        echo 'BOX: '. $box. " Mensagens antes de deletar: ", $numMsg . chr(10);

        $index = $numMsg;

        $emailsToDelete = [];

        for($i = $index; $i > 0; $i--){
                $uid = imap_uid($mbox,$i);

                $header = imap_headerinfo($mbox,$i);
                $dateNow = new \DateTime();
                $date = new \DateTime($header->date);
                $interval = $dateNow->diff($date); 
                $diffInDays = $interval->format('%a');

                if(intval($diffInDays)>=$days){
                    array_push($emailsToDelete, $uid);
                }

        }

        for($i=0;$i< sizeof($emailsToDelete); $i++ ){
                imap_delete($mbox, $emailsToDelete[$i],CP_UID);
        }   

        $res = imap_expunge($mbox);
        $check = imap_mailboxmsginfo($mbox);
        echo ' BOX: '. $box. " Mensagens restantes apos deletar: " . $check->Nmsgs . chr(10);

        imap_close($mbox);

    }
?>