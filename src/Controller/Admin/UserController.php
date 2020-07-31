<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/utilisateurs")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     * @param UserRepository $userRepository
     * @return Response
     */
    public function list(UserRepository $userRepository): Response
    {
        return $this->render('admin/user/list.html.twig', [
            'users' => $userRepository->findAll(),
            'breadcrumb' => [
                0 => [
                    'url' => $this->generateUrl('app_admin_dashboard_index'),
                    'name' => 'Tableau de bord',
                ],
                1 => [
                    'url' => $this->generateUrl('app_admin_user_list'),
                    'name' => 'Liste des utilisateurs',
                ],
            ],
        ]);
    }

    /**
     * @Route("/nouveau", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_user_list');
        }

        return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'breadcrumb' => [
                0 => [
                    'url' => $this->generateUrl('app_admin_dashboard_index'),
                    'name' => 'Tableau de bord',
                ],
                1 => [
                    'url' => $this->generateUrl('app_admin_user_list'),
                    'name' => 'Liste des utilisateurs',
                ],
                2 => [
                    'url' => $this->generateUrl('app_admin_user_new'),
                    'name' => 'Créer un utilisateur',
                ],
            ],
        ]);
    }

    /**
     * @Route("/{id}/modifier", methods={"GET","POST"})
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_admin_user_list');
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'breadcrumb' => [
                0 => [
                    'url' => $this->generateUrl('app_admin_dashboard_index'),
                    'name' => 'Tableau de bord',
                ],
                1 => [
                    'url' => $this->generateUrl('app_admin_user_list'),
                    'name' => 'Liste des utilisateurs',
                ],
                2 => [
                    'url' => $this->generateUrl('app_admin_user_edit', ['id' => $request->get('id')]),
                    'name' => 'Modifier utilisateur #' . $user->getId(),
                ],
            ],
        ]);
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_user_list');
    }
}
