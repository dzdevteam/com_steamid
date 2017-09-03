<?php

/**
 * @version     1.0.0
 * @package     com_steamid
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      DZ Dev <herophuong93@gmail.com> - http://dezign.vn
 */
defined('_JEXEC') or die;

/*
jimport('joomla.log.log');
JLog::addLogger(
    array(
         // Sets file name
         'text_file' => 'com_steamid.log.php'
    ),
    // Sets messages of all log levels to be sent to the file
    JLog::ALL,
    // The log category/categories which should be recorded in this file
    // In this case, it's just the one category from our extension, still
    // we need to put it inside an array
    array('com_steamid')
);
// JLog::add(JText::_('STEAMID PHP INIT'), JLog::WARNING, 'com_steamid');
//JLog::add(JText::_('JTEXT_ERROR_MESSAGE'), JLog::WARNING, 'com_steamid');
*/

$path = ini_get('include_path');
$path_extra = JPATH_LIBRARIES.'/openid/';
$path = $path_extra . PATH_SEPARATOR . $path;
ini_set('include_path', $path);

if (file_exists($path_extra)) {
    require_once 'Auth/OpenID/Consumer.php';
    require_once 'Auth/OpenID/JDatabaseStore.php';
} else {
    throw new RuntimeException(JText::_('COM_STEAMID_EXCEPTION_LIB_NOT_INSTALLED'));
}

class SteamidFrontendHelper {
    public static function getReturnURL($params, $type)
    {
        $app    = JFactory::getApplication();
        $router = $app->getRouter();
        $url = null;
        if ($itemid = $params->get($type))
        {
            $db     = JFactory::getDbo();
            $query  = $db->getQuery(true)
                ->select($db->quoteName('link'))
                ->from($db->quoteName('#__menu'))
                ->where($db->quoteName('published') . '=1')
                ->where($db->quoteName('id') . '=' . $db->quote($itemid));

            $db->setQuery($query);
            if ($link = $db->loadResult())
            {
                if ($router->getMode() == JROUTER_MODE_SEF)
                {
                    $url = 'index.php?Itemid='.$itemid;
                }
                else {
                    $url = $link.'&Itemid='.$itemid;
                }
            }
        }
        if (!$url)
        {
            $url = self::getCurrentUrl();
        }

        return $url;
    }

    public static function getCurrentUrl()
    {
        $url = null;

        // Stay on the same page
        $uri = clone JUri::getInstance();
        $app    = JFactory::getApplication();
        $router = $app->getRouter();
        $vars = $router->parse($uri);
        unset($vars['lang']);
        if ($router->getMode() == JROUTER_MODE_SEF)
        {
            if (isset($vars['Itemid']))
            {
                $itemid = $vars['Itemid'];
                $menu = $app->getMenu();
                $item = $menu->getItem($itemid);
                unset($vars['Itemid']);
                if (isset($item) && $vars == $item->query)
                {
                    $url = 'index.php?Itemid='.$itemid;
                }
                else {
                    $url = 'index.php?'.JUri::buildQuery($vars).'&Itemid='.$itemid;
                }
            }
            else
            {
                $url = 'index.php?'.JUri::buildQuery($vars);
            }
        }
        else
        {
            $url = 'index.php?'.JUri::buildQuery($vars);
        }

        return $url;
    }

    public static function getType()
    {
        $user = JFactory::getUser();
        return (!$user->get('guest')) ? 'logout' : 'login';
    }

    /**
     * Return 1 if https url, 2 if http,
     * -1 if neither.
     * Used when creating return URL with JRoute().
     */
    protected function usessl_int() {
        $uri = JUri::getInstance();
        if ($uri->getScheme() == 'https') {
            return 1;
        } else if ($uri-getScheme() == 'http') {
            return 2;
        } else {
            return -1;
        }
    }

    public static function getForm()
    {
        $identifier = 'http://steamcommunity.com/openid';

        $store = new Auth_OpenID_JDatabaseStore();
        $store->createTables();
        $consumer = new Auth_OpenID_Consumer($store);

        $auth_request = $consumer->begin($identifier);

        if (!$auth_request) {
            throw new RuntimeException(JText::_('COM_STEAMID_EXCEPTION_DISCOVER_FAILED'));
        }
        // Generate form markup and render it.
        $form_id = 'openid_message';
        $form_html = $auth_request->formMarkup(JUri::root(), JRoute::_(self::getCurrentUrl(), true, self::usessl_int() ),
                                                false, array('id' => $form_id));
        $form_html = str_replace('<input type="submit" value="Continue" />', '',$form_html);
        // JLog::add(JText::_('Returning form HTML: ' . $form_html ), JLog::DEBUG, 'com_steamid');

        // JLog::add(JText::_('JRoute return with -1: ' . JRoute::_(self::getCurrentUrl(), true, -1) ), JLog::DEBUG, 'com_steamid');// XXX
        // JLog::add(JText::_('JRoute return with 0: ' . JRoute::_(self::getCurrentUrl(), true, 0) ), JLog::DEBUG, 'com_steamid');// XXX
        // JLog::add(JText::_('JRoute return without: ' . JRoute::_(self::getCurrentUrl(), true) ), JLog::DEBUG, 'com_steamid');// XXX
        // JLog::add(JText::_('JRoute return with 1: ' . JRoute::_(self::getCurrentUrl(), true, 1) ), JLog::DEBUG, 'com_steamid');// XXX
        // JLog::add(JText::_('JRoute return with 2: ' . JRoute::_(self::getCurrentUrl(), true, 2) ), JLog::DEBUG, 'com_steamid');// XXX
        // JLog::add(JText::_('JRoute return with ssl func: ' . JRoute::_(self::getCurrentUrl(), true, self::usessl_int() ) ), JLog::DEBUG, 'com_steamid');// XXX

        return $form_html;
    }

    public static function getSteamInfo($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from('#__steamid')
            ->where('user_id = ' . (int) $id);
        $db->setQuery($query);

        return $db->loadObject();
    }
}
