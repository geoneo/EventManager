<?php
class SluggableBehavior extends ModelBehavior {
/**
 * Initiate behavior for the model using specified settings. Available settings:
 *
 * - label: 	(array | string, optional) set to the field name that contains the
 * 				string from where to generate the slug, or a set of field names to
 * 				concatenate for generating the slug. DEFAULTS TO: name
 *
 * - real:		(boolean, optional) if set to true then field names defined in
 * 				label must exist in the database table. DEFAULTS TO: true
 *
 * - slug:		(string, optional) name of the field name that holds generated slugs.
 * 				DEFAULTS TO: slug
 *
 * - separator:	(string, optional) separator character / string to use for replacing
 * 				non alphabetic characters in generated slug. DEFAULTS TO: _
 *
 * - length:	(integer, optional) maximum length the generated slug can have.
 * 				DEFAULTS TO: 100
 *
 * - overwrite: (boolean, optional) set to true if slugs should be re-generated when
 * 				updating an existing record. DEFAULTS TO: false
 *
 * - ignore:    (array, optional) array of words that should not be part of a slug.
 *
 * - conditions: (array, optional) array of custom conditions to use when looking for duplicate slugs
 *
 * @param object $Model Model using the behaviour
 * @param array $settings Settings to override for model.
 */
	public function setup(&$Model, $settings = array()) {
		$default = array(
			'real' => true,
			'label' => array('name'),
			'slug' => 'slug',
			'separator' => '_',
			'length' => 100,
			'overwrite' => false,
			'ignore' => array(),
			'conditions' => array()
		);
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = $default;
		}
		if (!is_array($settings)) {
			$settings = array();
		}
		$this->settings[$Model->alias] = array_merge($this->settings[$Model->alias], $settings);
	}

/**
 * Run before a model is saved, used to set up slug for model.
 *
 * @param object $Model Model about to be saved.
 * @return mixed False if the operation should abort. Any other result will continue.
 */
	public function beforeSave(&$Model) {
		$return = parent::beforeSave($Model);
		$settings = $this->settings[$Model->alias];
		$fields = (array) $settings['label'];

		if ($settings['real']) {
			// Check that all label fields exist
			foreach ($fields as $field) {
				if (!$Model->hasField($field)) {
					return $return;
				}
			}
		}

		if ((!$settings['real'] || $Model->hasField($settings['slug'])) && ($settings['overwrite'] || empty($Model->id))) {
			// Concat the contents of all label fields into $label
			$label = '';
			foreach ($fields as $field) {
				if (!empty($Model->data[$Model->alias][$field])) {
					$label .= (!empty($label) ? ' ' : '' ) . $Model->data[$Model->alias][$field];
				}
			}

			if (!empty($label)) {
				// Generate slug based on $label
				$slug = strtolower(Inflector::slug($label, $settings['separator']));

				// Look for similar slugs already in the model
				$conditions['OR'] = array(
					$Model->alias . '.' . $settings['slug'] => $slug,
					$Model->alias . '.' . $settings['slug'] . ' REGEXP ' => $slug . $settings['separator'] . '[0-9]+$'
				);
				if (!empty($Model->id)) {
					$conditions['NOT'] = array($Model->alias . '.' . $Model->primaryKey => $Model->id);
				}
				if (!empty($settings['conditions'])) {
					// Maybe this can be better achieved with cake's Set::merge
					$conditions = array_merge_recursive($conditions, $settings['conditions']);
				}

				$result = $Model->find('all', array(
					'conditions' => $conditions,
					'fields' => array($Model->primaryKey, $settings['slug']),
					'recursive' => -1
				));
				if (!empty($result)) {
					$sameSlugs = Set::extract('/' . $Model->alias . '/' . $settings['slug'], $result);
					foreach ($sameSlugs as $ss) {
						preg_match('/' . preg_quote($settings['separator']) . '([0-9]+)$/', $ss, $matches);
						$slugIndexes[] = !empty($matches[1]) ? $matches[1] : 0;
					}
					$slug .= $settings['separator'].(max($slugIndexes)+1);
				}

				$Model->data[$Model->alias][$settings['slug']] = $slug;
				$this->_addToWhitelist($Model, $settings['slug']);
			}
		}

		return $return;
	}
}
