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
