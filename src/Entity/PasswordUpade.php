<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


class PasswordUpade
{
   
    private $accienMotDePasse;

    /**
     * @Assert\Length(min = 8, minMessage = "Votre mot de passe doit depassez huit caractere")
     */
    private $nouveauMotDePasse;

   /**
    * @Assert\EqualTo(propertyPath = "nouveauMotDePasse", message= "les deux mot de passe sont differenet")
    */
    private $confirmNouveauMotDePasse;

   

    public function getAccienMotDePasse(): ?string
    {
        return $this->accienMotDePasse;
    }

    public function setAccienMotDePasse(string $accienMotDePasse): self
    {
        $this->accienMotDePasse = $accienMotDePasse;

        return $this;
    }

    public function getNouveauMotDePasse(): ?string
    {
        return $this->nouveauMotDePasse;
    }

    public function setNouveauMotDePasse(string $nouveauMotDePasse): self
    {
        $this->nouveauMotDePasse = $nouveauMotDePasse;

        return $this;
    }

    public function getConfirmNouveauMotDePasse(): ?string
    {
        return $this->confirmNouveauMotDePasse;
    }

    public function setConfirmNouveauMotDePasse(string $confirmNouveauMotDePasse): self
    {
        $this->confirmNouveauMotDePasse = $confirmNouveauMotDePasse;

        return $this;
    }
}
