<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Helper\FormHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CategoryController extends Controller
{
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/category/new", name="newCategory")
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        $category = new Category();

        $form = $this->createForm('AppBundle\Form\CategoryType', $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('listCategory');
        }

        return $this->render('form.html.twig', FormHelper::getArray($form));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/category/edit/{id}", name="editCategory")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        /** @var Category $task */
        $category = $this->getDoctrine()
            ->getRepository('AppBundle:Category')
            ->find($id);

        if (!$category) {
            throw $this->createNotFoundException(
                'No category found for id '.$id
            );
        }

        $form = $this->createForm('AppBundle\Form\CategoryType', $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('listCategory');
        }

        return $this->render('form.html.twig', FormHelper::getArray($form, [
            'list' => $this->generateUrl('listCategory'),
            'list_name' => 'Category list'
        ]));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/category", name="listCategory")
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Category');

        $categories = $repository->findBy([], ['name' => 'ASC']);

        return $this->render('grid.html.twig', [
            'view' => 'category',
            'categories' => $categories,
            'new' => $this->generateUrl('newCategory')
        ]);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/api/category", name="getCategories")
     * @param Request $request
     * @return JsonResponse
     */
    public function getCategoriesAction(Request $request)
    {
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Category');

        $response = array_map(function($category) {
            /** @var Category $category */
            return [
                'id' => $category->getId(),
                'name' => $category->getName(),
            ];
        }, $repository->findBy([], ['name' => 'ASC']));

        return new JsonResponse($response);
    }
}
