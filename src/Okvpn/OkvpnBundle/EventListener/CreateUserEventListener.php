<?php

namespace Okvpn\OkvpnBundle\EventListener;

use Okvpn\KohanaProxy\Kohana;
use Okvpn\KohanaProxy\Text;
use Okvpn\KohanaProxy\URL;
use Okvpn\KohanaProxy\View;
use Okvpn\OkvpnBundle\Entity\Users;
use Okvpn\OkvpnBundle\Event\CreateUserEvent;
use Okvpn\OkvpnBundle\Filter\UserFilter;
use Okvpn\OkvpnBundle\Tools\MailerInterface;

class CreateUserEventListener
{
    /** @var  MailerInterface */
    protected $mailer;
    
    /** @var  UserFilter */
    protected $userFilter;

    public function __construct(MailerInterface $mailer, UserFilter $userFilter)
    {
        $this->mailer = $mailer;
        $this->userFilter = $userFilter;
    }
    
    public function preCreateUser(CreateUserEvent $event)
    {
        $data = $event->getData();
        $this->userFilter->validateUserRegistrationForm($data);
    }

    public function postCreateUser(CreateUserEvent $event)
    {
        $user = $event->getUser();
        $this->updateUserFields($user);
        $this->sendEmail($user);
    }
    
    private function updateUserFields(Users $user)
    {
        $user->setDate()
            ->setLastLogin()
            ->setChecked(false)
            ->setToken(Text::random('alnum', 16));
    }
    
    private function sendEmail(Users $user)
    {
        $body = View::factory('mail/mailVerify')
            ->set('src', sprintf('%suser/verify/%s', URL::base(true), $user->getToken()));
        
        /** @var \Swift_Message $message */
        $message = \Swift_Message::newInstance();
        $message->setBody($body, 'text/html');
        $message->setTo($user->getEmail());
        $message->setSubject(Kohana::message('user', 'mailVerify'));
        $this->mailer->send($message);
    }
}
