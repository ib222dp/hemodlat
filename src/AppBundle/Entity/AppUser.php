<?php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Entity\AppUserRepository")
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
     * @ORM\ManyToOne(targetEntity="County", inversedBy="appUsers")
     * @ORM\JoinColumn(name="county_id", referencedColumnName="id")
     */
    protected $county;

    /**
     * @ORM\OneToOne(targetEntity="ProfilePicture", inversedBy="appUser")
     * @ORM\JoinColumn(name="profilepicture_id", referencedColumnName="id")
     */
    protected $profilePicture;

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
     * @ORM\OneToMany(targetEntity="PMReception", mappedBy="appUser")
     */
    protected $PMReceptions;

    /**
     * @ORM\OneToMany(targetEntity="PMThreadParticipation", mappedBy="appUser")
     */
    protected $PMThreadParticipations;

    /**
     * @ORM\OneToMany(targetEntity="Friendship", mappedBy="appUser")
     */
    protected $friendships;

    /**
     * @ORM\OneToMany(targetEntity="StatusUpdate", mappedBy="creator")
     */
    protected  $statusUpdates;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="creator")
     */
    protected $comments;

    /**
     * @ORM\OneToMany(targetEntity="AppGroupUpdate", mappedBy="appUser")
     */
    protected $appGroupUpdates;

    /**
     * @ORM\OneToMany(targetEntity="AppGroup", mappedBy="creator")
     */
    protected $createdGroups;

    /**
     * @ORM\OneToMany(targetEntity="FriendUpdate", mappedBy="receiver")
     */
    protected $receivedFriendUpdates;

    /**
     * @ORM\OneToMany(targetEntity="FriendUpdate", mappedBy="creator")
     */
    protected $createdFriendUpdates;

    /**
     * @ORM\OneToMany(targetEntity="FriendUpdateComment", mappedBy="creator")
     */
    protected $friendComments;

    /**
     * @ORM\OneToMany(targetEntity="PMThread", mappedBy="creator")
     */
    protected $createdPMThreads;

    /**
     * @ORM\OneToMany(targetEntity="PrivateMessage", mappedBy="creator")
     */
    protected $createdPMs;

    public function __construct() {
        $this->appGroups = new ArrayCollection();
        $this->crops = new ArrayCollection();
        $this->PMReceptions = new ArrayCollection();
        $this->PMThreadParticipations = new ArrayCollection();
        $this->friendships = new ArrayCollection();
        $this->statusUpdates = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->appGroupUpdates = new ArrayCollection();
        $this->createdGroups = new ArrayCollection();
        $this->receivedFriendUpdates = new ArrayCollection();
        $this->createdFriendUpdates = new ArrayCollection();
        $this->friendComments = new ArrayCollection();
        $this->createdPMThreads = new ArrayCollection();
        $this->createdPMs = new ArrayCollection();
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

    public function getCounty()
    {
        return $this->county;
    }

    public function setCounty($county)
    {
        $this->county = $county;
    }

    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    public function setProfilePicture($profilePicture)
    {
        $this->profilePicture = $profilePicture;
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

    public function getPMReceptions()
    {
        return $this->PMReceptions;
    }

    public function setPMReceptions($PMReceptions)
    {
        $this->PMReceptions = $PMReceptions;
    }

    public function getPMThreadParticipations()
    {
        return $this->PMThreadParticipations;
    }

    public function setPMThreadParticipations($PMThreadParticipations)
    {
        $this->PMThreadParticipations = $PMThreadParticipations;
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

    public function getAppGroupUpdates()
    {
        return $this->appGroupUpdates;
    }

    public function setAppGroupUpdates($appGroupUpdates)
    {
        $this->appGroupUpdates = $appGroupUpdates;
    }

    public function getReceivedFriendUpdates()
    {
        return $this->receivedFriendUpdates;
    }

    public function setReceivedFriendUpdates($receivedFriendUpdates)
    {
        $this->receivedFriendUpdates = $receivedFriendUpdates;
    }

    public function getCreatedFriendUpdates()
    {
        return $this->createdFriendUpdates;
    }

    public function setCreatedFriendUpdates($createdFriendUpdates)
    {
        $this->createdFriendUpdates = $createdFriendUpdates;
    }

    public function getFriendComments()
    {
        return $this->friendComments;
    }

    public function setFriendComments($friendComments)
    {
        $this->friendComments = $friendComments;
    }

    public function getCreatedPMThreads()
    {
        return $this->createdPMThreads;
    }

    public function setCreatedPMThreads($createdPMThreads)
    {
        $this->createdPMThreads = $createdPMThreads;
    }

    public function getCreatedPMs()
    {
        return $this->createdPMs;
    }

    public function setCreatedPMs($createdPMs)
    {
        $this->createdPMs = $createdPMs;
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
