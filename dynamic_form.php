<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.zaigoinfotech.com/
 * @since             1.0.0
 * @package           Dynamic_form
 *
 * @wordpress-plugin
 * Plugin Name:       Dynamic Form
 * Plugin URI:        https://dynamic_form.com
 * Description:       Best Wordpress dynamic form plugin
 * Version:           1.0.0
 * Author:            Zaigo infotech
 * Author URI:        https://www.zaigoinfotech.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dynamic_form
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'DYNAMIC_FORM_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dynamic_form-activator.php
 */
function activate_dynamic_form() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dynamic_form-activator.php';
	Dynamic_form_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-dynamic_form-deactivator.php
 */
function deactivate_dynamic_form() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dynamic_form-deactivator.php';
	Dynamic_form_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dynamic_form' );
register_deactivation_hook( __FILE__, 'deactivate_dynamic_form' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-dynamic_form.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_dynamic_form() {

	$plugin = new Dynamic_form();
	$plugin->run();

}
run_dynamic_form();

// add_action('admin_menu', array( $this, 'addPluginAdminMenu' ), 9);    



function wpdocs_bartag_func( $atts ) {
	$atts = shortcode_atts(
		array(
			'id' => 'no',
			
		), $atts, 'Dynamic_Form' );
		$form_id=$atts['id'];
		$step_id='1';
		$formapi='https://private-anon-69110eff5f-zippyform.apiary-mock.com/form/dynamic-form/'.$form_id.'/fields/'.$step_id;
		
		$getdata=wp_remote_get($formapi);
		$response_code = wp_remote_retrieve_response_code( $getdata );
		$getbody =  wp_remote_retrieve_body( $getdata );
		$datas=json_decode($getbody,true);
		$alldata=$datas['data'];
		$data=$alldata['form']['name'];
		$steps=$alldata['steps'];
		if(200==$response_code){
				$output='<form id="regForm" action="">
		<h1>'.$data.'</h1>';
		
		foreach($steps as $step){
		
		$s_id=$step['id'];
		$form_api='https://private-anon-69110eff5f-zippyform.apiary-mock.com/form/dynamic-form/'.$form_id.'/fields/'.$s_id;
		
			$stepgetdata=wp_remote_get($form_api);
			$step_response_code = wp_remote_retrieve_response_code( $stepgetdata );
			$step_getbody =  wp_remote_retrieve_body( $stepgetdata );
			$step_datas=json_decode($step_getbody,true);
			$step_alldata=$step_datas['data'];
			$step_data=$step_alldata['fields'];
			$step_name=$step['name'];
			if(200==$step_response_code){

				$output .='<div class="tab"><h3>'.$step_name.'</3>';
			foreach($step_data as $step_datas){
				$type=$step_datas['field_type'];
				$placeholder =$step_datas['placeholder'];
				$label=$step_datas['label'];
				$class_name=$step_datas['class_name'];
				$field_id=$step_datas['field_id'];
				$required=$step_datas['validations']['required'];
				
				if(! empty($required)){
					$require= 'require';
				}else{
					$require= '';
				}
				
				switch ($type) {
					case "text_box":
						$output .= '<p><label>'.$label.'</label><input id="'.$field_id.'" type="text" placeholder="'.$placeholder.'" class="'.$require.' '.$class_name.'"></p>';
					  break;
					case "short_text_area":
						$output .= '<p><label>'.$label.'</label><textarea id="'.$field_id.'"  class="'.$require.' '.$class_name.'" >'.$placeholder.'</textarea></p>';
					  break;
					case "text_area":
						$output .= '<p><label>'.$label.'</label><textarea id="'.$field_id.'"  class="'.$require.' '.$class_name.'" >'.$placeholder.'</textarea></p>';
					  break;
					case "number":
						$output .= '<p><label>'.$label.'</label><input type="number" id="'.$field_id.'" placeholder="'.$placeholder.'" class="'.$require.' '.$class_name.'"></p>';
					  break;
					case "email":
						$output .= '<p><label>'.$label.'</label><input required type="email" id="'.$field_id.'" placeholder="'.$placeholder.'" class="'.$require.' '.$class_name.'"></p>';
					  break;
					case "website_url":
						$output .= '<p><label>'.$label.'</label><input type="url" id="'.$field_id.'" placeholder="'.$placeholder.'" class="'.$require.' '.$class_name.'"></p>';
					  break;
					case "dropdown":
						$option=$step_datas['options'];
						$output .= '<p><label>'.$label.'</label><select id="'.$field_id.'" class="'.$require.' '.$class_name.'">';
						foreach($option as $options){
							$output .='<option value="'.$options['value'].'">'.$options['label'].'</option>';
						}
					 
						$output .= '</select></p>';
					  break;
					case "multiselect_checkbox":
						$option=$step_datas['options'];
						$output .= '<p><label>'.$label.'</label><select multiple id="'.$field_id.'" class="'.$require.' '.$class_name.'">';
						foreach($option as $options){
							$output .='<option value="'.$options['value'].'">'.$options['label'].'</option>';
						}
					 
						$output .= '</select></p>';
					  break;
					case "radio":
						$option=$step_datas['options'];
						$output .= '<p><label>'.$label.'</label><br>';
						foreach($option as $options){
							$output .='<input type="radio" id="'.$field_id.'" name="'.$field_id.'" value="'.$options['value'].'"><label for="'.$options['value'].'">'.$options['label'].'</label><br>';
						}
						
					  break;
					case "date":
						$output .= '<p><label>'.$label.'</label><input type="date" id="'.$field_id.'" placeholder="'.$placeholder.'" class="'.$require.' '.$class_name.'"></p>';
					  break;
					case "time":
						$output .= '<p><label>'.$label.'</label><input type="time" id="'.$field_id.'" placeholder="'.$placeholder.'" class="'.$require.' '.$class_name.'"></p>';
					  break;
					case "file":
						$output .= '<p><label>'.$label.'</label><input type="file" id="'.$field_id.'" placeholder="'.$placeholder.'" class="'.$require.' '.$class_name.'"></p>';
					  break;
					default:
					  echo "";
				  }
		
			}
			}
			$output .='</div>';
		}
		
		$output .='<div style="overflow:auto;">
		  <div style="float:right;">
			<button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
			<button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
		  </div>
		</div>
		<!-- Circles which indicates the steps of the form: -->
		<div style="text-align:center;margin-top:40px;">';
		foreach($steps as $step){
		  $output.='<span class="step"></span>';
		}
		$output.='</div>
	  </form>';
		}
		elseif(400==$response_code){
			$output ='';
		}

	

	return $output;
}
add_shortcode( 'Dynamic_Form', 'wpdocs_bartag_func' );


function hook_css() {
    ?>
        <style>

#regForm {
  background-color: #ffffff;
  margin: 100px auto;
  font-family: Raleway;
  padding: 40px;
  width: 70%;
  min-width: 300px;
}

h1 {
  text-align: center;  
}
#regForm p label{
	font-size: 18px;
}
#regForm p {
	margin: auto;
}
#regForm input,#regForm textarea,#regForm select {
  padding: 10px;
  width: 100%;
  font-size: 17px;
  font-family: Raleway;
  border: 1px solid #aaaaaa;
}
#regForm input[type="radio"]{
	width:unset !important;
}

