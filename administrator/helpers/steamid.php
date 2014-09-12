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

/**
 * Steamid helper.
 */
class SteamidHelper {

    /**
     * Configure the Linkbar.
     */
    public static function addSubmenu($vName = '') {
                JHtmlSidebar::addEntry(
            JText::_('COM_STEAMID_TITLE_STEAMIDS'),
            'index.php?option=com_steamid&view=steamids',
            $vName == 'steamids'
        );

    }

    /**
     * Gets a list of the actions that can be performed.
     *
     * @return  JObject
     * @since   1.6
     */
    public static function getActions() {
        $user = JFactory::getUser();
        $result = new JObject;

        $assetName = 'com_steamid';

        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
        );

        foreach ($actions as $action) {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }


}
