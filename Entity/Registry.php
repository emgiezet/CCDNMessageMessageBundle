<?php

/*
 * This file is part of the CCDNMessage MessageBundle
 *
 * (c) CCDN (c) CodeConsortium <http://www.codeconsortium.com/>
 *
 * Available on github <http://www.github.com/codeconsortium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CCDNMessage\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use CCDNMessage\MessageBundle\Model\Registry as AbstractRegistry;

class Registry extends AbstractRegistry
{
	/** @var integer $id */
    protected $id;

	/** @var integer $cachedUnreadMessageCount */
    protected $cachedUnreadMessagesCount = 0;

	/**
	 *
	 * @access public
	 */
    public function __construct()
    {
        parent::__construct();
        // your own logic
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
     * Get cachedUnreadMessagesCount
     *
     * @return integer
     */
    public function getCachedUnreadMessagesCount()
    {
        return $this->cachedUnreadMessagesCount;
    }
	
    /**
     * Set cachedUnreadMessagesCount
     *
     * @param integer $cachedUnreadMessagesCount
	 * @return Registry
     */
    public function setCachedUnreadMessagesCount($cachedUnreadMessagesCount)
    {
        $this->cachedUnreadMessagesCount = $cachedUnreadMessagesCount;
		
		return $this;
    }
}
