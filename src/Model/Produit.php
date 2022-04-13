<?php

namespace App\Model;

use App\Manager\ProduitManager;
use DateTime;
use JsonSerializable;

class Produit implements JsonSerializable {
    use \App\Util\Hydrator;

    private $id;
    private $nom = '';
    private $dateIn;
    private $dateUp;
    private $description = '';
    private $prix = .0;

    public function __construct(array $datas = array()) {
        $this->hydrate($datas);
    }

/* -------------------------- Accessors & Mutators -------------------------- */
    public function getId(): int {
        return $this->id;
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function getDateIn(): DateTime {
        return $this->dateIn;
    }

    public function getDateUp(): DateTime {
        return $this->dateUp;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getPrix(): float {
        return $this->prix;
    }

    protected function setId(int $id): void {
        if($id > 0) {
            $this->id = $id;
        }
    }

    public function setNom(string $nom): void {
        if(!empty($nom)) {
            $this->nom = htmlspecialchars($nom);
        }
    }

    public function setDateIn(DateTime $DateIn): void {
        $this->dateIn = $DateIn;
    }

    public function setDateUp(DateTime $DateUp): void {
        $this->dateUp = $DateUp;
    }

    public function setDescription(string $Description): void {
        if(!empty($Description)) {
            $this->description = htmlspecialchars($Description);
        }
    }

    public function setPrix(float $Prix): void {
        if($Prix > 0) {
            $this->prix = $Prix;
        }
    }

/* ---------------------------- Interface Methods --------------------------- */
    public function jsonSerialize(): mixed {
        return array(
            'id'          => $this->getId(),
            'nom'         => $this->getNom(),
            'description' => $this->getDescription(),
            'dateIn'      => $this->getDateIn()->format(ProduitManager::AMERICAN_DATE_FORMAT),
            'dateUp'      => $this->getDateUp()->format(ProduitManager::AMERICAN_DATE_FORMAT),
            'prix'        => $this->getPrix()
        );
    }
}