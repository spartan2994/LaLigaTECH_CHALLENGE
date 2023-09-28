<?php

namespace App\Service;

use App\Form\Model\TrainerDto;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use App\Service\TrainerManager;

class MailerService 
{
    private $em;
    private $mailerInterface;
    private $trainerManager;

    public function __construct(EntityManagerInterface $em, MailerInterface $mailerInterface, TrainerManager $trainerManager)
    {
        $this->em = $em;
        $this->mailerInterface = $mailerInterface;
        $this->trainerManager = $trainerManager;

    }

    public function sendMail(TrainerDto $trainerDto){
        try {
            //Config params and data to send Email
                               $email = (new Email())
                                   ->from("llt@test.com")
                                   ->to($trainerDto->email)
                                   //->cc('cc@example.com')
                                   //->bcc('bcc@example.com')
                                   //->replyTo('fabien@example.com')
                                   //->priority(Email::PRIORITY_HIGH)
                                   ->subject(
                                       "Your Account Has Been Created - LaLiga TECH!"
                                   )
                                   ->html(
                                       '<h5 class="card-title">Welcome ' .
                                       $trainerDto->name .
                                           "!</h5>"
                                   );
                               $this->mailerInterface->send($email);
           
                               //Save object to DBç
                               $trainer = $this->trainerManager->create();
                               $trainer->setIdClub($trainerDto->id_club);
                               $trainer->setName($trainerDto->name);
                               $trainer->setSalary($trainerDto->salary);
                               $trainer->setEmail($trainerDto->email);
                               $this->trainerManager->save($trainer);
           
           
                               //Returnning json response with status
                               return true;
                           } catch (\Exception $e) {
                               //Returnnig exception with json response message
                               return $e;
                           }
    }
       

}
