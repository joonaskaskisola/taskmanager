<?php

namespace AppBundle\Controller;

use AppBundle\Entity\PrivateMessage;
use AppBundle\Repository\PrivateMessageRepository;
use Cake\Chronos\Chronos;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Helper\FormHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class PrivateMessageController extends Controller
{
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/inbox/new", name="newPrivateMessage")
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        $message = new PrivateMessage();
        $message->setIsRead(0);
        $message->setTimestamp(new Chronos());
        $message->setFromUser(
            $this->container->get('security.context')->getToken()->getUser()
        );

        $form = $this->createForm('AppBundle\Form\PrivateMessageType', $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($message);
            $em->flush();

            return $this->redirectToRoute('listPrivateMessage');
        }

        return $this->render('form.html.twig', FormHelper::getArray($form, ['form_layout' => 'inbox']));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/inbox", name="listPrivateMessage")
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        return $this->render('grid.html.twig', [
            'view' => 'inbox',
            'new' => $this->generateUrl('newPrivateMessage')
        ]);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/api/inbox", name="getPrivateMessages")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getAction(Request $request)
    {
        $messageRepository = $this->getDoctrine()->getRepository('AppBundle:PrivateMessage');

        $response = array_map(function($message) {
            /** @var PrivateMessage $message */
            return [
                'id' => $message->getId(),
                'timestamp' => $message->getTimeStamp()->format("Y-m-d H:i:s"),
                'subject' => $message->getSubject(),
                'is_read' => $message->getIsRead(),
                'from' => $message->getFromUser()->getUsername()
            ];
        }, $messageRepository->findBy([
            'toUser' => $this->container->get('security.context')->getToken()->getUser()
        ], ['isRead' => 'ASC', 'timestamp' => 'DESC']));

        return new JsonResponse($response);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/api/inbox/{id}", name="getPrivateMessage")
     * @Method({"GET"})
     * @param Request $request
     * @param integer $id
     * @return JsonResponse
     */
    public function getMessageAction(Request $request, $id)
    {
        $messageRepository = $this->getDoctrine()->getRepository('AppBundle:PrivateMessage');
        $em = $this->getDoctrine()->getManager();

        $response = array_map(function($message) use ($em) {
            /** @var PrivateMessage $message */
            if (!$message->getIsRead()) {
                $message->setIsRead(1);
                $em->persist($message);
                $em->flush();
            }

            return [
                'from' => [
                    'id' => $message->getFromUser()->getId(),
                    'firstName' => $message->getFromUser()->getFirstName(),
                    'lastName' => $message->getFromUser()->getLastName()
                ],
                'id' => $message->getId(),
                'timestamp' => $message->getTimeStamp()->format("Y-m-d H:i:s"),
                'subject' => $message->getSubject(),
                'message' => $message->getMessage()
            ];
        }, $messageRepository->findBy([
            'toUser' => $this->container->get('security.context')->getToken()->getUser(),
            'id' => $id
        ]));

        return new JsonResponse($response);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/api/inbox/reply/{replyToId}", name="postReplyMessage")
     * @Method({"POST"})
     * @param Request $request
     * @param integer $replyToId
     * @return JsonResponse
     */
    public function postReplyMessageAction(Request $request, $replyToId)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:PrivateMessage');
        $em = $this->getDoctrine()->getManager();
        /** @var PrivateMessage $originalMessage */
        $originalMessage = $repository->find($replyToId);

        $message = new PrivateMessage();
        $message
            ->setReplyId($replyToId)
            ->setIsRead(0)
            ->setTimestamp(new Chronos())
            ->setToUser($originalMessage->getFromUser())
            ->setFromUser($this->container->get('security.context')->getToken()->getUser())
            ->setSubject($originalMessage->getSubject())
            ->setMessage(strip_tags(sprintf(
                "%s\n\n%s wrote:\n%s",
                $request->request->get('message'),
                $originalMessage->getFromUser()->getUsername(),
                implode(
                    PHP_EOL,
                    array_map(function($row) {
                        return "> " . $row;
                    }, explode(PHP_EOL, $originalMessage->getMessage()))
                )
            )));

        $em->persist($message);
        $em->flush();

        return new JsonResponse();
    }
}
