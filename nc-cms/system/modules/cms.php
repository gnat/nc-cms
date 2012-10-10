<?php
//
//	nc_get_cp_css_directory
//
//	Generate code for css include for the control panel..
//
function nc_get_cp_css_directory ($return = false)
{
	$output = nc_get_cms_path_relative().'/system/css/cp.css';
	
	if($return)
		return $output;
	else
		echo $output;
}
//
//	nc_get_cp
//
//	Generate code for the editor panel. Will only run if $login_state is true (the user is logged in).
//
function nc_get_cp ($return = false)
{
	global $nc_login_state, $nc_page_title, $nc_language;

	if($nc_login_state)
	{
		$output = '
		<div class="nc_cp">
			<div class="nc_logo">v'.NC_VERSION.'</div>
			<span class="nc_button nc_button_push_right"><a href="'.nc_get_cms_path_relative().'/index.php?action=logout"><span class="nc_icon nc_icon_logout">Log Out</span></a></span>';
			if($nc_page_title != '') 
				$output .= ' <span class="nc_button nc_button_push_right"><a href="'.nc_get_cms_path_relative().'/index.php?action=edit_string&amp;name='.$nc_page_title.'"><span class="nc_icon nc_icon_title">Edit Page Title</span></a></span>';
		$output .= '
			<div class="clear"></div>
		</div>';
		
		if($return)
			return $output;
		else
			echo $output;
	}
}
//
//	nc_content_string
//
//	Generate code for a string area. The string area's unique identifier is passed to $name.
//
function nc_content_string ($name, $return = false)
{
	global $nc_login_state;
	
	$output = nc_load_create_content($name); // Output the content we have prepared to webpage
	
	if($nc_login_state) // Edit mode is on, load the editor
		$output .=  '<a href="'.nc_get_cms_path_relative().'/index.php?action=edit_string&amp;name='.$name.'" class="nc_edit"><img src="'.nc_get_cms_path_relative().'/system/images/edit.png" alt="Edit" title="Edit" border="0"/></a>';
	
	if($return)
		return $output;
	else
		echo $output;
}
//
//	nc_content_html
//
//	Generate code for an html area. The html area's unique identifier is passed to $name.
//
function nc_content_html ($name, $return = false)
{
	global $nc_login_state;
	
	$output =  nc_load_create_content($name); // Output the content we have prepared to webpage
	
	if($nc_login_state) // Edit mode is on, load the editor
		$output .= '<a href="'.nc_get_cms_path_relative().'/index.php?action=edit_html&amp;name='.$name.'" class="nc_edit"><img src="'.nc_get_cms_path_relative().'/system/images/edit.png" alt="Edit" title="Edit" border="0"/></a>';

	if($return)
		return $output;
	else
		echo $output;
}
//
//	nc_title
//
//	Generate code for the page title.
//
function nc_title ($name, $return = false)
{
	global $nc_page_title;
	
	$nc_page_title = $name;
	$output = nc_load_create_content($name);
	
	if($return)
		return $output;
	else
		echo $output;
}
//
//	nc_login_link
//
//	Generate code for a nice little log in link.
//
function nc_login_link ($return = false)
{
	global $nc_login_state;
	
	if($nc_login_state)
		$output = '';
	else
		$output = '<a href="'.nc_get_cms_path_relative().'" class="nc_login_link"><img src="'.nc_get_cms_path_relative().'/system/images/key.png" alt="Log In" title="Log In" border="0"/></a>';
	
	if($return)
		return $output;
	else
		echo $output;
}
