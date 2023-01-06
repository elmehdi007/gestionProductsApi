<?php

namespace App\Helpers\Service;

class RepositoryMajResponseStructure{
    
    public function __construct(private string $message) {}

   public function toArray(){
    return ['message'=>$this->message];
   }
}