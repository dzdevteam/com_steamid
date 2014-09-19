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
<?php if ($this->error) : ?>
    <?php echo $this->loadTemplate('error'); ?>
<?php elseif ($this->try_auth) : ?>
    <?php echo $this->loadTemplate('login'); ?>
<?php else : ?>
    <?php echo $this->loadTemplate('form'); ?>
<?php endif; ?>
