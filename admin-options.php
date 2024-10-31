<?php
class MIU_Admin_Settings
{
	
	function __construct()
	{
		add_action('admin_menu', array($this,'MIU_Admin_Settings_menu_pages'));
	}

	public function MIU_Admin_Settings_menu_pages(){		
		add_menu_page('MIU Settings', 'MIU Settings', 'manage_options', 'miusettings', array($this,'miumanagement_settings'));		
	}
	
	public function miumanagement_settings(){
		if(isset($_POST['submit'])){
			if (isset($_POST['miu_option_nonce'])){
        		if (wp_verify_nonce($_POST['miu_option_nonce'], 'miu_option_nonce')){
        			if(isset($_POST['miusettings'])){
						$miusettings=array();
						foreach ($_POST['miusettings'] as $key => $value) {
							$miusettings[$key]=sanitize_text_field($value);
						}
						if (get_option('miusettings') !== false ) {
							update_option('miusettings',$miusettings);
						}else{
							add_option('miusettings',$miusettings,null,'no');
						}	
					}else{
						update_option('miusettings',null);
					}
					if(isset($_POST['miu_return_value'])){
						if (get_option('miu_return_value') !== false ) {
							update_option('miu_return_value',sanitize_text_field($_POST['miu_return_value']));
						}else{
							add_option('miu_return_value',sanitize_text_field($_POST['miu_return_value']),null,'no');
						}	
					}else{
						update_option('miu_return_value',null);
					}
            	}
           	}					
		}
	    $miusettings=get_option('miusettings');
	    $miu_return_value=get_option('miu_return_value');
	    $post_types=get_post_types(array('show_ui'=>true));
	    ?>
	    <div class="wrap">
			<h1>MIU Settings</h1>
			<form method="post" action="" novalidate="novalidate">
				<?php wp_nonce_field('miu_option', 'miu_option_nonce');?>
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row"><label>Return value</label></th>
							<td>								
								<span class="setting_wrap" style="margin-right: 10px;"><input type="radio" name="miu_return_value" value="url" <?php if($miu_return_value=='url'){ echo 'checked';};?>> Image Url</span><span class="setting_wrap"><input type="radio" name="miu_return_value" value="id" <?php if($miu_return_value=='id'){ echo 'checked';};?>> Image ID</span>
							</td>
						</tr>
						<?php foreach ( $post_types as $post_type ) {
							$post_obj = get_post_type_object($post_type);
							?>
						   <tr>
								<th scope="row"><label for="miusettings[<?php echo $post_type;?>]"><?php echo $post_obj->labels->singular_name;?></label></th>
								<td>								
									<input name="miusettings[<?php echo $post_type;?>]" type="checkbox" id="miusettings[<?php echo $post_type;?>]" class="regular-text" value="1" <?php if(isset($miusettings[$post_type]) && $miusettings[$post_type]==true){ echo 'checked'; }?> >
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
			</form>
		</div>
		<?php
	}		
	
}
new MIU_Admin_Settings();
?>