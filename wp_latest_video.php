<?php
/*
=== WP-Latest Video Widget ===

Plugin Name: WP-Latest Video Widget
Version: 1.70
Description: A widget that shows latest video in sidebar from your custom set category using custom fields.
Author: Radu Lucian
Author URI: http://specimen.ro
Plugin URI: http://specimen.ro/wp-latest-video-widget-plugin
License: GPLv2 or later

Copyright 2011 Radu Lucian (lucian@specimen.ro)

*/

class specimen_latestvideo extends WP_Widget {

	function specimen_latestvideo() {
	    load_plugin_textdomain('wp-latest-video-widget', false, dirname(plugin_basename(__FILE__)) . '/lang/');
		$widget_ops = array('classname' => 'specimen_widget_video', 'description' => __('Show latest video.', 'wp-latest-video-widget'));
		$this->WP_Widget('specimen-latest-video', __('WP-Latest Video', 'wp-latest-video-widget'), $widget_ops);
	}
	
	function widget($args, $instance) {
		extract($args);
		
		
		//$show_rating = isset($instance['titlelink']) ? $instance['titlelink'] : true;
		$height = $instance['height'];
		$width = $instance['width'];
		$category = $instance['category'];
		$titlelink = $instance['titlelink'];
		$titlelinktype = $instance['titlelinktype'];
		
		if(!$height) {
			$height = '250';
		}
		
		if(!$width) {
			$width = '300';
		}
		
		if(!category) {
			$category = 'Videos';
		}
		
		if (!$titlelinktype) {
			$titlelinktype = 'post';
		}
		
		$catex = term_exists("$category", 'category', false);
		if ($catex !== 0 && $catex !== null)
		{
		global $post;
		$recent_video = new WP_Query('showposts=1&meta_key=video_site&category_name='.$category.'');
		//$vid_cat_id = get_cat_ID(''.$category.'');
		//$vid_cat_link = get_category_link($vid_cat_id);
		while ( $recent_video -> have_posts() ) : $recent_video -> the_post(); 
		
		$vsite = get_post_meta($post->ID, 'video_site',true);
		$posturl = get_permalink( $post->ID );
		$posttitle = get_the_title();
		$visis = isset($vsite);	
		
		$yt_video_id = get_post_meta($post->ID, 'youtube_video_id',true);
		$vi_video_id = get_post_meta($post->ID, 'vimeo_video_id',true);
		$ap_video_id = get_post_meta($post->ID, 'apropo_video_id',true);
		$ap_video_key = get_post_meta($post->ID, 'apropo_video_key',true);
		
		echo $before_widget;
		if ( $titlelink == 1 )
		{
			
			if ( $titlelinktype == 'post' ) {
			
			echo ''.$before_title.'' . '<a href="'.$posturl.'" title="'.$posttitle.'">'.$instance['title'].'</a>' . ''.$after_title.'';
			
			}
			elseif ( $titlelinktype == 'category' ) {
			
				$category_link = esc_url( get_category_link( get_cat_ID($category) ) );
				
				echo ''.$before_title.'' . '<a href="'.$category_link.'" title="'.$category.'">'.$instance['title'].'</a>' . ''.$after_title.'';
			
			}
			elseif ( $titlelinktype == 'video_site' ) {
			
				if ( $vsite == 'youtube' ) {
				
					echo ''.$before_title.'' . '<a href="http://'.$vsite.'.com/watch?v='.$yt_video_id.'" title="http://'.$vsite.'.com/watch?v='.$yt_video_id.'">'.$instance['title'].'</a>' . ''.$after_title.'';
				
				}
				elseif ( $vsite == 'vimeo' ) {
				
					echo ''.$before_title.'' . '<a href="http://'.$vsite.'.com/'.$vi_video_id.'" title="http://'.$vsite.'.com/'.$vi_video_id.'">'.$instance['title'].'</a>' . ''.$after_title.'';
				
				}
			
				
			
			} else {
				
				echo $before_title . $instance['title'] . $after_title;
				
			}
			
			
			
		}
		elseif ( $visis == 1 && $titlelink == 0 )
		{
			echo $before_title . $instance['title'] . $after_title;
		}

		if ( $visis == '0' ) {
		
		if ( !isset($yt_video_id) == '1' ) {
		?>
        <div class="textwidget">
         <iframe title="YouTube video player" width="<?php echo $width; ?>" height="<?php echo $height; ?>" src="http://www.youtube.com/embed/<?php echo $yt_video_id; ?>?rel=0" frameborder="0" allowfullscreen></iframe>
        </div>
		<?php
		}
		elseif ( !isset($ap_video_id) == '1' ) {
		
		   if ( !isset($ap_video_key) == '1' ) {
		   		?>
		<div class="textwidget">
		<embed width="<?php echo $width; ?>" height="<?php echo $height; ?>" src="http://cache.apropo.ro/<?php echo $apropo_video_id; ?>/videoplayer.swf?key=<?php echo $apropo_video_key; ?>&autostart=false" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" allowfullscreen="true" autoplay="false" />
		</div>
		<?php
		   }
		   }
		   else
		   {
		   echo '<div class="textwidget"><font color="red"><p align="center"><b><strong>'._e('ERROR!', 'wp-latest-video-widget').':</strong><br />'._e('Video not set by user', 'wp-latest-video-widget').'<br />'._e('or', 'wp-latest-video-widget').'<br />'._e('no video to show.', 'wp-latest-video-widget').'</b></font></p></div>';
		   }
		}
		elseif ( !isset($vi_video_id) == '1' ) {
		?>
		<iframe src="http://player.vimeo.com/video/<?php echo $vi_video_id; ?>?title=0&amp;byline=0&amp;portrait=0" width="<?php echo $width; ?>" height="<?php echo $height; ?>" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
		<?php
		}
		else {
		
		if ( $vsite == 'apropo' ) {
		?>
		<div class="textwidget">
		<embed width="<?php echo $width; ?>" height="<?php echo $height; ?>" src="http://cache.apropo.ro/<?php echo $apropo_video_id; ?>/videoplayer.swf?key=<?php echo $apropo_video_key; ?>&autostart=false" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" allowfullscreen="true" autoplay="false" />
		</div>
		<?php
		}
		elseif ( $vsite == 'youtube' ) {
		?>
        <div class="textwidget">
         <iframe title="YouTube video player" width="<?php echo $width; ?>" height="<?php echo $height; ?>" src="http://www.youtube.com/embed/<?php echo $yt_video_id; ?>?rel=0" frameborder="0" allowfullscreen></iframe>
        </div>
		<?php
		}
		elseif ( $vsite == 'vimeo' ) {
		?>
		<iframe src="http://player.vimeo.com/video/<?php echo $vi_video_id; ?>?title=0&amp;byline=0&amp;portrait=0" width="<?php echo $width; ?>" height="<?php echo $height; ?>" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
		<?php
		}
		}
		endwhile; wp_reset_query();		
		
		echo $after_widget;
		}
		else
		{
		echo $before_widget;
		
		echo $before_title . $instance['title'] . $after_title;
		echo '<div class="textwidget"><font color="red"><p align="center"><b><strong>'._e('ERROR!', 'wp-latest-video-widget').':</strong><br />'._e('Video category set by user', 'wp-latest-video-widget').'<br />'._e('do not exist!', 'wp-latest-video-widget').'</b></font></p></div>';
		
		echo $after_widget;
		}
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['width'] = $new_instance['width'];
		$instance['titlelink'] = !empty($new_instance['titlelink']) ? 1 : 0;
		$instance['height'] = $new_instance['height'];
		$instance['category'] = $new_instance['category'];
		$instance['titlelinktype'] = $new_instance['titlelinktype'];

		return $instance;
	}
	
