<?php

namespace AppBundle\Controller;

use AppBundle\Entity\UserGroup;
use AppBundle\Helper\FormHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserGroupController extends Controller
{
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/group/new", name="newGroup")
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        $userGroup = new UserGroup();

        $form = $this->createForm('AppBundle\Form\UserGroupType', $userGroup);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($userGroup);
            $em->flush();

            return $this->redirectToRoute('listGroup');
        }

        return $this->render('form.html.twig', FormHelper::getArray($form, [
            'list' => $this->generateUrl('listGroup'),
            'list_name' => 'Group list'
        ]));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/group/edit/{id}", name="editGroup")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        /** @var UserGroup $userGroup */
        $userGroup = $this->getDoctrine()
            ->getRepository('AppBundle:UserGroup')
            ->find($id);

        if (!$userGroup) {
            throw $this->createNotFoundException(
                'No group found for id '.$id
            );
        }

        $form = $this->createForm('AppBundle\Form\UserGroupType', $userGroup);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($userGroup);
            $em->flush();

            return $this->redirectToRoute('listGroup');
        }

        return $this->render('form.html.twig', FormHelper::getArray($form));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/groups", name="listGroup")
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $userGroupRepository = $this->getDoctrine()
            ->getRepository('AppBundle:UserGroup');

        $groups = $userGroupRepository->findAll();

        return $this->render('grid.html.twig', [
            'view' => 'user_group',
            'groups' => $groups,
            'new' => $this->generateUrl('newGroup')
        ]);
    }
}
