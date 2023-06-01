<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.zaigoinfotech.com/
 * @since      1.0.0
 *
 * @package    Dynamic_form
 * @subpackage Dynamic_form/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php 
$t=wp_remote_get('https://private-anon-69110eff5f-zippyform.apiary-mock.com/form/dynamic-form/list');
$res =  wp_remote_retrieve_body( $t );
$datas=json_decode($res,true);
$d=$datas['data']['list']['data'];

// echo '<pre>';
// print_r($d);

?>
<div class="wrap">
		        <div id="icon-themes" class="icon32"></div>  
		        <h2>Contact Forms</h2>  
		         <!--NEED THE settings_errors below so that the errors/success messages are shown after submission - wasn't working once we started using add_menu_page and stopped using add_options_page so needed this-->
                 <table class="wp-list-table widefat fixed striped table-view-list">
  
  <thead>
    <tr>
      <th scope="col">Form Name</th>
      <th scope="col">Shortcode</th>
     
    </tr>
  </thead>
  <tbody>
   <?php foreach( $d as $dd){
    ?>
    <tr>
      <td data-label="Name"><?php echo $dd['name']; ?></td>
      <td data-label="Email"><?php echo '[Dynamic_Form id="'.$dd['id'].'"]';?></td>
     
    </tr>
   <?php } ?>
  </tbody>
</table>			
</div>

