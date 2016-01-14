<?php
/**
 * ContentComment Helper
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppHelper', 'View/Helper');

/**
 * ContentComment Helper
 *
 * @package NetCommons\ContentComments\View\Helper
 */
class ContentCommentHelper extends AppHelper {

/**
 * Other helpers used by FormHelper
 *
 * @var array
 */
	public $helpers = array(
		'Html',
	);

/**
 * コメント数表示
 *
 * コメント数の表示HTMLを返します。<br>
 * 設定データ配列、コンテンツデータ配列を指定してください。<br>
 * 設定データ配列のuse_commentを判断し、コンテンツデータ配列のContentCommentCnt.cntを表示します。
 *
 * #### Sample code
 * ##### template file(ctp file)
 * ```
 * <?php echo $this->ContentComment->cnt($videoBlockSetting, $video); ?>
 * ```
 *
 * @param array $setting Array of use comment setting data.
 * @param array $content Array of content data with ContentComment count.
 * @param array $attributes Array of attributes and HTML arguments.
 * @return string HTML tags
 */
	public function count($setting, $content, $attributes = array()) {
		$output = '';

		//いいね
		if (isset($setting['use_comment']) && $setting['use_comment']) {
			// <span class="glyphicon glyphicon-comment" aria-hidden="true"></span> <?php echo $video['ContentCommentCnt']['cnt'];
			$element = '<span class="glyphicon glyphicon-comment" aria-hidden="true"></span> ';
			$element .= (int)Hash::get($content, 'ContentCommentCnt.cnt');
			// <span style="padding-right: 15px;">
			$style = array('style' => 'padding-right: 15px;');
			$attributes = Hash::merge($attributes, $style);

			// http://book.cakephp.org/2.0/ja/core-libraries/helpers/html.html#HtmlHelper::tag
			$output .= $this->Html->tag('span', $element, $attributes);
		}

		return $output;
	}

/**
 * Outputs room roles radio
 *
 * @param string $fieldName Name attribute of the RADIO
 * @param array $attributes The HTML attributes of the select element.
 * @return string Formatted RADIO element
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#options-for-select-checkbox-and-radio-inputs
 */
	public function checkboxBlockRolePermission($fieldName, $attributes = array()) {
		list($model, $permission) = explode('.', $fieldName);
		$html = '';

		if (! isset($this->_View->request->data[$model][$permission])) {
			return $html;
		}

		$html .= '<div class="form-inline">';
		foreach ($this->_View->request->data[$model][$permission] as $roleKey => $role) {
			if (! $role['value'] && $role['fixed']) {
				continue;
			}

			$html .= '<span class="checkbox-separator"></span>';
			$html .= '<div class="form-group">';
			if (! $role['fixed']) {
				$html .= $this->Form->hidden($fieldName . '.' . $roleKey . '.id');
				$html .= $this->Form->hidden($fieldName . '.' . $roleKey . '.roles_room_id');
				$html .= $this->Form->hidden($fieldName . '.' . $roleKey . '.block_key');
				$html .= $this->Form->hidden($fieldName . '.' . $roleKey . '.permission');
			}

			$options = Hash::merge(array(
				'div' => false,
				'disabled' => (bool)$role['fixed']
			), $attributes);
			if (! $options['disabled']) {
				$options['ng-click'] = 'clickRole($event, \'' . $permission . '\', \'' . Inflector::variable($roleKey) . '\')';
			}
			$html .= $this->Form->checkbox($fieldName . '.' . $roleKey . '.value', $options);

			$html .= $this->Form->label($fieldName . '.' . $roleKey . '.value', h($this->_View->viewVars['roles'][$roleKey]['name']));
			$html .= '</div>';
		}
		$html .= '</div>';

		return $html;
	}

}
