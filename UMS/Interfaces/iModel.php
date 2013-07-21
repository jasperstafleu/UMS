<?php
namespace UMS\Interfaces;

interface iModel {

    /**
     * Save this instance of the model to a non-session based storing medium
     * (ie: database)
     */
    public function save();

} // end interface \UMS\Interfaces\Model