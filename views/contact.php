<?php

use app\core\form\Form;
use app\core\form\TextAreaField;

/**
 * @var \app\core\Model $model
 */
$this->title = "İletişim Formu";
?>

<h1>İletişim</h1>

<?php $form = \app\core\form\Form::begin('', 'post'); ?>
<?php echo $form->field($model, 'subject');  ?>
<?php echo $form->field($model, 'email');  ?>
<?php echo new TextAreaField($model, 'body');  ?>
<button type="submit" class="btn btn-primary">Submit</button>
<?php Form::end(); ?>