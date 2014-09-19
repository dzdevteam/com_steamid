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
$image_src = JURI::root() . 'media/com_steamid/images/sits_large_border.png';
?>

<div id="steamlogin">
    <form id="steamlogin_form" method="POST" action="<?= $this->form_url; ?>">
        <input type="hidden" name="try_auth" value="1"/>
        <input type="image" src="<?php echo $image_src; ?>" />
        <input type="hidden" name="return" value="<?= $this->return; ?>" />
    </form>
    <div class="loader" style="display: none;">
        <img src="<?php echo JUri::root().'media/system/images/modal/spinner.gif'; ?>" />
        <?php echo JText::_('COM_STEAMID_OPENID_TRANSACTION_IN_PROGRESS'); ?>
    </div>
</div>
<script type="text/javascript">
    jQuery('#steamlogin_form').on('submit', function() {
        jQuery(this).hide();
        jQuery("#steamlogin > .loader").show();

        jQuery.ajax({
            type: "POST",
            url: this.action,
            data: jQuery(this).serialize(),
            success: function(data) {
                var module = jQuery("#steamlogin", jQuery(data));
                jQuery("#steamlogin").replaceWith(module);
                jQuery("#openid_message", module).submit();
            }
        })

        return false;
    });
</script> 
