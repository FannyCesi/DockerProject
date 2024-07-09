<?php

namespace App\Controllers;

use App\Models\Articles;
use App\Utility\Upload;
use Core\View;

/**
 * Product controller
 */
class Product extends \Core\Controller {

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

            // Vérifier si l'utilisateur est connecté
            $isLoggedIn = isset($_SESSION['user']);

            // Vérifier si l'utilisateur est le vendeur de l'article
            $isOwner = $isLoggedIn && $_SESSION['user']['id'] == $article[0]['user_id'];

            // Afficher la page avec ou sans formulaire de modification et bouton de suppression
            View::renderTemplate('Product/Show.html', [
                'article' => $article[0],
                'suggestions' => $suggestions,
                'isLoggedIn' => $isLoggedIn,
                'isOwner' => $isOwner
            ]);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Met à jour un produit
     * @return void
     */
    public function editAction() {
        $id = $this->route_params['id'];

        try {
            // Vérifie si l'utilisateur est connecté
            if (!isset($_SESSION['user'])) {
                throw new \Exception("Vous devez être connecté pour modifier cet article.");
            }

            // Vérifie si l'utilisateur est le vendeur de l'article
            $article = Articles::getOne($id);
            if ($_SESSION['user']['id'] != $article[0]['user_id']) {
                throw new \Exception("Vous n'êtes pas autorisé à modifier cet article.");
            }

            // Vérifie si le formulaire a été soumis
            if (isset($_POST['submit'])) {
                $f = $_POST;
                $f['user_id'] = $_SESSION['user']['id'];

                // Gestion de l'upload de l'image si présente
                if (!empty($_FILES['picture']['name'])) {
                    $pictureName = Upload::uploadFile($_FILES['picture'], $id);
                    $f['picture'] = $pictureName;
                } else {
                    $f['picture'] = $article[0]['picture'];
                }

                Articles::update($id, $f);

                // Retourne un message de succès
                echo json_encode(['message' => "Article mis à jour avec succès."]);
                return;
            }
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Supprime un produit
     * @return void
     */
    public function deleteAction() {
        $id = $this->route_params['id'];

        try {
            // Vérifie si l'utilisateur est connecté
            if (!isset($_SESSION['user'])) {
                throw new \Exception("Vous devez être connecté pour supprimer cet article.");
            }

            // Vérifie si l'utilisateur est le vendeur de l'article
            $article = Articles::getOne($id);
            if ($_SESSION['user']['id'] != $article[0]['user_id']) {
                throw new \Exception("Vous n'êtes pas autorisé à supprimer cet article.");
            }

            Articles::delete($id, $_SESSION['user']['id']);

            // Retourne un message de succès
            echo json_encode(['message' => "Article supprimé avec succès."]);
            return;
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Affiche la page de contact réussi
     * @return void
     */
    public function contactSuccessAction() {
        View::renderTemplate('Product/Contact.html');
    }
}
