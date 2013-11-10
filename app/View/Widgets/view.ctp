<div class="widgets view">
<h2><?php echo __('Widget'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($widget['Widget']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($widget['User']['id'], array('controller' => 'users', 'action' => 'view', $widget['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Url'); ?></dt>
		<dd>
			<?php echo h($widget['Widget']['url']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Articles to display'); ?></dt>
		<dd>
			<?php echo h($widget['Widget']['nr_of_articles']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __(' Order'); ?></dt>
		<dd>
			<?php echo h($widget['Widget']['_order']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __(' Column'); ?></dt>
		<dd>
			<?php echo h($widget['Widget']['_column']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Widget'), array('action' => 'edit', $widget['Widget']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Widget'), array('action' => 'delete', $widget['Widget']['id']), null, __('Are you sure you want to delete # %s?', $widget['Widget']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Widgets'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Widget'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
