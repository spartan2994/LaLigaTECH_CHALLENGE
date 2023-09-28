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

    public function __construct(
        EntityManagerInterface $em,
        MailerInterface $mailerInterface,
        TrainerManager $trainerManager
    ) {
        $this->em = $em;
        $this->mailerInterface = $mailerInterface;
        $this->trainerManager = $trainerManager;
    }

    public function sendMail(TrainerDto $trainerDto)
    {
        try {
            $email = (new Email())
                ->from("llt@test.com")
                ->to($trainerDto->email)
                ->subject("Your Account Has Been Created - LaLiga TECH!")
                ->html(
                    '<h5 class="card-title">Welcome ' .
                        $trainerDto->name .
                        "!</h5>"
                );
            $this->mailerInterface->send($email);
            $trainer = $this->trainerManager->create();
            $trainer->setIdClub($trainerDto->id_club);
            $trainer->setName($trainerDto->name);
            $trainer->setSalary($trainerDto->salary);
            $trainer->setEmail($trainerDto->email);
            $this->trainerManager->save($trainer);

            return true;
        } catch (\Exception $e) {
            return $e;
        }
    }
}
