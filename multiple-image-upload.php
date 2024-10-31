<?php
/*
  Plugin Name: Multiple Image Upload
  Plugin URI: http://brainwaveinfoways.com/
  Description: This plugin allow to upload multiple images for posts, pages or custom post with arrange images order using drag and drop.
  Author: Bharat Bhola
  Version: 1.0.1
  Author URI: https://www.upwork.com/freelancers/~01c2201e125460dc1a
 */

define("MIU_DIR_PATH",dirname(__FILE__));
require(MIU_DIR_PATH.'/admin-options.php');

class MultipleImageUpload{ 
    public function __construct() {                
        $this->rules = array();                        
        $this->miusettings=get_option('miusettings');        
        add_action('add_meta_boxes', array($this,'add_image_metaboxes'));
        add_action('save_post', array($this,'save_image_data'));
        add_action( 'admin_enqueue_scripts', array($this,'load_miu_wp_admin_style' ));

    }
    function load_miu_wp_admin_style() {
        wp_enqueue_script('jquery-ui-droppable');
    }

    function save_image_data($post_id){
        if (isset($_POST['miu_post_nonce'])){
            if (wp_verify_nonce($_POST['miu_post_nonce'], 'miu_post')){
                if ( isset( $_POST['image_attachment_ids'] ) ) {
                    $image_attachment_ids=array();
                    foreach ($_POST['image_attachment_ids'] as $attachment) {
                        $image_attachment_ids[]=sanitize_text_field($attachment);
                    }
                    update_post_meta( $post_id, 'image_attachment_ids', $image_attachment_ids);
                }
            }
        }
    }
    function add_image_metaboxes(){
        if(is_array($this->miusettings)){
            $posttypes=array();
            foreach ($this->miusettings as $post_type => $value) {
                $posttypes[]=$post_type;
            }
            add_meta_box('add_multiple_images_metabox', 'Multiple Images', array($this,'add_multiple_images_metabox'),$posttypes);
        }
    }

    function add_multiple_images_metabox($post){
        $image_attachment_ids=get_post_meta($post->ID,'image_attachment_ids',true);     
        ?>
        <?php wp_nonce_field('miu_post', 'miu_post_nonce');?>
        <ul class="bb-gallery-list">
            <?php 
            if(is_array($image_attachment_ids)):
                foreach ($image_attachment_ids as $attchment_id) {
                    $url=wp_get_attachment_image_src($attchment_id,'thumbnail');                
                    echo '<li>';
                    echo '<img src="'.esc_url_raw($url[0]).'">';
                    echo '<input type="hidden" name="image_attachment_ids[]" value="'.$attchment_id.'"/>';
                    echo '<a class="gallery_image_remove">Remove</a></li>';
                }
            endif;
            ?>
        </ul>
        <ul class="bb-gallery-url">
            <li>
                <a class="add-new-images button button-primary" onclick="open_media_uploader_multiple_images()">Add New</a>
            </li>
        </ul>
        <style type="text/css">
        .bb-gallery-url{margin: 0;padding: 0;text-align: right;}
        .bb-gallery-list li {width: auto; display: inline-block; padding-right: 10px; position: relative;cursor: move;}
        .gallery_image_remove{display: block;text-align: center;cursor: pointer;}
        </style>
        <script type="text/javascript">
            jQuery( function() {
                jQuery( ".bb-gallery-list" ).sortable();
                jQuery( ".bb-gallery-list" ).disableSelection();
            });
            var media_uploader = null;
            function open_media_uploader_multiple_images()
            {
                media_uploader = wp.media({
                    frame:    "post", 
                    state:    "insert", 
                    multiple: true 
                });

                media_uploader.on("insert", function(){

                    var length = media_uploader.state().get("selection").length;
                    var images = media_uploader.state().get("selection").models
                    returnhtml='';
                    for(var iii = 0; iii < length; iii++){                      
                        returnhtml+='<li>';
                        returnhtml+='<img src="'+images[iii].changed.sizes.thumbnail.url+'"/>';
                        returnhtml+='<input type="hidden" name="image_attachment_ids[]" value="'+images[iii].id+'"/>';
                        returnhtml+='<a class="gallery_image_remove">Remove</a></li>';                  
                    }
                    jQuery(".bb-gallery-list").append(returnhtml);                  
                });

                media_uploader.open();
            }
        jQuery(document).ready(function($){
            $(".bb-gallery-list").on('click',".gallery_image_remove",function(){
                $(this).parent().remove();
            });         
        });
        </script>
    <?php
    }

}
new MultipleImageUpload();

function get_miu_images($post_id=null){
    if($post_id==null){
        global $post;
        $post_id=$pos->ID;
    }
    $miu_return_value=get_option('miu_return_value');
    $image_attachment_ids=get_post_meta($post_id,'image_attachment_ids',true);     
    $miu_return=array();
    if(is_array($image_attachment_ids)){
        if($miu_return_value=='url'){
            foreach ($image_attachment_ids as $attchment_id) {
                $url=wp_get_attachment_image_src($attchment_id,'full');                
                $miu_return[]=esc_url_raw($url[0]);
            }
        }else{
            return $image_attachment_ids;
        }
    }
    return $miu_return;
}
?>