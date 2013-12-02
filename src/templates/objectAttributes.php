<?php
foreach ($columnNames as $columnName) {
    ?>

/**
* @var array
*/
private $<?php echo $columnName ?>AsArray = null;
<?php
}
