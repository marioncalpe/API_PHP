<?php

namespace App\Controller;

use App\Model\Produit;
use App\Util\HTTP;
use App\Util\Post;

class ProduitController {
    public function getFromId(int $pdtId) {
        $mngr = \App\Manager\ProduitManager::getInstance($GLOBALS['pdo']);
        $pdt = $mngr->getById(intval($pdtId));

        if($pdt != null) {
            HTTP::response(HTTP::CODE_2XX_SUCCESS, 'Success', $pdt);
        } else {
            HTTP::response(HTTP::CODE_4XX_NOTFOUND, 'No datas found');
        }
    }

    public function getFromName(string $pdtName) {
        $mngr = \App\Manager\ProduitManager::getInstance($GLOBALS['pdo']);
        $pdts = $mngr->getByName($pdtName);

        if(count($pdts) > 0) {
            HTTP::response(HTTP::CODE_2XX_SUCCESS, 'Success', $pdts);
        } else {
            HTTP::response(HTTP::CODE_4XX_NOTFOUND, 'No datas found');
        }
    }

    public function getAll() {
        $mngr = \App\Manager\ProduitManager::getInstance($GLOBALS['pdo']);
        $pdts = $mngr->getAll();

        HTTP::response(HTTP::CODE_2XX_SUCCESS, 'Success', $pdts);
    }

    public function remove(int $id) {
        $mngr = \App\Manager\ProduitManager::getInstance($GLOBALS['pdo']);
        if($mngr->drop($id)) {
            HTTP::response(HTTP::CODE_2XX_SUCCESS, 'Data Deleted');
        } else {
            HTTP::response(HTTP::CODE_4XX_BADREQUEST, 'No data to delete');
        }
    }

    public function add() {
        if(Post::exists('nom', 'description', 'prix')) {
            $mngr = \App\Manager\ProduitManager::getInstance($GLOBALS['pdo']);

            $insertedId = $mngr->insert(
                new Produit(
                    array(
                        'nom' => Post::get('nom'),
                        'description' => Post::get('description'),
                        'prix' => Post::get('prix')
                    )
                )
            );

            if($insertedId > 0) {
                HTTP::response(HTTP::CODE_2XX_CREATED, 'Created', array('id'=>$insertedId));
            } else {
                HTTP::response(HTTP::CODE_5XX_INTERNAL, 'Internal SQL Error');
            }
        } else {
            HTTP::response(HTTP::CODE_4XX_BADREQUEST, 'Not enough datas');
        }
    }

    public function update(int $pdtId) {
        $mngr = \App\Manager\ProduitManager::getInstance($GLOBALS['pdo']);

        $pdt = $mngr->getById($pdtId);
        if($pdt != null) {
            HTTP::parseHttpRequest($datas);
            $pdt->setPrix(floatval($datas['prix']));
            $pdt->setDateUp(new \DateTime());
            if($mngr->update($pdt)) {
                HTTP::response(HTTP::CODE_2XX_SUCCESS, 'Updated', $pdt);
            } else {
                HTTP::response(HTTP::CODE_5XX_INTERNAL, 'Internal SQL error');
            }
        } else {
            HTTP::response(HTTP::CODE_4XX_BADREQUEST, 'No corresponding data');
        }
    }

    public function getHisto(int $pdtId) {}
}