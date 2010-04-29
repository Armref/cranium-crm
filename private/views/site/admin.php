<h3>Site Administration</h3>

<ul>
<?php
foreach($controllers AS $k=>$v) {
    ?>
    <li><?php echo CHtml::link($v,array($k.'/admin')); ?></li>
    <?php
}
?>
</ul>