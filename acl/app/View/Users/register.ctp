<?php echo $this->Html->css('cake.generic');?>
<div class="users form">
<?php echo $this->Form->create('User'); ?>
<fieldset>
	<legend><?php echo __('Register'); ?></legend>
	<?php
	echo $this->Form->input('first_name');
	echo $this->Form->input('last_name');
	echo $this->Form->input('email');
	echo $this->Form->input('username');
	echo $this->Form->input('password', array('type' => 'password'));
	echo $this->Form->input('password_confirm', array('type' => 'password'));
	?>
</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>

