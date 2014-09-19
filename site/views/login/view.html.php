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

jimport('joomla.application.component.view');

/**
 * View class for a list of Steamid.
 */
class SteamidViewLogin extends JViewLegacy {
    protected $try_auth;
    protected $form;
    protected $return;

    /**
     * Temporary put logic here
     * Should put them into controller instead
     */
    public function display($tpl = null) {
        $app = JFactory::getApplication();

        // Redirect to this URL after successfully logged in
        $this->return = JRequest::getVar('return');

        // Get form url
        if (JRequest::getVar('Itemid')) {
            $this->form_url = JRoute::_('index.php?Itemid=' . JRequest::getVar('Itemid'));
        } else {
            $this->form_url = JRoute::_('index.php?option=com_steamid&view=login');
        }
        $user = JFactory::getUser();
        if (!$user->get('guest')) {
            // Already logged in
            if ($this->return) {
                $app->redirect(base64_decode($this->return), JText::_('COM_STEAMID_ALREADY_LOGGED_IN'));
            } else {
                $app->redirect(JUri::root(), JText::_('COM_STEAMID_ALREADY_LOGGED_IN'));
            }
        }

        // Processing openid submit form
        $this->try_auth = JRequest::getVar('try_auth');
        if ($this->try_auth) {
            if ($this->return) {
                $session = &JFactory::getSession();
                $session->set('user.return', $this->return);
            }
            try {
                $this->form = SteamidFrontendHelper::getForm();
            } catch (Exception $e) {
                // Return to current url to show error
                $url = SteamidFrontendHelper::getCurrentUrl();
                JFactory::getApplication()->redirect(JRoute::_($url), $e->getMessage(), 'error');
            }
        }

        // Processing openid response
        if (JRequest::getVar('janrain_nonce')) {
            $credentials = $_GET;

            $result = JFactory::getApplication()->login($_GET, array('autoregister' => true));
            usleep(300); // Make sure the login session is complete before redirect

            $session = &JFactory::getSession();
            if ($result) {
                if ($session->get('user.first_connect', false)) {
                    $session->clear('user.first_connect');
                    $app->enqueueMessage(JText::_('COM_STEAMID_FIRST_LOGIN_MESSAGE'), 'notice');
                    $app->redirect(JRoute::_('index.php?option=com_users&view=profile&layout=edit'));
                } else if ($session->get('user.return')) {
                    $return = base64_decode($session->get('user.return'));
                    $session->clear('user.return');
                    $app->redirect($return, JText::_('COM_STEAMID_LOGIN_SUCCESS'), 'success');
                } else {
                    $app->redirect(JUri::root(), JText::_('COM_STEAMID_LOGIN_SUCCESS'), 'success');
                }
            } else {
                // Nothing
            }

        }

        parent::display($tpl);
    }
}