	function form($instance) {

		$instance = wp_parse_args((array) $instance, $defaults);
		$instance = wp_parse_args( (array) $instance, array('title' => 'Latest Video', 'category' => 'Videos', 'width' => '300', 'height' => '250'));
		$title = htmlspecialchars($instance['title']);
		$titlelink = isset( $instance['titlelink'] ) ? (bool) $instance['titlelink'] : false;
		$titlelinktype = $instance['titlelinktype'];
		
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'wp-latest-video-widget'); ?></label><br />
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title;?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Category Name:', 'wp-latest-video-widget'); ?></label>
			<input class="widefat" style="width: 70px;" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>" value="<?php echo $instance['category']; ?>" /><?php _e('(Ex: Videos)', 'wp-latest-video-widget'); ?>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width:', 'wp-latest-video-widget'); ?></label>
			<input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" value="<?php echo $instance['width']; ?>" />

			<label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height:', 'wp-latest-video-widget'); ?></label>
			<input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" value="<?php echo $instance['height']; ?>" />
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('titlelink'); ?>" name="<?php echo $this->get_field_name('titlelink'); ?>"<?php checked( $titlelink ); ?> />
			<label for="<?php echo $this->get_field_id('titlelink'); ?>"><?php _e( 'Enable widget title link', 'wp-latest-video-widget' ); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titlelinktype'); ?>"><?php _e( 'Where to link widget titile', 'wp-latest-video-widget' ); ?></label>
			<select name="<?php echo $this->get_field_name('titlelinktype'); ?>" id="<?php echo $this->get_field_id('titlelinktype'); ?>" class="widefat">
			<?php
			$options = array('post', 'category', 'video_site');
			foreach ($options as $option) {
				echo '<option value="' . $option . '" id="' . $option . '"', $titlelinktype == $option ? ' selected="selected"' : '', '>', $option, '</option>';
			}
			?>
			</select>
		</p>

		<p>
		<?php _e('To make this widget to work, you must set custom fields in post page.', 'wp-latest-video-widget'); ?><br />
		<?php echo ''.__('First you must set', 'wp-latest-video-widget').' "<b>video_site</b>" '.__('(can be youtube,vimeo,apropo).', 'wp-latest-video-widget').''; ?>
        <?php echo ''.__('For Youtube you must set', 'wp-latest-video-widget').' "<b>youtube_video_id</b>"'; ?>
		<?php echo ''.__('For Vimeo you must set', 'wp-latest-video-widget').' "<b>vimeo_video_id</b>"'; ?>
		<?php echo ''.__('For Apropo.ro/Tare.ro you must set', 'wp-latest-video-widget').' "<b>apropo_video_id</b>" '.__('and', 'wp-latest-video-widget').' "<b>apropo_video_key</b>".<br />'; ?>
		<?php echo ''.__('More details and help', 'wp-latest-video-widget').' <a href="http://specimen.ro/wp-latest-video-widget-plugin" title="'.__('HELP HERE!', 'wp-latest-video-widget').'">'.__('here', 'wp-latest-video-widget').'</a>.'; ?>
		</p>
		
		<?php
	}
}

add_action('widgets_init', create_function('', 'return register_widget("specimen_latestvideo");'));

// gata !
?>