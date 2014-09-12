<?php
/**
 * @version     1.0.0
 * @package     com_steamid
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      DZ Dev <herophuong93@gmail.com> - http://dezign.vn
 */
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_steamid/assets/css/steamid.css');
?>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function() {
        
    js('input:hidden.user_id').each(function(){
        var name = js(this).attr('name');
        if(name.indexOf('user_idhidden')){
            js('#jform_user_id option[value="'+js(this).val()+'"]').attr('selected',true);
        }
    });
    js("#jform_user_id").trigger("liszt:updated");
    });

    Joomla.submitbutton = function(task)
    {
        if (task == 'steamid.cancel') {
            Joomla.submitform(task, document.getElementById('steamid-form'));
        }
        else {
            
            if (task != 'steamid.cancel' && document.formvalidator.isValid(document.id('steamid-form'))) {
                
                Joomla.submitform(task, document.getElementById('steamid-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_steamid&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="steamid-form" class="form-validate">

    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_STEAMID_TITLE_STEAMID', true)); ?>
        <div class="row-fluid">
            <div class="span10 form-horizontal">
                <fieldset class="adminform">

                                <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('id'); ?></div>
            </div>
                <input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
                <input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
                <input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
                <input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

                <?php if(empty($this->item->created_by)){ ?>
                    <input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />

                <?php } 
                else{ ?>
                    <input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />

                <?php } ?>          <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('steamid'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('steamid'); ?></div>
            </div>
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('user_id'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('user_id'); ?></div>
            </div>

            <?php
                foreach((array)$this->item->user_id as $value): 
                    if(!is_array($value)):
                        echo '<input type="hidden" class="user_id" name="jform[user_idhidden]['.$value.']" value="'.$value.'" />';
                    endif;
                endforeach;
            ?>          <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('personaname'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('personaname'); ?></div>
            </div>
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('realname'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('realname'); ?></div>
            </div>
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('profileurl'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('profileurl'); ?></div>
            </div>
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('avatar'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('avatar'); ?></div>
            </div>


                </fieldset>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        
        <?php if (JFactory::getUser()->authorise('core.admin','steamid')) : ?>
    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL', true)); ?>
        <?php echo $this->form->getInput('rules'); ?>
    <?php echo JHtml::_('bootstrap.endTab'); ?>
<?php endif; ?>

        <?php echo JHtml::_('bootstrap.endTabSet'); ?>

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>