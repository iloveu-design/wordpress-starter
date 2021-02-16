<?php
add_action( 'after_setup_theme', 'blankslate_setup' );
function blankslate_setup() {
load_theme_textdomain( 'blankslate', get_template_directory() . '/languages' );
add_theme_support( 'title-tag' );
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'html5', array( 'search-form' ) );
global $content_width;
if ( ! isset( $content_width ) ) { $content_width = 1920; }
register_nav_menus( array( 'main-menu' => esc_html__( 'Main Menu', 'blankslate' ) ) );
}
add_action( 'wp_enqueue_scripts', 'blankslate_load_scripts' );
function blankslate_load_scripts() {
wp_enqueue_style( 'blankslate-style', get_stylesheet_uri() );
wp_enqueue_script( 'jquery' );
}
add_action( 'wp_footer', 'blankslate_footer_scripts' );
function blankslate_footer_scripts() {
?>
<script>
jQuery(document).ready(function ($) {
var deviceAgent = navigator.userAgent.toLowerCase();
if (deviceAgent.match(/(iphone|ipod|ipad)/)) {
$("html").addClass("ios");
$("html").addClass("mobile");
}
if (navigator.userAgent.search("MSIE") >= 0) {
$("html").addClass("ie");
}
else if (navigator.userAgent.search("Chrome") >= 0) {
$("html").addClass("chrome");
}
else if (navigator.userAgent.search("Firefox") >= 0) {
$("html").addClass("firefox");
}
else if (navigator.userAgent.search("Safari") >= 0 && navigator.userAgent.search("Chrome") < 0) {
$("html").addClass("safari");
}
else if (navigator.userAgent.search("Opera") >= 0) {
$("html").addClass("opera");
}
});
</script>
<?php
}
add_filter( 'document_title_separator', 'blankslate_document_title_separator' );
function blankslate_document_title_separator( $sep ) {
$sep = '|';
return $sep;
}
add_filter( 'the_title', 'blankslate_title' );
function blankslate_title( $title ) {
if ( $title == '' ) {
return '...';
} else {
return $title;
}
}
add_filter( 'the_content_more_link', 'blankslate_read_more_link' );
function blankslate_read_more_link() {
if ( ! is_admin() ) {
return ' <a href="' . esc_url( get_permalink() ) . '" class="more-link">...</a>';
}
}
add_filter( 'excerpt_more', 'blankslate_excerpt_read_more_link' );
function blankslate_excerpt_read_more_link( $more ) {
if ( ! is_admin() ) {
global $post;
return ' <a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="more-link">...</a>';
}
}
add_filter( 'intermediate_image_sizes_advanced', 'blankslate_image_insert_override' );
function blankslate_image_insert_override( $sizes ) {
unset( $sizes['medium_large'] );
return $sizes;
}
add_action( 'widgets_init', 'blankslate_widgets_init' );
function blankslate_widgets_init() {
register_sidebar( array(
'name' => esc_html__( 'Sidebar Widget Area', 'blankslate' ),
'id' => 'primary-widget-area',
'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
'after_widget' => '</li>',
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
}
add_action( 'wp_head', 'blankslate_pingback_header' );
function blankslate_pingback_header() {
if ( is_singular() && pings_open() ) {
printf( '<link rel="pingback" href="%s" />' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
}
}
add_action( 'comment_form_before', 'blankslate_enqueue_comment_reply_script' );
function blankslate_enqueue_comment_reply_script() {
if ( get_option( 'thread_comments' ) ) {
wp_enqueue_script( 'comment-reply' );
}
}
function blankslate_custom_pings( $comment ) {
?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php echo comment_author_link(); ?></li>
<?php
}
add_filter( 'get_comments_number', 'blankslate_comment_count', 0 );
function blankslate_comment_count( $count ) {
if ( ! is_admin() ) {
global $id;
$get_comments = get_comments( 'status=approve&post_id=' . $id );
$comments_by_type = separate_comments( $get_comments );
return count( $comments_by_type['comment'] );
} else {
return $count;
}
}



function require_comment_name($fields) {
	
	if ($fields['comment_author'] == '') {
		wp_die('Error: please enter a valid name.');
	}
	if ($fields['comment_author_email'] == '') {
		$fields['comment_author_email']='no@email.com';
	}
	return $fields;
}
	add_filter('preprocess_comment', 'require_comment_name');

	add_action( 'comment_post', 'save_comment_password' );
function save_comment_password( $comment_id ) {
    if ( ( isset( $_POST['password'] ) ) && ( ! empty( $_POST['password'] ) ) ) {
        $mobile_number = md5($_POST['password']);
        add_comment_meta( $comment_id, 'comment-password', $mobile_number );
    }
}

function wpb_move_comment_field_to_bottom( $fields ) {
	$comment_field = $fields['comment'];
	unset( $fields['comment'] );
	$fields['comment'] = $comment_field;
	return $fields;
	}
add_filter('comment_form_fields', 'wpb_move_comment_field_to_bottom');

function check_comment_password() {
    
    if(!is_user_logged_in()) {
        $arrayName = array();
        $object = new stdClass();
        $chk_pw = get_comment_meta($_POST['comment_id'], 'comment-password', true);
        $hased_password = md5($_POST['password']);
        
        if($hased_password == $chk_pw ) :
            if(get_comments([ 'parent' => $_POST['comment_id'], 'count' => true ] ) > 0) {
                $commentarr = array();
                $commentarr['comment_ID'] = $_POST['comment_id'];
                $commentarr['comment_content'] = '삭제된 댓글입니다.';
                wp_update_comment($commentarr);
            } else {
                wp_delete_comment($_POST['comment_id']);
            }
            $object->result = 'ok';
        else :
            $object->result = 'no';
        endif;
        array_push($arrayName, $object);
        echo json_encode($arrayName);
        die();
    }
}
add_action('wp_ajax_check_comment_password', 'check_comment_password');
add_action('wp_ajax_nopriv_check_comment_password', 'check_comment_password');
function wpsites_modify_comment_form_text_area($arg) {
    $arg['comment_field'] = '<p class="comment-form-comment"><label for="comment">' . _x( 'Your Feedback Is Appreciated', 'noun' ) . '</label><textarea id="comment" name="comment" cols="45" rows="3" placeholder="내용을 입력해주세요." aria-required="true"></textarea></p>';
    return $arg;
}

add_filter('comment_form_defaults', 'wpsites_modify_comment_form_text_area');
function custom_comments($comment, $args, $depth) {
    
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
	  <div id="comment-<?php comment_ID(); ?>">
	   <div class="comment-author vcard">
		  <?php //echo get_avatar($comment,$size='48',$default='<path_to_url>' ); ?>
		  <span class="comment-author"><?php printf(__('%s'), get_comment_author()) ?></span>
		  <span class="comment-meta commentmetadata"><?php printf(__('%1$s. %2$s'), get_comment_date(),  get_comment_time()) ?>
          <?php if (is_user_logged_in()) {
            edit_comment_link(__('수정'),'  ','');
          } else {
              ?>
            <!-- <a href="#li-comment-<?php comment_ID() ?>" id="<?php comment_ID(); ?>" class="update-comment" title="댓글 수정">수정</a> -->
          <?php  }?>
          </span>
          
		  <input type="password" name="password" placeholder="비밀번호" class="comment-password"/>
	    <a href="#li-comment-<?php comment_ID() ?>" id="<?php comment_ID(); ?>" class="delete-comment" title="댓글 삭제">삭제</a>
	   </div>
	   <?php if ($comment->comment_approved == '0') : ?>
		  <em><?php _e('Your comment is awaiting moderation.') ?></em>
		  <br />
	   <?php endif; ?>
 
	   <?php comment_text() ?>
	  
		<div class="reply">
		  <div class="btn-reply"><?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?></div>
	   </div>
		
	   
	  </div>
 <?php
		 } 