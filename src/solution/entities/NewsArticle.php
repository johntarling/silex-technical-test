<?php

namespace Solution\Entities;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Entity
 * @Table(name="news")
 * @Solution\Entities\NewsArticle
 */
class NewsArticle
{
    /** 
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /** @Column(type="text") */
    private $title;

    /** @Column(type="text") */
    private $short_desc;

    /** @Column(type="text") */
    private $body;

    /** @Column(length=100) */
    private $posted;

    /** @Column(length=255) */
    private $listing_image_url;

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
     * @param string $title
     * @return NewsArticle
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set short_desc
     *
     * @param string $shortDesc
     * @return NewsArticle
     */
    public function setShortDesc($shortDesc)
    {
        $this->short_desc = $shortDesc;
    
        return $this;
    }

    /**
     * Get short_desc
     *
     * @return string 
     */
    public function getShortDesc()
    {
        return $this->short_desc;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return NewsArticle
     */
    public function setBody($body)
    {
        $this->body = $body;
    
        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set posted
     *
     * @param \string $posted
     * @return NewsArticle
     */
    public function setPosted($posted)
    {
        $this->posted = $posted;
    
        return $this;
    }

    /**
     * Get posted
     *
     * @return \string 
     */
    public function getPosted()
    {
        return date("j F, Y", (int)$this->posted);
    }

    /**
     * Set image_url
     *
     * @param string $listingimageUrl
     * @return NewsArticle
     */
    public function setListingImageUrl($imageUrl)
    {
        $this->listing_image_url = $imageUrl;
    
        return $this;
    }

    /**
     * Get image_url
     *
     * @return string 
     */
    public function getImageUrl()
    {
        return $this->listing_image_url;
    }

}