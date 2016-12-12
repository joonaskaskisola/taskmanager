<?php

namespace AppBundle\Controller;

use AppBundle\Entity\PrivateMessage;
use AppBundle\Repository\PrivateMessageRepository;
use Cake\Chronos\Chronos;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class InboxController extends Controller
{
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/inbox", name="listPrivateMessage")
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        return $this->render('base.html.twig');
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
                'from' => sprintf("%s %s", $message->getFromUser()->getFirstName(), $message->getFromUser()->getLastName())
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
                'from_user' => $message->getFromUser()->getId(),
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
     * @Route("/api/inbox/reply", name="replyMessageAction")
     * @Method({"POST"})
     * @param Request $request
     * @return Response|JsonResponse
     */
    public function replyMessageAction(Request $request)
    {
        $replyToId = $request->request->get('replyToId');

        $repository = $this->getDoctrine()->getRepository('AppBundle:PrivateMessage');
        $em = $this->getDoctrine()->getManager();
        /** @var PrivateMessage $originalMessage */
        $originalMessage = $repository->find($replyToId);
        if ($originalMessage->getToUser() !== $this->container->get('security.context')->getToken()->getUser()) {
            return (new JsonResponse())->setStatusCode(403);
        }

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

        return (new JsonResponse())->setStatusCode(200);
    }

    /**
     * @param Request $request
     * @return JsonResponse*
     * @Route("/api/inbox", name="postMessageAction")
     * @Method({"POST"})
     */
    public function postMessageAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $this->getDoctrine()->getRepository('AppBundle:User');

        /** @var PrivateMessage $message */
        $message = new PrivateMessage();

        $message
            ->setIsRead(0)
            ->setTimestamp(new Chronos())
            ->setToUser($userRepository->findOneBy(['id' => $request->request->get('to_user')]))
            ->setFromUser($this->container->get('security.context')->getToken()->getUser())
            ->setSubject($request->request->get('subject'))
            ->setMessage(strip_tags($request->request->get('message')));

        $em->persist($message);
        $em->flush();

        return new JsonResponse();
    }
}
