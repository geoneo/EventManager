<?=$this->Form->create('Event', array('type' => 'file'));?>
<?php
$this->Html->scriptBlock(
<<<JS
$(function() {
	$('input.input-radio-photo').click(function() {
		if (this.value == 1) {
			$('#divPhoto, #photoPreview').fadeIn(200);
		} else {
			$('#divPhoto, #photoPreview').fadeOut(100);
			$('#EventPhoto').val('');
		}
	});
});
JS
, array('inline' => false));
?>
<fieldset>
	<div class="tabs">
		<ul>
			<li><a href="#node-main"><span><?=__d('event_manager', 'Event')?></span></a></li>
		</ul>
		<div id="node-main">
		<?php
			echo $this->Form->input('title', array('label' => __('Title')));
			echo $this->Form->input('date', array('label' => __('Date')));
			echo $this->Form->input('body', array('label' => __('Description'), 'class' => 'content'));
			echo $this->Form->input('with_photo', array(
				'type' => 'radio', 'legend' => 'Foto',
				'options' => array(
					1 => 'Si',
					0 => 'No'
				),
				'div' => array('class' => 'input radio clearfix'),
				'value' => empty($this->data['Event']['with_photo']) ? 0 : $this->data['Event']['with_photo'],
				'class' => 'input-radio-photo'
			));
			if ($this->params['action'] == 'admin_add') {
				echo $this->Form->input('photo', array('type' => 'file', 'label' => 'Foto', 'div' => array('id' => 'divPhoto', 'style' => 'display: none;')));
			} else {
				if (!empty($this->data['Event']['photo']) && is_string($this->data['Event']['photo']) && file_exists(PATH_FULL_UPLOAD . $this->data['Event']['photo'])) {
					$options = array('class' => 'input', 'id' => 'photoPreview');
					if (empty($this->data['Event']['with_photo'])) {
						$options['style'] = 'display: none;';
					}
					echo $this->Html->tag(
						'div',
						$this->Image->resize(PATH_UPLOAD . $this->data['Event']['photo'], 120, 80, true, array(), true),
						$options
					);
				}
				$options = array('type' => 'file', 'label' => 'Foto', 'div' => array('id' => 'divPhoto'));
				if (empty($this->data['Event']['with_photo'])) {
					$options['div']['style'] = 'display: none';
				}
				echo $this->Form->input('photo', $options);
				echo $this->Form->input('photo_old', array('type' => 'hidden', 'value' => $this->data['Event']['photo']));
			}
			echo $this->Form->input('status', array(
				'label' => __('Published'),
				'checked' => 'checked',
			));
		?>
		</div>
	</div>
	</fieldset>
	<div class="buttons">
	<?php
		echo $this->Form->submit(__('Apply'), array('name' => 'apply'));
		echo $this->Form->submit(__('Save'));
		echo $this->Html->link(__('Cancel'), array(
			'action' => 'index',
		), array(
			'class' => 'cancel',
		));
	?>
	</div>
<?php echo $this->Form->end(); ?>
