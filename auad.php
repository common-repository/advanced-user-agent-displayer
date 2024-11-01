<?php

	/*
	Plugin Name: Advanced User Agent Displayer
	Plugin URI: http://www.moallemi.ir/en/blog/2009/09/20/advanced-user-agent-displayer/
	Description: Shows user agent information to your blog comments by adding  browser and platform icons.
	Version: 2.7.5.2
	Author: Reza Moallemi
	Author URI: http://www.moallemi.ir/blog
	*/

	load_plugin_textdomain('advanced-user-agent-displayer', NULL, dirname(plugin_basename(__FILE__)) . "/languages");

	add_action('admin_menu', 'auad_menu');

	function auad_menu() 
	{
		add_options_page('Advanced User Agent Displayer Options', __('Advanced User Agent', 'advanced-user-agent-displayer'), 8, 'advanced-user-agent-displayer', 'auad_options');
	}

	function get_auad_options()
	{
		$auad_options = array('post_icon_size' => '16',
								'post_show_browser' => 'true',
								'post_show_platform' => 'true',
								'general_show_unknown' => 'false',
								'post_location' => 'pl_before',
								'show_in_dashboard' => 'true');
		$auad_save_options = get_option('auad_options');
		if (!empty($auad_save_options))
		{
			foreach ($auad_save_options as $key => $option)
			$auad_options[$key] = $option;
		}
		update_option('auad_options', $auad_options);
		return $auad_options;
	}

	function auad_options()
	{
		$auad_options = get_auad_options();
		if (isset($_POST['update_auad_settings']))
		{
			$auad_options['post_icon_size'] = isset($_POST['post_icon_size']) ? $_POST['post_icon_size'] : '16';
			$auad_options['post_show_browser'] = isset($_POST['post_show_browser']) ? $_POST['post_show_browser'] : 'false';
			$auad_options['post_show_platform'] = isset($_POST['post_show_platform']) ? $_POST['post_show_platform'] : 'false';
			$auad_options['general_show_unknown'] = isset($_POST['general_show_unknown']) ? $_POST['general_show_unknown'] : 'false';
			$auad_options['post_location'] = isset($_POST['post_location']) ? $_POST['post_location'] : 'pl_before';
			$auad_options['show_in_dashboard'] = isset($_POST['show_in_dashboard']) ? $_POST['show_in_dashboard'] : 'false';

			update_option('auad_options', $auad_options);
			?>
			<div class="updated">
				<p><strong><?php _e("Settings Saved.","advanced-user-agent-displayer");?></strong></p>
			</div>
			<?php
		} ?>
		<div class=wrap>
		<?php if(function_exists('screen_icon')) screen_icon(); ?>
			<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
				<h2><?php _e('Advanced User Agent Displayer Settings', 'advanced-user-agent-displayer'); ?></h2>
				<h3><?php _e('General Options:', 'advanced-user-agent-displayer'); ?></h3>
				<p>
					<input name="general_show_unknown" value="true" 
							type="checkbox" <?php if ($auad_options['general_show_unknown'] == 'true' ) echo ' checked="checked" '; ?> />
							<?php _e('Hide ', 'advanced-user-agent-displayer'); ?> 
							<img src="<?php echo get_option('siteurl').'/wp-content/plugins/'.plugin_basename(dirname(__FILE__));?>/img/24/os/unknown.png" alt="unknown" title="unknown">
							<?php _e(' icons if the browser and user agent are unknown.', 'advanced-user-agent-displayer'); ?>
				</p>
				<h3><?php _e('Dashboard Options:', 'advanced-user-agent-displayer'); ?></h3>
				<p><input name="show_in_dashboard" value="true" type="checkbox" <?php if ($auad_options['show_in_dashboard'] == 'true' ) echo ' checked="checked" '; ?> /> <?php _e('Show browser and platform icons in the dashboard comments.', 'advanced-user-agent-displayer'); ?></p>
				<h3><?php _e('Post Options:', 'advanced-user-agent-displayer'); ?></h3>
				<p><?php _e('Display location:', 'advanced-user-agent-displayer'); ?> 
								<select name="post_location">
								  <option value="pl_author" <?php if ($auad_options['post_location'] == 'pl_author' ) echo ' selected="selected" '; ?> ><?php _e('After comment author name', 'advanced-user-agent-displayer'); ?></option>
								  <option value="pl_date" <?php if ($auad_options['post_location'] == 'pl_date' ) echo ' selected="selected" '; ?> ><?php _e('After comment date', 'advanced-user-agent-displayer'); ?></option>
								  <option value="pl_before" <?php if ($auad_options['post_location'] == 'pl_before' ) echo ' selected="selected" '; ?> ><?php _e('Before comment text', 'advanced-user-agent-displayer'); ?></option>
								  <option value="pl_after" <?php if ($auad_options['post_location'] == 'pl_after' ) echo ' selected="selected" '; ?> ><?php _e('After comment text', 'advanced-user-agent-displayer'); ?></option>
								</select>
				</p>
				<p><?php _e('Icon size:', 'advanced-user-agent-displayer'); ?> 
								<select name="post_icon_size">
								  <option value="16" <?php if ($auad_options['post_icon_size'] == '16' ) echo ' selected="selected" '; ?> >16 px</option>
								  <option value="18" <?php if ($auad_options['post_icon_size'] == '18' ) echo ' selected="selected" '; ?> >18 px</option>
								  <option value="20" <?php if ($auad_options['post_icon_size'] == '20' ) echo ' selected="selected" '; ?> >20 px</option>
								  <option value="22" <?php if ($auad_options['post_icon_size'] == '22' ) echo ' selected="selected" '; ?> >22 px</option>
								  <option value="24" <?php if ($auad_options['post_icon_size'] == '24' ) echo ' selected="selected" '; ?> >24 px</option>
								</select>
				</p>
				<p><input name="post_show_browser" value="true" type="checkbox" <?php if ($auad_options['post_show_browser'] == 'true' ) echo ' checked="checked" '; ?> /> <?php _e('Show browser icon.', 'advanced-user-agent-displayer'); ?></p>
				<p><input name="post_show_platform" value="true" type="checkbox" <?php if ( $auad_options['post_show_platform'] == 'true' ) echo ' checked="checked" '; ?> /> <?php _e('show platform icon.', 'advanced-user-agent-displayer'); ?></p>
				<div class="submit">
					<input class="button-primary" type="submit" name="update_auad_settings" value="<?php _e('Save Changes', 'advanced-user-agent-displayer'); ?>" />
				</div>
				<hr />
				<div>
					<h4><?php _e('My other plugins for wordpress:', 'advanced-user-agent-displayer'); ?></h4>
					<ul>
						<li><b><font color="red">- <?php _e('Google Reader Stats ', 'advanced-user-agent-displayer'); ?></font></b>
							(<a href="http://wordpress.org/extend/plugins/google-reader-stats/"><?php _e('Download', 'advanced-user-agent-displayer'); ?></a> | 
							<a href="<?php _e('http://www.moallemi.ir/en/blog/2010/06/03/google-reader-stats-for-wordpress/', 'advanced-user-agent-displayer'); ?>"><?php _e('More Information', 'advanced-user-agent-displayer'); ?></a>)
						</li>
						<li><b>- <?php _e('Likekhor ', 'advanced-user-agent-displayer'); ?></b>
							(<a href="http://wordpress.org/extend/plugins/wp-likekhor/"><?php _e('Download', 'advanced-user-agent-displayer'); ?></a> | 
							<a href="<?php _e('http://www.moallemi.ir/blog/1389/04/30/%D9%85%D8%B9%D8%B1%D9%81%DB%8C-%D8%A7%D9%81%D8%B2%D9%88%D9%86%D9%87-%D9%84%D8%A7%DB%8C%DA%A9-%D8%AE%D9%88%D8%B1-%D9%88%D8%B1%D8%AF%D9%BE%D8%B1%D8%B3/', 'google-reader-stats'); ?>"><?php _e('More Information', 'advanced-user-agent-displayer'); ?></a>)
						</li>
						<li><b>- <?php _e('Google Transliteration ', 'advanced-user-agent-displayer'); ?></b>
							(<a href="http://wordpress.org/extend/plugins/google-transliteration/"><?php _e('Download', 'advanced-user-agent-displayer'); ?></a> | 
							<a href="<?php _e('http://www.moallemi.ir/en/blog/2009/10/10/google-transliteration-for-wordpress/', 'advanced-user-agent-displayer'); ?>"><?php _e('More Information', 'advanced-user-agent-displayer'); ?></a>)
						</li>
						<li><b>- <?php _e('Behnevis Transliteration ', 'advanced-user-agent-displayer'); ?></b> 
							(<a href="http://wordpress.org/extend/plugins/behnevis-transliteration/"><?php _e('Download', 'advanced-user-agent-displayer'); ?></a> | 
							<a href="http://www.moallemi.ir/blog/1388/07/25/%D8%A7%D9%81%D8%B2%D9%88%D9%86%D9%87-%D9%86%D9%88%DB%8C%D8%B3%D9%87-%DA%AF%D8%B1%D8%AF%D8%A7%D9%86-%D8%A8%D9%87%D9%86%D9%88%DB%8C%D8%B3-%D8%A8%D8%B1%D8%A7%DB%8C-%D9%88%D8%B1%D8%AF%D9%BE%D8%B1%D8%B3/"><?php _e('More Information', 'advanced-user-agent-displayer'); ?></a> )
						</li>
						<li><b>- <?php _e('Comments On Feed ', 'advanced-user-agent-displayer'); ?></b> 
							(<a href="http://wordpress.org/extend/plugins/comments-on-feed/"><?php _e('Download', 'advanced-user-agent-displayer'); ?></a> | 
							<a href="<?php _e('http://www.moallemi.ir/en/blog/2009/12/18/comments-on-feed-for-wordpress/', 'advanced-user-agent-displayer'); ?>"><?php _e('More Information', 'advanced-user-agent-displayer'); ?></a>)
						</li>
						<li><b>- <?php _e('Feed Delay ', 'advanced-user-agent-displayer'); ?></b> 
							(<a href="http://wordpress.org/extend/plugins/feed-delay/"><?php _e('Download', 'advanced-user-agent-displayer'); ?></a> | 
							<a href="<?php _e('http://www.moallemi.ir/en/blog/2010/02/25/feed-delay-for-wordpress/', 'advanced-user-agent-displayer'); ?>"><?php _e('More Information', 'advanced-user-agent-displayer'); ?></a>)
						</li>
						<li><b>- <?php _e('Contact Commenter ', 'advanced-user-agent-displayer'); ?></b> 
							(<a href="http://wordpress.org/extend/plugins/contact-commenter/"><?php _e('Download', 'advanced-user-agent-displayer'); ?></a> | 
							<a href="<?php _e('http://www.moallemi.ir/blog/1388/12/27/%d9%87%d8%af%db%8c%d9%87-%da%a9%d8%a7%d9%88%d8%b4%da%af%d8%b1-%d9%85%d9%86%d8%a7%d8%b3%d8%a8%d8%aa-%d8%b3%d8%a7%d9%84-%d9%86%d9%88-%d9%88%d8%b1%d8%af%d9%be%d8%b1%d8%b3/', 'advanced-user-agent-displayer'); ?>"><?php _e('More Information', 'advanced-user-agent-displayer'); ?></a>)
						</li>
					</ul>
				</div>
			</form>
		</div>
		<?php
	}

	require_once 'include/browser.php';

	function display_user_agent($comment_text)
	{
		$plugin_url = get_option('siteurl').'/wp-content/plugins/'.plugin_basename(dirname(__FILE__));
		global $comment;
		if(is_feed())
			return $comment_text;
		$auad_options = get_auad_options();
		$browser = new Browser($comment->comment_agent);
		if($auad_options['general_show_unknown'] == 'true' and $browser->Name == "Unknown" and $browser->Platform == "Unknown")
			return $comment_text;
		if($auad_options['post_show_browser'] != 'false')
			$auad_string = '<img width="'.$auad_options['post_icon_size'].'" height="'.$auad_options['post_icon_size'].'" src="'.$plugin_url.'/img/24/net/'.$browser->BrowserImage.'.png" alt="'.$browser->Name.' '.$browser->Version.'" title="'.$browser->Name.' '.$browser->Version.'"> ';
		if($auad_options['post_show_platform'] != 'false')
			$auad_string .= '<img width="'.$auad_options['post_icon_size'].'" height="'.$auad_options['post_icon_size'].'" src="'.$plugin_url.'/img/24/os/'.$browser->PlatformImage.'.png" alt="'.$browser->Platform.' '.$browser->Pver.'" title="'.$browser->Platform.' '.$browser->Pver.' '.$browser->Architecture.'">';
		$commentID = get_comment_id();
		$result = $auad_options['post_location'] == 'pl_before' ? $auad_string."\n\n" . $comment_text . "\n\n" : $comment_text."\n\n".$auad_string;
		return $result;
	}
	
	function display_user_agent_author($link)
	{
		//print_r($link);
		$plugin_url = get_option('siteurl').'/wp-content/plugins/'.plugin_basename(dirname(__FILE__));
		global $comment;
		if(is_feed())
			return $link;
		$auad_options = get_auad_options();
		$browser = new Browser($comment->comment_agent);
		if($auad_options['general_show_unknown'] == 'true' and $browser->Name == "Unknown" and $browser->Platform == "Unknown")
			return $link;
		if($auad_options['post_show_browser'] != 'false')
			$auad_string = '<img width="'.$auad_options['post_icon_size'].'" height="'.$auad_options['post_icon_size'].'" src="'.$plugin_url.'/img/24/net/'.$browser->BrowserImage.'.png" alt="'.$browser->Name.' '.$browser->Version.'" title="'.$browser->Name.' '.$browser->Version.'"> ';
		if($auad_options['post_show_platform'] != 'false')
			$auad_string .= '<img width="'.$auad_options['post_icon_size'].'" height="'.$auad_options['post_icon_size'].'" src="'.$plugin_url.'/img/24/os/'.$browser->PlatformImage.'.png" alt="'.$browser->Platform.' '.$browser->Pver.'" title="'.$browser->Platform.' '.$browser->Pver.' '.$browser->Architecture.'">';
		$commentID = get_comment_id();
		return $link.' '.$auad_string;
	}
	
	function display_user_agent_date($input, $d = '')
	{
		$plugin_url = get_option('siteurl').'/wp-content/plugins/'.plugin_basename(dirname(__FILE__));
		global $comment;
		if(is_feed())
			return '';
		$auad_options = get_auad_options();
		$browser = new Browser($comment->comment_agent);
		if($auad_options['general_show_unknown'] == 'true' and $browser->Name == "Unknown" and $browser->Platform == "Unknown")
			return '';
		if($auad_options['post_show_browser'] != 'false')
			$auad_string = '<img width="'.$auad_options['post_icon_size'].'" height="'.$auad_options['post_icon_size'].'" src="'.$plugin_url.'/img/24/net/'.$browser->BrowserImage.'.png" alt="'.$browser->Name.' '.$browser->Version.'" title="'.$browser->Name.' '.$browser->Version.'"> ';
		if($auad_options['post_show_platform'] != 'false')
			$auad_string .= '<img width="'.$auad_options['post_icon_size'].'" height="'.$auad_options['post_icon_size'].'" src="'.$plugin_url.'/img/24/os/'.$browser->PlatformImage.'.png" alt="'.$browser->Platform.' '.$browser->Pver.'" title="'.$browser->Platform.' '.$browser->Pver.' '.$browser->Architecture.'">';
		$commentID = get_comment_id();
		if(WPLANG == 'fa_IR')
			echo ' '.$auad_string.' ';
		else
			echo $input.' '.$auad_string.' ';		
	}
	
	$auad_options = get_auad_options();
	if($auad_options['post_location'] == 'pl_author')
		add_filter('get_comment_author','display_user_agent_author');
	elseif($auad_options['post_location'] == 'pl_date')
		add_filter('get_comment_date','display_user_agent_date');
	else
		add_filter('get_comment_text','display_user_agent');
		
	if($auad_options['show_in_dashboard'] == 'true')
		add_filter('get_comment_excerpt', 'display_user_agent');
		
	
	$plugin = plugin_basename(__FILE__); 
	add_filter("plugin_action_links_$plugin", 'auad_links' );
	
	function auad_links($links)
	{ 
		$settings_link = '<a href="options-general.php?page=advanced-user-agent-displayer">'.__('Settings', 'advanced-user-agent-displayer').'</a>';
		array_unshift($links, $settings_link); 
		return $links; 
	}

?>
