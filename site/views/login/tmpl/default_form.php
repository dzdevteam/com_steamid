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
?>
<div id="steamlogin">
    <img src="<?php echo JUri::root().'media/system/images/modal/spinner.gif'; ?>" />
    <?php echo JText::_('COM_STEAMID_OPENID_TRANSACTION_IN_PROGRESS'); ?>
    <?php echo $this->form; ?>
    <script type="text/javascript">
    document.getElementById("openid_message").submit();
    </script>
</div>
