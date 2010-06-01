<?php
/**
 * @todo Add support for generating elements w/out $form variable
 */
$displayField = function($content) {
	return '
<div class="row">
	' . $content . '
</div>
	';
};

echo $this->buildForm($model, $form, $displayField);