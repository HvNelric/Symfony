<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * préfixe de route pour toutes les méthodes du controleur
 * @Route("/http")
 */
class HttpController extends Controller
{
    /**
     *
     * @Route("/")
     */
    public function index()
    {
        return $this->render('http/index.html.twig', [
            'http' => 'HttpController',
        ]);
    }
    
    /**
     * @Route("/request")
     */
    public function request(Request $request)
    {
        // var_dump($_GET['name'];
        dump($request->query->get('name'));

        // var_dump($_GET)
        dump($request->query->all());

        // GET ou POST
        dump($request->getMethod());

        if ($request->isMethod('POST')) {
            echo 'le formulaire a été envoyé';

            dump($request->request->get('nom'));

            // var_dump($_GET)
            dump($request->query->all());
        }

        /////////////////////
        // test appel AJAX//
        ///////////////////
        if (!$request->isXmlHtmlRequest()) {
            echo "<p>La page n'a pas été appelé en AJAX</p>";
        }

        return $this->render('http/request.html.twig'

        );
    }

        /**
         * @Route("/response")
         */
        public function response(Request $request) {
            // renvoie du texte brut
            $response = new Response('Ma réponse');

            // if($_GET['type'] == 'twig')
            if($request->query->get('type') == 'twig') {
                // $this->render('...') retourne objet Reponse
                // contenant une page construite avec twig
                $response = $this->render('http/response.html.twig');
            } elseif ($request->query->get('type') == 'json') {
                $exempleJson = [
                    'prenom' => 'Hoan Vu',
                    'nom' => 'Ngo'
                ];

                $response = new JsonResponse($exempleJson['prenom'] . ' ' . $exempleJson['nom']);
            }

            if($request->query->get('found') == 'no') {
                // jette une erreur 404
                throw new NotFoundHttpException();
            }

            if($request->query->get('redirect') == 'index') {
                // pour rediriger vers la page dont la route
                // a pour nom app_index_bonjour en lui passant
                // une valeur pour la partie variable de la route {qui}
                return $this->redirectToRoute('app_index_bonjour',

                    [
                        'qui' => 'toi'
                    ]
                );
            }

            return $response;
        }

        /**
         * @Route("/session")
         */
        public function session(Request $request) {
            // pour accéder à la session
            $session = $request->getSession();

            // ajoute un élément 'nom' valant 'HvN'
            // à la session
            // $_SESSION['nom'] = 'HvN';
            $session->set('nom', 'HvN');

            $session->set('prenom', 'HoanVu');
            dump($session->get('nom'));

            // var_dump($_SESSION);
            dump($session->all());

            //supprime un élément un élément de la session
            // unset($_SESSION['prenom]);
            $session->remove('prenom');

            dump($session->all());

            return $this->render('http/session.html.twig');

        }

         /**
         * @Route("/flash")
         */
        public function flash(Request $request) {
            // ajoute un message flash de type success
            $this->addFlash('success', 'Message de succès');
            return $this->redirectToRoute('app_http_flashed');
        }

    /**
     * @Route("/flashed")
     */
        public function flashed() {
            return $this->render('http/flashed.html.twig');
        }
    
}
