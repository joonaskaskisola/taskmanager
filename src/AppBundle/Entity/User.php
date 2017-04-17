<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use FOS\UserBundle\Model\User as BaseUser;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser implements \Serializable, TwoFactorInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Customer")
     * @ORM\JoinColumn(name="customer", referencedColumnName="id")
     */
    protected $customer;

    /**
     * @ORM\Column(type="string", length=64)
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", length=64)
     */
    protected $lastName;

    /**
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(name="country", referencedColumnName="id")
     */
    protected $country;

    /**
     * @ORM\Column(type="string", length=30, unique=false, nullable=true)
     */
    protected $phone;

    /**
     * @ORM\Column(name="google_tfa_secret", type="string", nullable=true)
     */
    private $googleAuthenticatorSecret;

    /**
     * @ORM\Column(name="tfa_enabled", type="boolean", nullable=true)
     */
    private $isTfaEnabled;

    /**
     * @ORM\ManyToOne(targetEntity="Media")
     * @ORM\JoinColumn(name="profile_picture", referencedColumnName="id")
     */
    private $profilePicture;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param mixed $country
     * @return User
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        $number = $phoneUtil->parse($phone, $this->getCountry()->getCode());
        if ($phoneUtil->isValidNumber($number)) {
            $this->phone = $phoneUtil->format($number, PhoneNumberFormat::INTERNATIONAL);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->getLastName() . ' ' . $this->getFirstName();
    }

    /**
     * @param mixed $customer
     * @return User
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @return mixed
     */
    public function getGoogleAuthenticatorSecret()
    {
        if ($this->isTfaEnabled()) {
            return $this->googleAuthenticatorSecret;
        }

        return null;
    }

    /**
     * @param mixed $googleAuthenticatorSecret
     * @return $this
     */
    public function setGoogleAuthenticatorSecret($googleAuthenticatorSecret)
    {
        $this->googleAuthenticatorSecret = $googleAuthenticatorSecret;

        return $this;
    }

    /**
     * @return mixed
     */
    public function isTfaEnabled()
    {
        return $this->isTfaEnabled;
    }

    /**
     * @param mixed $isTfaEnabled
     * @return $this
     */
    public function setTfaEnabled($isTfaEnabled)
    {
        $this->isTfaEnabled = $isTfaEnabled;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTfaKey()
    {
        return $this->googleAuthenticatorSecret;
    }

    /**
     * @return mixed
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    /**
     * @param mixed $profilePicture
     */
    public function setProfilePicture($profilePicture)
    {
        $this->profilePicture = $profilePicture;
    }
}
