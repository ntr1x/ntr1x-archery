<?php

namespace NTR1X\LayoutBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as JMS;

/**
 * Portal
 *
 * @ORM\Table(name="portal_items")
 * @ORM\Entity(repositoryClass="NTR1X\LayoutBundle\Repository\PortalRepository")
 * @JMS\ExclusionPolicy("none")
 */
class Portal
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=511)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="portals")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @JMS\Exclude
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="Resource", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumn(name="resource_id", referencedColumnName="id", nullable=false)
     */
    private $resource;

    /**
     * @ORM\OneToMany(targetEntity="Page", mappedBy="portal", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $pages;

    public function __construct() {
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param array $title
     *
     * @return Portal
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return array
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set user
     *
     * @param \NTR1X\LayoutBundle\Entity\User $user
     *
     * @return Portal
     */
    public function setUser(\NTR1X\LayoutBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \NTR1X\LayoutBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set resource
     *
     * @param \NTR1X\LayoutBundle\Entity\Resource $resource
     *
     * @return Portal
     */
    public function setResource(\NTR1X\LayoutBundle\Entity\Resource $resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * Get resource
     *
     * @return \NTR1X\LayoutBundle\Entity\Resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Add page
     *
     * @param \NTR1X\LayoutBundle\Entity\Page $page
     *
     * @return Portal
     */
    public function addPage(\NTR1X\LayoutBundle\Entity\Page $page)
    {
        $this->pages[] = $page;

        return $this;
    }

    /**
     * Remove page
     *
     * @param \NTR1X\LayoutBundle\Entity\Page $page
     */
    public function removePage(\NTR1X\LayoutBundle\Entity\Page $page)
    {
        $this->pages->removeElement($page);
    }

    /**
     * Get pages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPages()
    {
        return $this->pages;
    }
}
