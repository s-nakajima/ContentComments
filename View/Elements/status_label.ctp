<?php
/**
 * status_label element template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

$labels = [
	ContentComment::STATUS_APPROVED => [
		'class' => 'label-warning',
		'message' => __d('content_comments', 'Approving'),
	],
];
$label = isset($labels[$status]) ? $labels[$status] : null;
?>

<?php if ($label): ?>
	<span class="label <?php echo $labels[$status]['class'] ?>"><?php echo $labels[$status]['message'] ?></span>
<?php endif;
