<?php

namespace App\Service;

use App\Entity\Club;
use App\Form\Model\ClubDto;
use Symfony\Component\Form\FormFactoryInterface;
use App\Service\ClubManager;
use Symfony\Component\HttpFoundation\Request;


class ClubFormProcessor 
{
    private $clubManager;
    private $formFactory;

    public function __contruct(
        ClubManager $clubManager,
        FormFactoryInterface $formFactory
    )
    {
      $this->clubManager = $clubManager;  
      $this->formFactory = $formFactory;  
    }
    public function __invoke(
        ClubDto $club_Dto,
        Request $request,
        $form,
        $clubManager
        ){
    
        
        
        $form->handleRequest($request); 
       
        if ($form->isSubmitted() && $form->isValid()) {
            
                
                return $club_Dto;

            }  else {
                return false;
            }

        

     

    }
   
} 