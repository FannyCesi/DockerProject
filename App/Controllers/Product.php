<?php

namespace App\Controllers;

use App\Models\Articles;
use App\Utility\Upload;
use Core\View;
use Exception;

/**
 * Product controller
 */
class Product extends \Core\Controller {

    /**
     * Affiche la page d'ajout
     * @return void
     */
    public function indexAction() {
        if (isset($_POST['submit'])) {
            try {
                $f = $_POST;
                // TODO: Validation

                $f['user_id'] = $_SESSION['user']['id'];
                $id = Articles::save($f);

                if (isset($_FILES['picture']) && $_FILES['picture']['error'] == UPLOAD_ERR_OK) {
                    $pictureName = Upload::uploadFile($_FILES['picture'], $id);
                    Articles::attachPicture($id, $pictureName);
                }

                header('Location: /product/' . $id);
            } catch (Exception $e) {
                var_dump($e);
            }
        }

        View::renderTemplate('Product/Add.html');
    }

    /**
     * Affiche la page d'un produit
     * @return void
     */
    public function showAction() {
        $id = $this->route_params['id'];

        try {
            Articles::addOneView($id);
            $suggestions = Articles::getSuggest();
            $article = Articles::getOne($id);
        } catch (Exception $e) {
            var_dump($e);
        }

        View::renderTemplate('Product/Show.html', [
            'article' => $article[0],
            'suggestions' => $suggestions,
            'session_user_id' => $_SESSION['user']['id']
        ]);
    }

    /**
     * Gérer la mise à jour d'un article
     * @return void
     */
    public function editAction() {
        if (isset($_POST['update'])) {
            $id = $this->route_params['id'];
            try {
                $article = Articles::getOne($id);
                if ($article[0]['user_id'] == $_SESSION['user']['id']) {
                    $data = [
                        'name' => $_POST['name'],
                        'description' => $_POST['description']
                    ];

                    if (isset($_FILES['picture']) && $_FILES['picture']['error'] == UPLOAD_ERR_OK) {
                        $pictureName = Upload::uploadFile($_FILES['picture'], $id);
                        $data['picture'] = $pictureName;
                    }

                    Articles::update($id, $data);
                }
                header('Location: /product/' . $id);
            } catch (Exception $e) {
                var_dump($e);
            }
        }
    }

    /**
     * Gérer la suppression d'un article
     * @return void
     */
    public function deleteAction() {
        if (isset($_POST['delete'])) {
            $id = $this->route_params['id'];
            try {
                $article = Articles::getOne($id);
                if ($article[0]['user_id'] == $_SESSION['user']['id']) {
                    Articles::delete($id);
                }
                header('Location: /');
            } catch (Exception $e) {
                var_dump($e);
            }
        }
    }
}
