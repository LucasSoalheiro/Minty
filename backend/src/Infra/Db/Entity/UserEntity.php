<?php

namespace Src\Infra\Db\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "Users")]
class UserEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    private string $id;

    #[ORM\Column(type: 'string' )]
    private string $name;

    #[ORM\Column(type: 'string')]
    private string $email;

    #[ORM\Column(type: 'string')]
    private string $password;

    public function getId(){
        return $this->id;
    }
    public function setId(string $id){
        $this->id = $id;
    }
    public function getName(){
        return $this->name;
    }
    public function setName(string $name){
        $this->name = $name;
    }
    public function getEmail(){
        return $this->email;
    }
    public function setEmail(string $email){
        $this->email = $email;
    }
    public function getPassword(){
        return $this->password;
    }
    public function setPassword(string $password){
        $this->password = $password;
    }
}