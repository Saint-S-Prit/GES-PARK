<?php

namespace App\Service;


class SendMessage
{



    public function sendMessageActivatorAccount(\Swift_Mailer $mailer, $user)
    {


        $url = 'test';
        $message = (new \Swift_Message('Ouverture de votre Compte sur SEN INTERIM'))
            ->setFrom('saintespritt@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(

                "
                img
                <hr><h4>Salut " . $user->getFirstname() . ' ' . $user->getLastname() . "!</h4> <br>
                <p>
                Votre compte a été créé  pour l'entité SEN INTERIM. <br>
                Vous êtes demander de bien vouloir modifier votre mot de passe.
                <hr>
                <h3>Vos identifiants</h3>
                <b>Pseudo</b> :" . $user->getEmail() . "<br>
                <b>Mot de passe</b> :SEN-INTERIM
                <hr>
                Pour vous connectez, veuillez cliquer ici:  <a href='.$url.'>ici</a></p>",
                'text/html'
            );

        $mailer->send($message);
    }
}
