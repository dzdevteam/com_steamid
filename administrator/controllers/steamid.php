<?php
/**
 * @version     1.0.0
 * @package     com_steamid
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      DZ Dev <herophuong93@gmail.com> - http://dezign.vn
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Steamid controller class.
 */
class SteamidControllerSteamid extends JControllerForm
{

    function __construct() {
        $this->view_list = 'steamids';
        parent::__construct();
    }

}