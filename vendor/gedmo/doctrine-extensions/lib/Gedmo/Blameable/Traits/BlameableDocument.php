<?php

namespace Gedmo\Blameable\Traits;

/**
 * Blameable Trait, usable with PHP >= 5.4
 *
 * @author David Buchmann <mail@davidbu.ch>
 * @package Gedmo.Blameable.Traits
 * @subpackage BlameableDocument
 * @link http://www.gediminasm.org
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
trait BlameableDocument
{
    /**
     * @Gedmo\Blameable(on="create")
     * @ODM\String
     */
    private $createdBy;

    /**
     * @Gedmo\Blameable(on="update")
     * @ODM\String
     */
    private $updatedBy;

    /**
     * Sets createdBy.
     *
     * @param  string $createdBy
     * @return $this
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Returns createdBy.
     *
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Sets updatedBy.
     *
     * @param  string $updatedBy
     * @return $this
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Returns updatedBy.
     *
     * @return string
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }
}