/* Mark input boxes that gets an error on validation: */
#regForm input.invalid,#regForm textarea.invalid, #regForm select.invalid {
  background-color: #ffdddd;
}

/* Hide all steps by default: */
.tab {
  display: none;
}

button {
  background-color: #04AA6D;
  color: #ffffff;
  border: none;
  padding: 10px 20px;
  font-size: 17px;
  font-family: Raleway;
  cursor: pointer;
}

button:hover {
  opacity: 0.8;
}

#prevBtn {
  background-color: #bbbbbb;
}

/* Make circles that indicate the steps of the form: */
.step {
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbbbbb;
  border: none;  
  border-radius: 50%;
  display: inline-block;
  opacity: 0.5;
}

.step.active {
  opacity: 1;
}

/* Mark the steps that are finished and valid: */
.step.finish {
  background-color: #04AA6D;
}
</style>
    <?php
}
add_action('wp_head', 'hook_css');

function footer_script(){
	?>
	<script>
		
var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab

function showTab(n) {
  // This function will display the specified tab of the form...
  var x = document.getElementsByClassName("tab");
  x[n].style.display = "block";
  //... and fix the Previous/Next buttons:
  if (n == 0) {
    document.getElementById("prevBtn").style.display = "none";
  } else {
    document.getElementById("prevBtn").style.display = "inline";
  }
  if (n == (x.length - 1)) {
    document.getElementById("nextBtn").innerHTML = "Submit";
  } else {
    document.getElementById("nextBtn").innerHTML = "Next";
  }
  //... and run a function that will display the correct step indicator:
  fixStepIndicator(n)
}

function nextPrev(n) {
  // This function will figure out which tab to display
  var x = document.getElementsByClassName("tab");
  // Exit the function if any field in the current tab is invalid:
  if (n == 1 && !validateForm()) return false;
  // Hide the current tab:
  x[currentTab].style.display = "none";
  // Increase or decrease the current tab by 1:
  currentTab = currentTab + n;
  // if you have reached the end of the form...
  if (currentTab >= x.length) {
    // ... the form gets submitted:
    document.getElementById("regForm").submit();
    return false;
  }
  // Otherwise, display the correct tab:
  showTab(currentTab);
}

function validateForm() {
  // This function deals with validation of the form fields
  var x, y, i, valid = true;
  x = document.getElementsByClassName("tab");
  y = x[currentTab].getElementsByClassName("require");
  // A loop that checks every input field in the current tab:
  for (i = 0; i < y.length; i++) {
    // If a field is empty...
    if (y[i].value == "") {
      // add an "invalid" class to the field:
      y[i].className += " invalid";
      // and set the current valid status to false
      valid = false;
    }
  }
  // If the valid status is true, mark the step as finished and valid:
  if (valid) {
    document.getElementsByClassName("step")[currentTab].className += " finish";
  }
  return valid; // return the valid status
}

function fixStepIndicator(n) {
  // This function removes the "active" class of all steps...
  var i, x = document.getElementsByClassName("step");
  for (i = 0; i < x.length; i++) {
    x[i].className = x[i].className.replace(" active", "");
  }
  //... and adds the "active" class on the current step:
  x[n].className += " active";
}
</script>
	<?php
}

add_action('wp_footer', 'footer_script');

function dynamic_form_stylesheets(){
	wp_enqueue_script('dynamic_form1', 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js', array(), null, true);
	wp_enqueue_script('dynamic_form2', 'https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js', array(), null, true);
	wp_enqueue_script('dynamic_form3', 'https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js', array(), null, true);

}

add_action('init', 'dynamic_form_stylesheets');

