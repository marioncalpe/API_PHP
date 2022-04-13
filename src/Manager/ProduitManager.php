<?php

namespace App\Manager;

use App\Model\Produit;
use DateTime;

class ProduitManager {
    const AMERICAN_DATE_FORMAT = 'Y-m-d H:m:s';

    private static $instance = null;

    private $pdo = null;

    private function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    public static function getInstance(\PDO $pdo): ProduitManager {
        if(self::$instance == null) {
            self::$instance = new self($pdo);
        }

        return self::$instance;
    }

    public function getById(int $id): ?Produit {
        $result = null;

        $stmt = $this->pdo->prepare(
            'SELECT id_produit AS id, nom, date_in AS dateIn, date_up AS dateUp,
            description, prix FROM produits WHERE id_produit=?'
        );
        $stmt->execute(array($id));

        if($stmt->rowCount() > 0) {
            while($buf = $stmt->fetch()) {
                $buf['dateIn'] = DateTime::createFromFormat(
                    self::AMERICAN_DATE_FORMAT, $buf['dateIn']);
                $buf['dateUp'] = DateTime::createFromFormat(
                    self::AMERICAN_DATE_FORMAT, $buf['dateUp']);

                $result = new Produit($buf);
            }
        }
        $stmt->closeCursor();

        return $result;
    }

    public function getByName(string $name): array {
        $result = array();

        $stmt = $this->pdo->prepare(
            'SELECT id_produit AS id, nom, date_in AS dateIn, date_up AS dateUp,
            description, prix FROM produits WHERE nom LIKE ?'
        );
        $stmt->execute(array("%$name%"));

        if($stmt->rowCount() > 0) {
            while($buf = $stmt->fetch()) {
                $buf['dateIn'] = DateTime::createFromFormat(
                    self::AMERICAN_DATE_FORMAT, $buf['dateIn']);
                $buf['dateUp'] = DateTime::createFromFormat(
                    self::AMERICAN_DATE_FORMAT, $buf['dateUp']);

                array_push($result, new Produit($buf));
            }
        }
        $stmt->closeCursor();

        return $result;
    }

    public function getAll(): array {
        $result = array();

        $stmt = $this->pdo->query(
            'SELECT id_produit AS id, nom, date_in AS dateIn, date_up AS dateUp,
            description, prix FROM produits'
        );

        if($stmt->rowCount() > 0) {
            while($buf = $stmt->fetch()) {
                $buf['dateIn'] = DateTime::createFromFormat(
                    self::AMERICAN_DATE_FORMAT, $buf['dateIn']);
                $buf['dateUp'] = DateTime::createFromFormat(
                    self::AMERICAN_DATE_FORMAT, $buf['dateUp']);

                array_push($result, new Produit($buf));
            }
        }
        $stmt->closeCursor();

        return $result;
    }

    public function insert(Produit $pdt): int {
        $result = -1;

        $stmt = $this->pdo->prepare('INSERT INTO produits(nom, description, prix) VALUE (?,?,?)');
        if($stmt->execute(array($pdt->getNom(), $pdt->getDescription(), $pdt->getPrix()))) {
            $result = $this->pdo->lastInsertId();
        }

        return $result;
    }

    public function drop(int $pdtId): bool {
        $stmt = $this->pdo->prepare('DELETE FROM produits WHERE id_produit=?');
        $result = $stmt->execute(array($pdtId));
        $stmt->closeCursor();
        return $result;
    }

    public function update(Produit $pdt): bool {
        $stmt = $this->pdo->prepare(
            'UPDATE produits SET prix=?, date_up=CURRENT_TIMESTAMP WHERE id_produit=?;
            INSERT INTO evolutions(id_produit, prix) VALUES (?, ?);'
        );

        $id = $pdt->getId();
        $prix = $pdt->getPrix();
        $result = $stmt->execute(array($prix, $id, $id, $prix));
        $stmt->closeCursor();

        return $result;
    }
}