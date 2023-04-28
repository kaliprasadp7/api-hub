<?php
/**
 * Plugin Name: Api Hub
 * Plugin URI: https://webdisk.info/blog
 * Description: It helps to create your own api.
 * Author: Kali Prasad Panda
 * Author URI: https://webdisk.info
 * Version: 1.0.0
 * Text Domain: api-hub  echo ; 
*/

if(!defined('ABSPATH'))
{
    //echo "what you want to do?";
   exit;
}

define( 'API_DIR_PATH', plugin_dir_path( __FILE__ ) );
define('API_PLUGIN_URL', plugins_url());
define('API_ADMIN_URL', admin_url());


function add_api_menu() {
    add_menu_page( 'Api-hub',//page-title
   'Api Hub',// menu-title
   'manage_options',//capabilities (user level access) 
   'api-hub',// slug
   'api_admin_view', //call back function
   'dashicons-rest-api', //icon url
    7 //position 
  );}
  add_action('admin_menu', 'add_api_menu');

  function api_admin_view(){
            //......
  }

  add_action('rest_api_init', function(){
    register_rest_route('wc/v3','posts',[
      'methods' => 'POST',
      'callback' => 'wp_custom_Api'
    ]);
  });

  //callback function
  function wp_custom_Api($request){

    // $slug= $request->get_param('slug');

  if($request->get_param('title')==null) {
    return new WP_Error('missing_fields', 'please include title as a parameter');
  } 
  else {
    $title = $request->get_param('title');
  }


  if($request->get_param('content')==null) {
    return new WP_Error('missing_fields', 'please include content as a parameter');
  } 
  else {
    $content = $request->get_param('content');
  }

  if($request->get_param('slug')==null) {
    return new WP_Error('missing_fields', 'please include slug as a parameter');
  } 
  else {
    $slug= $request->get_param('slug');
  }

    global $wpdb;
    if($wpdb->get_row("SELECT post_name FROM wp_posts WHERE post_name = '" . $slug . "'")) {
       $message = array(
        "success" => false,
        "message" => "Slug is already exist"
      );
  
    echo json_encode(  $message );
    return;
   }


   $args=array('post_title'=>$title, 'post_content'=>$content, 'post_name'=>$slug, 'post_status'=>'publish','post_type'=>'post');
   if(!empty($request)){
      wp_insert_post($args);
      
      $message = array(
        "success" => true,
        "message" => "Post published succesfully"
      );
  
    echo json_encode(  $message );
   }
   else{
     return new WP_Error('missing_fields', 'please fill title,content & slug as a parameter');
   }
  
  

  }
  ?>