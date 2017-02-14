<?php

namespace WP\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use WP\PlatformBundle\Entity\Movie;
use WP\PlatformBundle\Entity\Director;
use WP\PlatformBundle\Entity\Message;
use WP\PlatformBundle\Entity\Comment;
use WP\PlatformBundle\Entity\Viewlist;
use WP\PlatformBundle\Entity\Wishlist;
use WP\PlatformBundle\Form\MovieType;
use WP\PlatformBundle\Form\DirectorType;
use WP\PlatformBundle\Form\MessageType;
use WP\PlatformBundle\Form\CommentType;
use WP\UserBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class MovieController extends Controller {
    /* Ajax */
    /* Ajouter Viewlist */
    /* path: wp_platform_addView */

    public function addViewAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $rep = '';

        $movie = $em->getRepository('WPPlatformBundle:Movie')->find($request->get('movie'));
        $user = $em->getRepository('WPUserBundle:User')->find($request->get('user'));

        $view = $em->getRepository('WPPlatformBundle:Viewlist')->findOneBy(['user' => $user, 'movie' => $movie]);
        $wish = $em->getRepository('WPPlatformBundle:Wishlist')->findOneBy(['user' => $user, 'movie' => $movie]);

        if ($view == null) {
            $view = new Viewlist();
            $view->setView(1);
            $view->setMovie($movie);
            $view->setUser($user);
            $rep = 'active';
            if ($wish->getWish() == 1) {
                $wish->setWish(0);
                $em->persist($wish);
                $rep = 'sp_active';
            }
        } else if ($view->getView() == 0) {
            $view->setView(1);
            $rep = 'active';
            if ($wish->getWish() == 1) {
                $wish->setWish(0);
                $em->persist($wish);
                $rep = 'sp_active';
            }
        } else if ($view->getView() == 1) {
            $view->setView(0);
            $rep = 'desactive';
        }
        $em->persist($view);
        $em->flush();
        return new Response($rep);
    }

    /* Ajax */
    /* Ajouter Wishlist */
    /* path: wp_platform_addWish */

    public function addWishAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $rep = '';
        $movie = $em->getRepository('WPPlatformBundle:Movie')->find($request->get('movie'));
        $user = $em->getRepository('WPUserBundle:User')->find($request->get('user'));

        $wish = $em->getRepository('WPPlatformBundle:Wishlist')->findOneBy(['user' => $user, 'movie' => $movie]);
        $view = $em->getRepository('WPPlatformBundle:Viewlist')->findOneBy(['user' => $user, 'movie' => $movie]);

        if ($view == null) {
            if ($wish == null) {
                $wish = new Wishlist();
                $wish->setWish(1);
                $wish->setMovie($movie);
                $wish->setUser($user);
                $rep = 'active';
            } else if ($wish->getWish() == 0) {
                $wish->setWish(1);
                $rep = 'active';
            } else if ($wish->getWish() == 1) {
                $wish->setWish(0);
                $rep = 'desactive';
            }
            $em->persist($wish);
            $em->flush();
        } else if ($view->getView() == 0) {
            if ($wish == null) {
                $wish = new Wishlist();
                $wish->setWish(1);
                $rep = 'active';
                $wish->setMovie($movie);
                $wish->setUser($user);
            } else if ($wish->getWish() == 0) {
                $wish->setWish(1);
                $rep = 'active';
            } else if ($wish->getWish() == 1) {
                $wish->setWish(0);
                $rep = 'desactive';
            }
            $em->persist($wish);
            $em->flush();
        }



        return new Response($rep);
    }

//    Page d'accueil
//    path: wp_platform_home

    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $listNewMovies = $em->getRepository('WPPlatformBundle:Movie')->findBy(
                array(), array('id' => 'desc'), 6
        );

        return $this->render('WPPlatformBundle:Movie:index.html.twig', array(
                    'listNewMovies' => $listNewMovies
        ));
    }

