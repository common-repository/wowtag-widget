<?php
/*
Plugin Name: WoWTag Widget
Plugin URI: http://wowtag.nfshost.com/
Description: Easily adds a WoWTag to your Worpress site to display your World of Warcraft Character.
Version: 0.2.2B
Author: Timothy 'SeiferTim' Ian Hely
Author URI: http://tims-world.com/

Copyright (C) 2010 Timothy 'SeiferTim' Ian Hely

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

//error_reporting(E_ALL);
wp_enqueue_script('jquery');
add_action('widgets_init', 'load_widget');
add_action('wp_head', 'add_head');
function load_widget()
{
	register_widget('WoWTagWidget');
}

function add_head()
{
	$path = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
	//echo '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>';
	
	echo '<script type="text/javascript" src="' . $path  . 'wowtag.js"></script>';
}

class WoWTagWidget extends WP_Widget
{
	function WoWTagWidget()
	{
		$widget_ops = array('classname'=>'wowtag', 'description'=>'Easily adds a WoWTag to your Worpress site to display your World of Warcraft Character.');
		$control_ops = array('id_base'=>'wowtag-widget');
		$this->WP_Widget('wowtag-widget', 'WoWTag Widget', $widget_ops, $control_ops);
	}
	
	function form($instance)
	{
		$defaults = array('realm'=>'', 'character'=>'', 'refreshtime'=>0, 'backcolor'=>'', 'border'=>'', 'roundamt'=>'', 'shine'=>true, 'namecolor'=>'', 'titlecolor'=>'', 'guildcolor'=>'');
		$instance = wp_parse_args((array)$instance, $defaults);
		
		?>
			<input type="hidden" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo esc_attr($instance['character']); ?>"/>
			<p>
				<label for="<?php echo $this->get_field_id('realm'); ?>">Realm: </label>
				<input id="<?php echo $this->get_field_id('realm'); ?>" name="<?php echo $this->get_field_name('realm'); ?>" value="<?php echo $instance['realm']; ?>" class="widefat" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('character'); ?>">Character: </label>
				<input id="<?php echo $this->get_field_id('character'); ?>" name="<?php echo $this->get_field_name('character'); ?>" value="<?php echo $instance['character']; ?>" class="widefat" />
			</p>
			<hr/>
			<p>
				<label for="<?php echo $this->get_field_id('refreshtime'); ?>">Refresh Time: </label>
				<select id="<?php echo $this->get_field_id('refreshtime'); ?>" name="<?php echo $this->get_field_name('refreshtime'); ?>">
				<?php
					for ($i = 0; $i <= 30; $i+=5)
					{
						?>
						<option value="<?php echo $i; ?>" <?php if ($i == $instance['refreshtime']) echo 'selected="selected"';?>><?php echo $i;?></option>
						<?php
					}
				?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('backcolor'); ?>">Back Color: </label>
				<input id="<?php echo $this->get_field_id('backcolor'); ?>" name="<?php echo $this->get_field_name('backcolor'); ?>" value="<?php echo $instance['backcolor']; ?>" class="widefat" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('border'); ?>">Border: </label>
				<input id="<?php echo $this->get_field_id('border'); ?>" name="<?php echo $this->get_field_name('border'); ?>" value="<?php echo $instance['border']; ?>" class="widefat" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('roundamt'); ?>">Round Amount: </label>
				<input id="<?php echo $this->get_field_id('roundamt'); ?>" name="<?php echo $this->get_field_name('roundamt'); ?>" value="<?php echo $instance['roundamt']; ?>" class="widefat" />
			</p>
			<p>
				<input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id('shine'); ?>" name="<?php echo $this->get_field_name('shine'); ?>" <?php checked($instance['shine'],true); ?> />
				<label for="<?php echo $this->get_field_id('shine'); ?>">Shine?</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('namecolor'); ?>">Name Color: </label>
				<input id="<?php echo $this->get_field_id('namecolor'); ?>" name="<?php echo $this->get_field_name('namecolor'); ?>" value="<?php echo $instance['namecolor']; ?>" class="widefat" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('titlecolor'); ?>">Title Color: </label>
				<input id="<?php echo $this->get_field_id('titlecolor'); ?>" name="<?php echo $this->get_field_name('titlecolor'); ?>" value="<?php echo $instance['titlecolor']; ?>" class="widefat" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('guildcolor'); ?>">Guild Color: </label>
				<input id="<?php echo $this->get_field_id('guildcolor'); ?>" name="<?php echo $this->get_field_name('guildcolor'); ?>" value="<?php echo $instance['guildcolor']; ?>" class="widefat" />
			</p>
		<?php
	}
	
	function widget($args, $instance)
	{
		extract($args);
		$no = $this->number;
		$refresh_code = '<script type="text/javascript">'."\r\n".'jQuery(document).ready(function() { '."\r\n".'wT'.$no.' = new wowTag(); '."\r\n".'wT'.$no.'.init({charname:\''.$instance['character'].'\',realm: \''.$instance['realm'].'\',placeholder: \'wowtag-'.$no.'\', refreshtime: '.$instance['refreshtime'].', shine: ';
		if($instance['shine'] == true)
			$refresh_code .= '\'yes\'';
		else
			$refresh_code .= '\'no\'';
		if($instance['backcolor']!='')
			$refresh_code.=', backcolor: \''.$instance['backcolor'].'\'';
		if($instance['border']!='')
			$refresh_code.=', border: \''.$instance['border'].'\'';
		if($instance['namecolor']!='')
			$refresh_code.=', namecolor: \''.$instance['namecolor'].'\'';
		if($instance['roundamt']!='')
			$refresh_code.=', roundamt: \''.$instance['roundamt'].'\'';
		if($instance['titlecolor']!='')
			$refresh_code.=', titlecolor: \''.$instance['titlecolor'].'\'';
		if($instance['guildcolor']!='')
			$refresh_code.=', guildcolor: \''.$instance['guildcolor'].'\'';
		$refresh_code .= '});'."\r\n".'});'."\r\n".'</script>';
		echo $refresh_code;
		echo $before_widget;
		echo '<div id="wowtag-'.$no.'"></div>';
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['realm'] = urlencode(strtolower(strip_tags($new_instance['realm'])));
		$instance['character'] = urlencode(strtolower(strip_tags($new_instance['character'])));
		$instance['refreshtime'] = (int) strip_tags($new_instance['refreshtime']);
		$instance['backcolor'] = strip_tags($new_instance['backcolor']);
		$instance['border'] = strip_tags($new_instance['border']);
		$instance['namecolor'] = strip_tags($new_instance['namecolor']);
		$instance['roundamt'] = strip_tags($new_instance['roundamt']);
		$instance['titlecolor'] = strip_tags($new_instance['titlecolor']);
		$instance['guildcolor'] = strip_tags($new_instance['guildcolor']);
		$instance['shine'] = (bool) $new_instance['shine'];
		
		return $instance;
	}
}

?>