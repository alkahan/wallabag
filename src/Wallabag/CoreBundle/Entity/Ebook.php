<?php

namespace Wallabag\CoreBundle\Entity;

use wallabag\phpMobi;
use wallabag\PHPePub;

/**
 * Ebook.
 *
 */
class Ebook
{
	/**
     * @var string
     *
     */
    private $title;

    /**
     * @var string
     *
     */
    private $url;

    /**
     * @var string
     *
     */
    private $content;

    /**
     * @var date
     *
     */
    private $createdAt;

    /**
     * @var boolean
     *
     */
    private $includeComments;

    /**
     * @var int
     *
     */
    private $fullReadingTime;

    /**
     * 
     */
    private $tags;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->tags = new ArrayCollection();
    }

}

?>