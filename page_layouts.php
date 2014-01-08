<?php require_once("connection.php"); ?>

<?php

$path_to_flowers = "img/flowers/";

$current_month = date("m", time());

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
	
$browse_image_query = "SELECT * FROM flower_inventory WHERE occasion = '{$_GET['category']}' ORDER BY retail_price DESC";
$browse_image_process = mysql_query($browse_image_query);

while ($browser = mysql_fetch_array($browse_image_process)) {

$avail = explode("+", $browser["avail"]);

if (in_array($current_month, $avail) || $browser["avail"] == "all") {

?>

<div class="browse-image-block">
    <div class="browse-image-price">
        $<?php echo $browser["retail_price"]; ?>
    </div>
    <a onClick="setSpecificFlower(<?php echo "'".$browser["arrangement_code"]."'"; ?>);"><img src="<?php echo $path_to_flowers.$browser["arrangement_code"]."_low.jpg"; ?>" /></a>
</div>

<?php } } } ?>

<?php

### Get saved recipients for wizard page ###

if (isset($_POST["method"]) && $_POST["method"] == "read") {
	if ($_POST["action"] == "get-recipients") {
		$user_id = mysql_real_escape_string($_POST["uuid"]);
		$recipient_query = "SELECT * FROM saved_delivery WHERE uuid = '{$user_id}'";
		$recipient_process = mysql_query($recipient_query);
?>

<?php if (mysql_num_rows($recipient_process) > 0) { ?>
<ul class="recipient-list" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="Search For Names">
<?php } ?>
    
<?php while ($recipients = mysql_fetch_array($recipient_process)) { ?>
    <li><a href="#" onClick="setRec('<?php echo $recipients["delivery_first_name"]." ".$recipients["delivery_last_name"]; ?>');"><?php echo $recipients["delivery_first_name"]." ".$recipients["delivery_last_name"]; ?></a></li>
<?php } //Close while loop ?>

</ul>

<?php } /*Close condition that if action equals get-recipients*/ } /*Close condition that method has to be read*/ ?>

<?php

### Get saved recipients for account page ###

if (isset($_POST["method"]) && $_POST["method"] == "read") {
	if ($_POST["action"] == "get-account-recipients") {
		$user_id = mysql_real_escape_string($_POST["uuid"]);
		$recipient_query = "SELECT * FROM saved_delivery WHERE uuid = '{$user_id}'";
		$recipient_process = mysql_query($recipient_query);
?>

<?php if (mysql_num_rows($recipient_process) > 0) { ?>
<ul data-role="listview" data-inset="true" data-theme="c">
<li data-role="list-divider" data-theme="b">
    Saved Recipients (tap to remove)
</li>
<?php } ?>

<?php while ($recipients = mysql_fetch_array($recipient_process)) { ?>
<li id="saved-rec-<?php echo $recipients['id']; ?>"><a href="#" onClick="removeRec('<?php echo $recipients['id']; ?>');"><?php echo $recipients["delivery_first_name"]." ".$recipients["delivery_last_name"]; ?></a></li>
<?php } ?>

</ul>

<?php } /*Close condition that if action equals get-recipients*/ } /*Close condition that method has to be read*/ ?>

<?php

### Get saved billing for account page ###

if (isset($_POST["method"]) && $_POST["method"] == "read") {
	if ($_POST["action"] == "get-account-billing") {
		$user_id = mysql_real_escape_string($_POST["uuid"]);
		$billing_query = "SELECT * FROM saved_billing WHERE uuid = '{$user_id}'";
		$billing_process = mysql_query($billing_query);
?>

<?php if (mysql_num_rows($billing_process) > 0) { ?>
<ul data-role="listview" data-inset="true" data-theme="c">
<li data-role="list-divider" data-theme="e">
    Saved Billing Information (tap to remove)
</li>
<?php } ?>

<?php while ($billings = mysql_fetch_array($billing_process)) { ?>
<li id="saved-bill-<?php echo $billings['id']; ?>"><a href="#" onClick="removeBill('<?php echo $billings['id']; ?>');"><?php echo $billings["billing_address_1"].", ".$billings["billing_city"].", ".$billings["billing_state"]; ?></a></li>
<?php } ?>

</ul>

<?php } /*Close condition that if action equals get-recipients*/ } /*Close condition that method has to be read*/ ?>


