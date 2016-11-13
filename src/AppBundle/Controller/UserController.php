<?php

namespace AppBundle\Controller;

use AppBundle\Entity\PrivateMessage;
use AppBundle\Entity\User;
use AppBundle\Helper\FormHelper;
use AppBundle\Repository\UserRepository;
use Cake\Chronos\Chronos;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserController extends Controller
{
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/user/new", name="newUser")
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        $user = new User();

        $form = $this->createForm('AppBundle\Form\UserType', $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $user->setPassword(
                $this->get('security.password_encoder')
                    ->encodePassword($user, $user->getPassword())
            );

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('listUser');
        }

        return $this->render('form.html.twig', FormHelper::getArray($form));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/user/edit/{id}", name="editUser")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        /** @var User $user */
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$id
            );
        }

        $form = $this->createForm('AppBundle\Form\UserType', $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if (!empty($user->getPassword())) {
                $user->setPassword(
                    $this->get('security.password_encoder')
                        ->encodePassword($user, $user->getPassword())
                );
            }

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('listUser');
        }

        return $this->render('form.html.twig', [
            'form_layout' => 'user',
            'form' => $form->createView(),
            'list' => $this->generateUrl('listUser'),
            'list_name' => 'User list'
        ]);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/users", name="listUser")
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $userRepository = $this->getDoctrine()
            ->getRepository('AppBundle:User');

        $users = $userRepository->findAll();

        return $this->render('grid.html.twig', [
            'view' => 'user',
            'users' => $users,
            'new' => $this->generateUrl('newUser')
        ]);
    }
}