//    Liste des films ajouter récemment 
//    path: wp_platform_recentMovies

    public function recentMoviesAction($page) {
        $nbPerPage = 18;

        $em = $this->getDoctrine()->getManager();

        $listNewMovies = $em->getRepository('WPPlatformBundle:Movie')->getRecentMovies($page, $nbPerPage);

        $nbPages = ceil(count($listNewMovies) / $nbPerPage);
        if (count($listNewMovies) != 0) {
            if ($page < 1 || $page > $nbPages) {
                throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
            }
        }

        return $this->render('WPPlatformBundle:Movie:recentMovies.html.twig', array(
                    'listNewMovies' => $listNewMovies,
                    'nbPages' => $nbPages,
                    'page' => $page,
        ));
    }

    /* Fiche film */
    /* path: wp_platform_movie */

    public function movieAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $movie = $em->getRepository('WPPlatformBundle:Movie')->find($id);

        $directors = $em->getRepository('WPPlatformBundle:Director')->getDirectorWithMovieId($id);

        $user = $this->getUser();
        $myComment = $em->getRepository('WPPlatformBundle:Comment')->findOneBy(['user' => $user, 'movie' => $movie]);

        $comments = $em->getRepository('WPPlatformBundle:Comment')->findByMovie($movie, ['id' => 'DESC'], 5);

        $view = $em->getRepository('WPPlatformBundle:Viewlist')->findOneBy(['user' => $user, 'movie' => $movie]);
        $wish = $em->getRepository('WPPlatformBundle:Wishlist')->findOneBy(['user' => $user, 'movie' => $movie]);

        if ($myComment === null) {
            $myComment = new Comment();
        }

        $form = $this->get('form.factory')->create(CommentType::class, $myComment);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $myComment->setMovie($movie);
            $myComment->setUser($user);
            $em->persist($myComment);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Le commentaire a bien été modifié.');

            return $this->redirectToRoute('wp_platform_movie', ['id' => $id]);
        }


        return $this->render('WPPlatformBundle:Movie:movie.html.twig', array(
                    'movie' => $movie,
                    'directors' => $directors,
                    'form' => $form->createView(),
                    'myComment' => $myComment,
                    'comments' => $comments,
                    'view' => $view,
                    'wish' => $wish
        ));
    }

    /* Commentaires */
    /* path: wp_platform_commentsMovie */

    public function commentsAction($id, $page) {
        if ($page < 1) {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }

        $nbPerPage = 10;

        $em = $this->getDoctrine()->getManager();
        $movie = $em->getRepository('WPPlatformBundle:Movie')->find($id);
        $comments = $em->getRepository('WPPlatformBundle:Comment')->getComments($page, $nbPerPage, $id);

        $nbPages = ceil(count($comments) / $nbPerPage);
        if (count($comments) != 0) {
            if ($page > $nbPages) {
                throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
            }
        }
        return $this->render('WPPlatformBundle:Movie:comments.html.twig', array(
                    'movie' => $movie,
                    'comments' => $comments,
                    'nbPages' => $nbPages,
                    'page' => $page,
        ));
    }

    /* Fiche réalisateur */
    /* path: wp_platform_director */

    public function directorAction($id) {
        $em = $this->getDoctrine()->getManager();

        $director = $em->getRepository('WPPlatformBundle:Director')->find($id);

        $movies = $em->getRepository('WPPlatformBundle:Movie')->getMovieWithDirectorId($id);

        return $this->render('WPPlatformBundle:Movie:director.html.twig', array(
                    'director' => $director,
                    'movies' => $movies
        ));
    }

    /* Recherche */
    /* path: wp_platform_search */

    public function searchAction($page) {
        $em = $this->getDoctrine()->getManager();
        $movies = null;
        $nbPerPage = 1;
        $paramRoute = null;
        $genres = $em->getRepository('WPPlatformBundle:Genre')->findBy([], ['genre' => 'asc']);
        if (isset($_GET['keywords'])) {
            $keywords = $_GET['keywords'];
            $genre = $_GET['genre'];
            $paramRoute = array('genre' => $genre, 'keywords' => $keywords);

            $movies = $em->getRepository('WPPlatformBundle:Movie')->getMovieSearch($page, $nbPerPage, $keywords, $genre);
        }

        $nbPages = ceil(count($movies) / $nbPerPage);
        if (count($movies) != 0) {
            if ($page > $nbPages || $page < 1) {
                throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
            }
        }
        return $this->render('WPPlatformBundle:Movie:search.html.twig', array(
                    'movies' => $movies,
                    'nbPages' => $nbPages,
                    'page' => $page,
                    'genres' => $genres,
                    'paramRoute' => $paramRoute
        ));
    }

    /* Contact */
    /* wp_platform_contact */

    public function contactAction(Request $request) {
        $user = $this->getUser();

        $message = new Message();



        if ($user !== null) {
            $message->setPseudo($user->getUsername());
            $message->setMail($user->getEmail());
        }

        $form = $this->get('form.factory')->create(MessageType::class, $message);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $message->setIsread(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Message bien enregistré.');

            return $this->redirectToRoute('wp_platform_contact');
        }

        return $this->render('WPPlatformBundle:Movie:contact.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    /* Profile */
    /* path: wp_platform_user */

    public function userAction($user) {
        $em = $this->getDoctrine()->getManager();
        $totalDur = 0;
        $myUser = $em->getRepository('WPUserBundle:User')->findOneByUsername($user);
        $his = $em->getRepository('WPPlatformBundle:Viewlist')->findBy(['user' => $myUser, 'view' => 1]);

        foreach ($his as $hist) {
            $totalDur += $hist->getMovie()->getDuration();
        }

        $history = $em->getRepository('WPPlatformBundle:Viewlist')->findBy(['user' => $myUser, 'view' => 1], ['id' => 'desc'], 6);
        $wishlist = $em->getRepository('WPPlatformBundle:Wishlist')->findBy(['user' => $myUser, 'wish' => 1], ['id' => 'desc'], 6);

        return $this->render('WPPlatformBundle:Movie:user.html.twig', array(
                    'user' => $myUser,
                    'history' => $history,
                    'wishlist' => $wishlist,
                    'his' => $his,
                    'duration' => $totalDur
        ));
    }

    /* Historique film vu */
    /* path: wp_platform_history */

    public function historyAction($user, $page) {
        $em = $this->getDoctrine()->getManager();

        $myUser = $em->getRepository('WPUserBundle:User')->findOneByUsername($user);

        $nbPerPage = 18;
        $history = $em->getRepository('WPPlatformBundle:Viewlist')->getHistory($myUser->getId(), $page, $nbPerPage);

        $nbPages = ceil(count($history) / $nbPerPage);

        if (count($history) != 0) {
            if ($page > $nbPages && $page < 1) {
                throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
            }
        }
        return $this->render('WPPlatformBundle:Movie:history.html.twig', array(
                    'history' => $history,
                    'nbPages' => $nbPages,
                    'page' => $page
        ));
    }

    /* Liste de souhait */
    /* path: wp_platform_wishlist */

    public function wishlistAction($user, $page) {
        $em = $this->getDoctrine()->getManager();

        $myUser = $em->getRepository('WPUserBundle:User')->findOneByUsername($user);

        $nbPerPage = 18;
        $wishlist = $em->getRepository('WPPlatformBundle:Wishlist')->getWishlist($myUser->getId(), $page, $nbPerPage);

        $nbPages = ceil(count($wishlist) / $nbPerPage);
        if (count($wishlist) != 0) {
            if ($page > $nbPages && $page < 1) {
                throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
            }
        }

        return $this->render('WPPlatformBundle:Movie:wishlist.html.twig', array(
                    'wishlist' => $wishlist,
                    'nbPages' => $nbPages,
                    'page' => $page
        ));
    }

    /* Mise à jour du profil */
    /* path: wp_platform_updateUser */

    public function updateUserAction(Request $request, $user) {
        $userManager = $this->get('fos_user.user_manager');

        $myUser = $userManager->findUserBy(array('username' => $user));

        if ($this->getUser() === null) {
            throw new AccessDeniedException('Vous devez être connecté!');
        } else if ($myUser === null) {
            return $this->redirectToRoute('wp_platform_updateUser', ['user' => $this->getUser()->getUsername()]);
        } else if ($myUser->getUsername() !== $this->getUser()->getUsername()) {
            return $this->redirectToRoute('wp_platform_updateUser', ['user' => $this->getUser()->getUsername()]);
        }

        $form = $this->get('form.factory')->create(UserType::class, $myUser);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Message bien enregistré.');

            return $this->redirectToRoute('wp_platform_user', ['user' => $myUser->getUsername()]);
        }

        return $this->render('WPPlatformBundle:Movie:updateUser.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    /* Accueil admin - dernier admin connecté */
    /* path: wp_platform_adminHome */

    public function adminHomeAction() {
        $em = $this->getDoctrine()->getManager();

        $admins = $em->getRepository('WPUserBundle:User')->findAdmin();

        return $this->render('WPPlatformBundle:Movie:adminIndex.html.twig', array(
                    'admins' => $admins
        ));
    }

    /* Admin: Liste de film */
    /* path: wp_platform_adminMovie */

    public function adminMovieAction($page) {
        $em = $this->getDoctrine()->getManager();

        $nbPerPage = 15;

        $listMovies = $em->getRepository('WPPlatformBundle:Movie')->getRecentMovies($page, $nbPerPage);

        $nbPages = ceil(count($listMovies) / $nbPerPage);
        if (count($listMovies) != 0) {
            if ($page > $nbPages && $page < 1) {
                throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
            }
        }

        return $this->render('WPPlatformBundle:Movie:adminMovie.html.twig', array(
                    'listMovies' => $listMovies,
                    'nbPages' => $nbPages,
                    'page' => $page
        ));
    }

    /* Admin: Ajouter film */
    /* path: wp_platform_adminAddMovie */

    public function adminAddMovieAction(Request $request) {
        $movie = new Movie();

        $form = $this->get('form.factory')->create(MovieType::class, $movie);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Film bien enregistrée.');

            return $this->redirectToRoute('wp_platform_adminMovie');
        }

        return $this->render('WPPlatformBundle:Movie:adminAddMovie.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    /* Admin: Modifier film */
    /* path: wp_platform_adminEditMovie */

    public function adminEditMovieAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $movie = $em->getRepository('WPPlatformBundle:Movie')->find($id);

        if (null === $movie) {
            throw new NotFoundHttpException('Le film d\'id ' . $id . ' n\'existe pas.');
        }

        $form = $this->get('form.factory')->create(MovieType::class, $movie);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Le film a bien été modifié.');

            return $this->redirectToRoute('wp_platform_adminMovie');
        }

        return $this->render('WPPlatformBundle:Movie:adminEditMovie.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    /* Admin: Supprimer film */
    /* path: wp_platform_adminRemoveMovie */

    public function adminRemoveMovieAction($id) {
        $em = $this->getDoctrine()->getManager();
        $movie = $em->getRepository('WPPlatformBundle:Movie')->find($id);
        $wishlist = $em->getRepository('WPPlatformBundle:Wishlist')->findByMovie($movie);
        $viewlist = $em->getRepository('WPPlatformBundle:Viewlist')->findByMovie($movie);

        foreach ($wishlist as $wish) {
            $em->remove($wish);
        }

        foreach ($viewlist as $view) {
            $em->remove($view);
        }

        if (null === $movie) {
            throw new NotFoundHttpException('Le film d\'id ' . $id . ' n\'existe pas.');
        }

        $em->remove($movie);
        $em->flush();

        return $this->redirectToRoute('wp_platform_adminMovie');
    }

    /* Admin: Liste réalisateur */
    /* path: wp_platform_adminDirector */

    public function adminDirectorAction($page) {
        $em = $this->getDoctrine()->getManager();

        $nbPerPage = 15;

        $listDirectors = $em->getRepository('WPPlatformBundle:Director')->getRecentDirectors($page, $nbPerPage);

        $nbPages = ceil(count($listDirectors) / $nbPerPage);
        if (count($listDirectors) != 0) {
            if ($page > $nbPages && $page < 1) {
                throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
            }
        }
        return $this->render('WPPlatformBundle:Movie:adminDirector.html.twig', array(
                    'listDirectors' => $listDirectors,
                    'nbPages' => $nbPages,
                    'page' => $page
        ));
    }

    /* Admin: Ajouter réalisateur */
    /* path: wp_platform_adminAddDirector */

    public function adminAddDirectorAction(Request $request) {
        $director = new Director();

        $form = $this->get('form.factory')->create(DirectorType::class, $director);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($director);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Réalisateur bien enregistré.');

            return $this->redirectToRoute('wp_platform_adminDirector');
        }

        return $this->render('WPPlatformBundle:Movie:adminAddDirector.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    /* Admin: Modifier réalisateur */
    /* path: wp_platform_adminEditDirector */

    public function adminEditDirectorAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $director = $em->getRepository('WPPlatformBundle:Director')->find($id);

        if (null === $director) {
            throw new NotFoundHttpException('Le réalisateur d\'id ' . $id . ' n\'existe pas.');
        }

        $form = $this->get('form.factory')->create(DirectorType::class, $director);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Le Réalisateur a bien été modifié.');

            return $this->redirectToRoute('wp_platform_adminDirector');
        }

        return $this->render('WPPlatformBundle:Movie:adminEditDirector.html.twig', array(
                    'movie' => $director,
                    'form' => $form->createView(),
        ));
    }

    /* Admin: Supprimer réalisateur */
    /* path: wp_platform_adminRemoveDirector */

    public function adminRemoveDirectorAction($id) {
        $em = $this->getDoctrine()->getManager();
        $director = $em->getRepository('WPPlatformBundle:Director')->find($id);

        if (null === $director) {
            throw new NotFoundHttpException('Le film d\'id ' . $id . ' n\'existe pas.');
        }

        $em->remove($director);
        $em->flush();

        return $this->redirectToRoute('wp_platform_adminDirector');
    }

    /* Admin: Liste utilisateur, modérateur, super admin */
    /* path: wp_platform_adminUsers */

    public function adminUsersAction($page) {
        $em = $this->getDoctrine()->getManager();

        $nbPerPage = 15;

        $superAdmins = $em->getRepository('WPUserBundle:User')->findByRoles('ROLE_SUPER_ADMIN');
        $admins = $em->getRepository('WPUserBundle:User')->findByRoles('ROLE_ADMIN');
        $users = $em->getRepository('WPUserBundle:User')->findUserRoles($page, $nbPerPage);

        $nbPages = ceil(count($users) / $nbPerPage);
        if (count($users) != 0) {
            if ($page > $nbPages && $page < 1) {
                throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
            }
        }
        return $this->render('WPPlatformBundle:Movie:adminUser.html.twig', array(
                    'superAdmins' => $superAdmins,
                    'admins' => $admins,
                    'users' => $users,
                    'page' => $page,
                    'nbPages' => $nbPages
        ));
    }

    /* Admin: Supprimer utilisateur */
    /* path: wp_platform_adminUsersDelete */

    public function adminUsersDeleteAction($id) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException('Vous n\'avez pas les droits');
        }


        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('WPUserBundle:User')->find($id);
        $comments = $em->getRepository('WPPlatformBundle:Comment')->findByUser($user);

        foreach ($comments as $comment) {
            $em->remove($comment);
        }

        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('wp_platform_adminUsers');
    }

    /* Admin: Retrograder modérateur */
    /* wp_platform_adminUsersDowngrade */

    public function adminUsersDowngradeAction($id) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException('Vous n\'avez pas les droits');
        }

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('WPUserBundle:User')->find($id);

        $user->setRoles([]);

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('wp_platform_adminUsers');
    }

    /* Admin: Upgrader utilisateur */
    /* path: wp_platform_adminUsersUpgrade */

    public function adminUsersUpgradeAction($id) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException('Vous n\'avez pas les droits');
        }

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('WPUserBundle:User')->find($id);

        $user->setRoles(['ROLE_ADMIN']);

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('wp_platform_adminUsers');
    }

    /* Admin: Messagerie interne */
    /* wp_platform_adminMessage */

    public function adminMessageAction() {
        $em = $this->getDoctrine()->getManager();
        $newMsg = $em->getRepository('WPPlatformBundle:Message')->findByIsread(false);
        $oldMsg = $em->getRepository('WPPlatformBundle:Message')->findByIsread(true);

        return $this->render('WPPlatformBundle:Movie:adminMessage.html.twig', array(
                    'newMsgs' => $newMsg,
                    'oldMsgs' => $oldMsg,
        ));
    }

    /* Admin: Message lu */
    /* path: wp_platform_adminReadMessage */

    public function adminReadMessageAction($id) {
        $em = $this->getDoctrine()->getManager();
        $msg = $em->getRepository('WPPlatformBundle:Message')->find($id);

        $msg->setIsread(true);

        $em->persist($msg);
        $em->flush();

        return $this->render('WPPlatformBundle:Movie:adminReadMessage.html.twig', array(
                    'msg' => $msg,
        ));
    }

    /* Admin: Supprimer message */
    /* path: wp_platform_adminDeleteMessage */

    public function adminDeleteMessageAction($id) {
        $em = $this->getDoctrine()->getManager();
        $msg = $em->getRepository('WPPlatformBundle:Message')->find($id);

        if (null === $msg) {
            throw new NotFoundHttpException('Le message d\'id ' . $id . ' n\'existe pas.');
        }

        $em->remove($msg);
        $em->flush();

        return $this->redirectToRoute('wp_platform_adminMessage');
    }

}
