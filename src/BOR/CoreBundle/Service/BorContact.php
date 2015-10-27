<?php

namespace Bor\CoreBundle\Service;

/**
 * Rédaction et envois des emails de contact
 */
class BorContact
{
    protected $mailer;

    /**
     * Constructeur, récupère le service $mailer
     * @param Mailer $mailer
     */
    public function __construct($mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Fonction pour envoyer un mail
     * @param Contact $user
     */
    public function sendMailAfterRegister($user)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject("Blabla Academy - Inscription confirmée")
            ->setFrom('noreply@blabla.academy')
            ->setTo($user->getEmail())
            ->setBody('Bienvenue sur Blabla Academy, <br><br> Merci pour votre inscription. <br><br> A bientot sur notre <a href="http://blabla.academy">site</a> <br><br> L\'équipe Blabla Academy', 'text/html');
        $this->mailer->send($message);
    }

    /**
     * Fonction pour envoyer un mail
     * @param Contact $user $sponsor
     */
    public function sendMailAfterSponsoring($user, $sponsor)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject("Blabla Academy - Parrainage")
            ->setFrom('noreply@blabla.academy')
            ->setTo($sponsor->getEmail())
            ->setBody('Merci d\'avoir parrainé '.$user->getFirstname().' '.$user->getLastname().'.<br><br> A bientot sur notre <a href="http://blabla.academy">site</a> <br><br> L\'équipe Blabla Academy', 'text/html');
        $this->mailer->send($message);
    }

    /**
     * Fonction pour envoyer un mail
     * @param Contact $entity
     */
    public function sendMailAfterContact($entity)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject("[Contact] ".$entity->getSubject())
            ->setFrom('noreply@blabla.academy')
            ->setTo('blabla.academy@gmail.com')
            ->setBody('Email : '.$entity->getEmail().'<br><br>Télephone : '.$entity->getPhone().'<br><br>Message :<br>'.$entity->getMessage(), 'text/html');
        $this->mailer->send($message);
    }
}
