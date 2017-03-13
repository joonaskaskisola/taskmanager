<?php

namespace AppBundle\Entity;

use Cake\Chronos\Chronos;
use Doctrine\ORM\Mapping as ORM;

/**
 * PrivateMessage
 *
 * @ORM\Table(name="private_message")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PrivateMessageRepository")
 */
class PrivateMessage
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="from_user", referencedColumnName="id")
     */
    private $fromUser;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="to_user", referencedColumnName="id")
     */
    private $toUser;

    /**
     * @var Chronos
     *
     * @ORM\Column(name="timestamp", type="datetimetz")
     */
    private $timestamp;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_read", type="boolean")
     */
    private $isRead;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @var int
     *
     * @ORM\Column(name="reply_id", type="integer", nullable=true)
     */
    private $replyId;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fromUser
     *
     * @param User $fromUser
     *
     * @return PrivateMessage
     */
    public function setFromUser(User $fromUser)
    {
        $this->fromUser = $fromUser;

        return $this;
    }

    /**
     * Get fromUser
     *
     * @return User
     */
    public function getFromUser()
    {
        return $this->fromUser;
    }

    /**
     * Set toUser
     *
     * @param User|object $toUser
     *
     * @return PrivateMessage
     */
    public function setToUser(User $toUser)
    {
        $this->toUser = $toUser;

        return $this;
    }

    /**
     * Get toUser
     *
     * @return User
     */
    public function getToUser()
    {
        return $this->toUser;
    }

    /**
     * Set timestamp
     *
     * @param Chronos $timestamp
     *
     * @return PrivateMessage
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return Chronos
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set isRead
     *
     * @param boolean $isRead
     *
     * @return PrivateMessage
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;

        return $this;
    }

    /**
     * Get isRead
     *
     * @return bool
     */
    public function getIsRead()
    {
        return $this->isRead;
    }

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return PrivateMessage
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return PrivateMessage
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    public function jsonSerialize()
    {
        return array(
            'timestamp' => $this->timestamp,
            'from_user'=> $this->fromUser,
            'subject' => $this->subject
        );
    }

    /**
     * @param int $replyId
     * @return PrivateMessage
     */
    public function setReplyId($replyId)
    {
        $this->replyId = $replyId;

        return $this;
    }

    /**
     * @return int
     */
    public function getReplyId()
    {
        return $this->replyId;
    }
}
