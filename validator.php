<?php   
/* 
	Plugin Name: WP-Validator
	Plugin URI: http://www.jamierumbelow.net/php/wp-validator 
	Description: WP-Validator allows you to crawl your entire site and validate each page within, to see if it complies to the W3C's XHTML guidelines
	Author: Jamie Rumbelow
	Version: 1.0 
	Author URI: http://www.jamierumbelow.net 
*/  

$DO_VALIDATION = FALSE;

function validate_check_trigger()
{
	$validate = $_GET['validate'];
	
	if (isset($validate) && $validate == 1)
	{
		global $DO_VALIDATION;
		$DO_VALIDATION = TRUE;
	}
}

function index_site()
{
	global $wpdb;
	
	$posts = $wpdb->get_results("SELECT guid, post_title FROM ".$wpdb->posts." WHERE post_status = 'publish'");
	
	$handle = fopen(dirname(__FILE__) . "/index.txt", "w+");
	
	$write = serialize($posts);
	
	fwrite($handle, $write);
	fclose($handle);
}

function validate()
{
	$file = file_get_contents(dirname(__FILE__) . "/index.txt");
	$posts = unserialize($file);
	
	$results = array();

	foreach ($posts as $post) {
		
		$results[] = file_get_contents("http://validator.w3.org/check/?uri=".$post->guid);
		
	}	
	
	$invalid = array();
	$valid = array();
	
	foreach ($results as $num => $r) {
		
	  if (strpos($r, "<ol id=\"error_loop\">")):
		
		$invalid[] = array($posts[$num]->guid, $posts[$num]->post_title);
		
	  else:
	  
	  	$valid[] = array($posts[$num]->guid, $posts[$num]->post_title);
	  
	  endif;
		
	}
	
	$page = "<h2>".__("Valid Pages")."</h2>";
	
	if (!empty($valid)): 
	
		$page .= "<ol id=\"valid_pages\">";
			
			foreach ($valid as $post) {
				
				$page .= "<li>&quot;".$post[1]."&quot; ".__("is Valid!")." <a href=\"http://validator.w3.org/check/?uri=".$post[0]."\">Check</a>.</li>";
				
			}
		
		$page .= "</ol>";
	
	else:
	
		$page .= "<p>".__("There were no valid pages!")."</p>";
	
	endif;
	
	$page .= "<h2>".__("Invalid Pages")."</h2>";
	
	if (!empty($valid)): 
	
		$page .= "<ol id=\"invalid_pages\">";
			
			foreach ($invalid as $post) {
				
				$page .= "<li>&quot;".$post[1]."&quot; ".__("is invalid!")." <a href=\"http://validator.w3.org/check/?uri=".$post[0]."\">".__("Find out more")."</a>.</li>";
				
			}
		
		$page .= "</ol>";
	
	else:
	
		$page .= "<p>".__("There were no invalid pages! Well done!")."</p>";
	
	endif;
	
	echo($page);
	
}

function validate_admin()
{		
	
	include('validate_admin.php');
	
	if ($_GET['validate_step'] == '1')
	{
		index_site();	
		
	}
	
	if ($_GET['validate_step'] == '2')
	{
		validate();
	}
	
}

function validate_admin_actions() {  
	add_options_page("WP-Validator", "WP-Validator", 2, "WP-Validator", "validate_admin");  
}  
     
add_action('admin_menu', 'validate_admin_actions');  
add_action("init", "validate_check_trigger");