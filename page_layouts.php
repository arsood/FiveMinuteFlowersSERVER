<?php require_once("connection.php"); ?>

<?php

$path_to_flowers = "img/flowers/";

?>

<?php 

### Get index occasions ###

if ($_GET["action"] == "read" && $_GET["page-layout"] == "index-categories") { 

$index_categories_query = "SELECT * FROM flower_cats ORDER BY occasion ASC";
$index_categories_process = mysql_query($index_categories_query);

?>

<li data-role="list-divider" data-theme="e" role="heading" class="ui-li ui-li-divider ui-bar-e ui-first-child">Browse By Occasion</li>

<?php 

//Get row information and set up counter to display last-child css class

$rows = mysql_num_rows($index_categories_process);
$i = 0;

while($categories = mysql_fetch_array($index_categories_process)) { 

$i++;

?>

<li data-icon="plus" data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="div" data-iconpos="right" data-theme="c" class="ui-btn ui-btn-icon-right ui-li-has-arrow ui-li ui-li-has-thumb ui-btn-up-c <?php if ($i == $rows) { echo "ui-last-child"; } ?>">
	<div class="ui-btn-inner ui-li">
        <div class="ui-btn-text">
            <a class="ui-link-inherit" data-occasion="<?php echo $categories["occasion"]; ?>" onClick="setBrowsePage(<?php echo "'".$categories["occasion"]."'"; ?>);">
                <img src="<?php echo $path_to_flowers.$categories["img_link"]; ?>" class="ui-li-thumb" />
                <h1 class="ui-li-heading"><?php echo $categories["occasion"]; ?></h1>
                <p class="ui-li-desc"><?php echo $categories["description"]; ?></p>
            </a>
        </div>
    <span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span>
    </div>
</li>

<?php } /*End while loop*/ } /*End if statement*/ ?>

<?php

### Get occasion specific flowers ###

if ($_GET["action"] == "read" && $_GET["page-layout"] == "browse-images") {
	
$browse_image_query = "SELECT * FROM flower_inventory WHERE occasion = '{$_GET['category']}'";
$browse_image_process = mysql_query($browse_image_query);

while ($browser = mysql_fetch_array($browse_image_process)) {

?>

<div class="browse-image-block">
    <div class="browse-image-price">
        $<?php echo $browser["retail_price"]; ?>
    </div>
    <a onClick="setSpecificFlower(<?php echo "'".$browser["arrangement_code"]."'"; ?>);"><img src="<?php echo $path_to_flowers.$browser["arrangement_code"]."_low.jpg"; ?>" /></a>
</div>

<?php } } ?>


