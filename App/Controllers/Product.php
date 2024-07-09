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

                $pictureName = Upload::uploadFile($_FILES['picture'], $id);

                Articles::attachPicture($id, $pictureName);

                header('Location: /product/' . $id);
            } catch (\Exception $e) {
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
        } catch (\Exception $e) {
            var_dump($e);
        }

        View::renderTemplate('Product/Show.html', [
            'article' => $article[0],
            'suggestions' => $suggestions
        ]);
    }
    /**
     * Affiche la page de contact réussi
     * @return void
     */
    public function contactSuccessAction() {
        View::renderTemplate('Product/Contact.html');

}

    /**
     * Handle the contact form submission
     * @return void
     */
    // public function contactAction() {
    //     if (isset($_POST['sellerEmail']) && isset($_POST['message'])) {
    //         $sellerEmail = $_POST['sellerEmail'];
    //         $message = $_POST['message'];

    //         // TODO: Send email logic here

    //         header('Location: /product/contact_success');
    //     } else {
    //         header('Location: /product/contact_error');
    //     }
    // }
}