<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @UniqueEntity(fields="email", message="Epostaddressen finns redan")
 * @UniqueEntity(fields="username", message="Användarnamnet är upptaget")
 * @ORM\Table(name="appuser")
 */
class AppUser implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer", length=20)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    protected $username;

    /**
     * @ORM\Column(name="first_name", type="string", length=100)
     * @Assert\NotBlank()
     */
    protected $firstName;
    
    /**
     * @ORM\Column(name="last_name", type="string", length=100)
     * @Assert\NotBlank()
     */
    protected $lastName;
    
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    protected $email;
    
    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank()
     * @Assert\Length(max = 4096)
     */
    protected $password;

    /**
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="appUsers")
     * @ORM\JoinColumn(name="locationid", referencedColumnName="id")
     */
    protected $location;

    /**
     * @ORM\ManyToMany(targetEntity="AppGroup", inversedBy="appUsers")
     * @ORM\JoinTable(name="appusers_appgroups",
     * joinColumns={@ORM\JoinColumn(name="app_user_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="app_group_id", referencedColumnName="id")}
     * )
     **/
    protected $appGroups;

    /**
     * @ORM\ManyToMany(targetEntity="Crop", inversedBy="appUsers")
     * @ORM\JoinTable(name="appusers_crops",
     * joinColumns={@ORM\JoinColumn(name="app_user_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="crop_id", referencedColumnName="id")}
     * )
     **/
    protected $crops;

    /**
     * @ORM\OneToMany(targetEntity="Friendship", mappedBy="appUser")
     */
    protected $friendships;

    /**
     * @ORM\OneToMany(targetEntity="StatusUpdate", mappedBy="appUser")
     */
    protected  $statusUpdates;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="appUser")
     */
    protected $comments;

    public function __construct() {
        $this->appGroups = new ArrayCollection();
        $this->crops = new ArrayCollection();
        $this->friendships = new ArrayCollection();
        $this->statusUpdates = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }
    
    public function getFirstName()
    {
        return $this->firstName;
    }
    
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }
    
    public function getLastName()
    {
        return $this->lastName;
    }
    
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    
    public function setEmail($email)
    {
        $this->email = $email;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function getAppGroups()
    {
        return $this->appGroups;
    }

    public function setAppGroups($appGroups)
    {
        $this->appGroups = $appGroups;
    }

    public function getCrops()
    {
        return $this->crops;
    }

    public function setCrops($crops)
    {
        $this->crops = $crops;
    }

    public function getFriendships()
    {
        return $this->friendships;
    }

    public function setFriendships($friendships)
    {
        $this->friendships = $friendships;
    }

    public function getStatusUpdates()
    {
        return $this->statusUpdates;
    }

    public function setStatusUpdates($statusUpdates)
    {
        $this->statusUpdates = $statusUpdates;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password
            ) = unserialize($serialized);
    }
}
