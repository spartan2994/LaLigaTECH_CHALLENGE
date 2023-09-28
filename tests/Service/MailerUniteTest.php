<?php

namespace App\Service;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Form\Model\TrainerDto;




class MailerServiceUniteTest extends KernelTestCase
{
  
   
    private MockObject|MailerInterface $mailerInterface;
    private MailerService $service;
    private TrainerDto $trainerDto;

    public function setUp():void
    {
        $this->mailerInterface = $this->getMockBuilder(MailerInterface::class)->disableOriginalConstructor()->getMock();
        $this->service = new MailerService($trainerDto);
    }

    public function UnitTestMailerService(){

        $response = $this->service
                            ->method("sendEmail")
                            ->with($this->isInstanceOf(TrainerDto::class))
                            ->willReturn();
    }
    


   
} 