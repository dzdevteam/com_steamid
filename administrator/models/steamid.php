<?php
/**
 * @version     1.0.0
 * @package     com_steamid
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      DZ Dev <herophuong93@gmail.com> - http://dezign.vn
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Steamid model.
 */
class SteamidModelSteamid extends JModelAdmin
{
    /**
     * @var     string  The prefix to use with controller messages.
     * @since   1.6
     */
    protected $text_prefix = 'COM_STEAMID';


    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param   type    The table type to instantiate
     * @param   string  A prefix for the table class name. Optional.
     * @param   array   Configuration array for model. Optional.
     * @return  JTable  A database object
     * @since   1.6
     */
    public function getTable($type = 'Steamid', $prefix = 'SteamidTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param   array   $data       An optional array of data for the form to interogate.
     * @param   boolean $loadData   True if the form is to load its own data (default case), false if not.
     * @return  JForm   A JForm object on success, false on failure
     * @since   1.6
     */
    public function getForm($data = array(), $loadData = true)
    {
        // Initialise variables.
        $app    = JFactory::getApplication();

        // Get the form.
        $form = $this->loadForm('com_steamid.steamid', 'steamid', array('control' => 'jform', 'load_data' => $loadData));


        if (empty($form)) {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed   The data for the form.
     * @since   1.6
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_steamid.edit.steamid.data', array());

        if (empty($data)) {
            $data = $this->getItem();

        }

        return $data;
    }

    /**
     * Method to get a single record.
     *
     * @param   integer The id of the primary key.
     *
     * @return  mixed   Object on success, false on failure.
     * @since   1.6
     */
    public function getItem($pk = null)
    {
        if ($item = parent::getItem($pk)) {

            //Do any procesing on fields here if needed

        }

        return $item;
    }

    /**
     * Prepare and sanitise the table prior to saving.
     *
     * @since   1.6
     */
    protected function prepareTable($table)
    {
        jimport('joomla.filter.output');

        if (empty($table->id)) {

            // Set ordering to the last item if not set
            if (@$table->ordering === '') {
                $db = JFactory::getDbo();
                $db->setQuery('SELECT MAX(ordering) FROM #__steamid');
                $max = $db->loadResult();
                $table->ordering = $max+1;
            }

        }
    }

    /**
     * Reload steam information
     */
    public function reload($pks) {
        JArrayHelper::toInteger($pks);
        $count = 0;
        if ($pks) {
            // Get corresponding steam ids from primary keys
            $db = $this->getDbo();
            $query = $db->getQuery(true);
            $query->select("steamid")
                ->from('#__steamid')
                ->where('id IN (' . implode(',', $pks). ')');
            $db->setQuery($query);

            $steamids = $db->loadColumn(0);
            if ($steamids) {
                $result = json_decode(file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=E6C7134FF86C803B2A04D974976AE561&steamids='.implode(',',$steamids)), true);
                $summaries = $result['response']['players'];
                foreach ($summaries as $player_summary) {
                    $personaname = $player_summary['personaname'];
                    $realname = !empty($player_summary['realname']) ? $player_summary['realname'] : '';
                    $avatar = $player_summary['avatarfull'];
                    $profileurl = $player_summary['profileurl'];
                    $steamid = $player_summary['steamid'];

                    $fields = array(
                        $db->quoteName('personaname') . ' = ' . $db->quote($personaname),
                        $db->quoteName('realname') . ' = ' . $db->quote($realname),
                        $db->quoteName('avatar') . ' = ' . $db->quote($avatar),
                        $db->quoteName('profileurl') . ' = ' . $db->quote($profileurl)
                    );

                    $query = $db->getQuery(true);
                    $query->update('#__steamid')
                        ->set($fields)
                        ->where('steamid = ' . $db->quote($steamid));
                    $db->setQuery($query);
                    if ($db->query()) {
                        $count++;
                    }
                }
            }
        }

        return $count;
    }
}
