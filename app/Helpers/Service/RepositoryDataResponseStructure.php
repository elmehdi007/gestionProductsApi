<?php

namespace App\Helpers\Service;

class RepositoryDataResponseStructure{
    
    public function __construct(private string $message,private $data, private ?int $count, private ?int $totalRows) {}

   public function toArray(){
    $objRetourned=[];
    if($this->totalRows)$objRetourned[ "message"]=$this->message;
    if($this->data)$objRetourned["data"]=$this->data;
    if($this->count!==null)$objRetourned["count"]=$this->count;
    if($this->totalRows!==null)$objRetourned["totalRows"]=$this->totalRows;
    //return ["data"=>$this->data,"count"=>$this->count,"totalRows"=>$this->totalRows];
    return $objRetourned;
   }
 
   public function setMessage(string $message){
    $this->message=$message;
   }
}