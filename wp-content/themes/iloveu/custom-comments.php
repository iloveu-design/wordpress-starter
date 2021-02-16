<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password,
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

$twenty_twenty_one_comment_count = get_comments_number();
?>

<div id="comments" class="comments-area default-max-width <?php echo get_option( 'show_avatars' ) ? 'show-avatars' : ''; ?>">
<?php 
	
	function custom_fields($fields) {
		 $req = get_option( 'require_name_email' );
		 $aria_req = ( $req ? " aria-required='true'" : '' );
		 $fields[ 'author' ] = '<p class="comment-form-author">'.
		   '<label for="author">' . "이름". '</label>'.( $req ? '<span class="required">*</span>' : "" ). '<input id="author" name="author" type="text" value="'. esc_attr( $commenter['comment_author'] ) .
		   '" size="10" placeholder="이름" tabindex="1"' . $aria_req . ' /></p>';
		$fields[ 'email' ] = '<p class="comment-form-email">'.
		   '<label for="email">' . __( 'Email' ) . '</label>'.
		   ( $req ? '<span class="required">*</span>' : '').
		   '<input id="email" name="email" type="text" value="'. esc_attr( $commenter['comment_author_email'] ) .
		   '" size="30"  tabindex="2" aria-required="false"/></p>';
		 $fields[ 'password' ] = '<p class="comment-form-password">'.
		   '<label for="password">' . __( 'Password' ) . '</label>'.
		   '<input id="password" name="password" type="password" value="'. esc_attr( $commenter['password'] ) .
		   '" size="30" placeholder="비밀번호"  tabindex="3" /></p>';
	   return $fields;
     }
     add_filter('comment_form_default_fields', 'custom_fields');
		function remove_website_field($fields) {
			unset($fields['url']);
			unset($fields['cookies']);
			unset($fields['email']);
			return $fields;
		}
		 
		add_filter('comment_form_default_fields', 'remove_website_field');
	?>
	<?php
	comment_form(
		array(
			'logged_in_as'       => null,
			'title_reply'        => '',
			'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title">',
			'title_reply_after'  => '</h2>',
			'label_submit' => '글쓰기',
		)
	);
	?>
	<?php
	if ( have_comments() ) :
        global $post;
		?>
		<h2 class="comments-title">
			<?php if ( '1' === $twenty_twenty_one_comment_count ) : ?>
                <?php
                
                if ($post->post_type == 'column') {
                    printf(
                        /* translators: %s: comment count number. */
                        '댓글 ' . $twenty_twenty_one_comment_count
                    );
                } else if ($post->post_type == 'book') {
                    printf(
                        /* translators: %s: comment count number. */
                        '한줄리뷰 ' . $twenty_twenty_one_comment_count
                    );
                }
		?>
			<?php else : ?>
				<?php
                
                if ($post->post_type == 'column') {
                    printf(
                        /* translators: %s: comment count number. */
                        '댓글 ' . $twenty_twenty_one_comment_count
                    );
                } else if ($post->post_type == 'book') {
                    printf(
                        /* translators: %s: comment count number. */
                        '한줄리뷰 ' . $twenty_twenty_one_comment_count
                    );
                }
				
				?>
			<?php endif; ?>
		</h2><!-- .comments-title -->

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'avatar_size' => 60,
					'style'       => 'ol',
					'short_ping'  => true,
                    'callback' => 'custom_comments',
				)
			);
			?>
		</ol><!-- .comment-list -->

		<?php
		the_comments_pagination(
			array(
				'before_page_number' => esc_html__( 'Page', '' ) . ' ',
				'mid_size'           => 0,
				'prev_text'          => sprintf(
					'%s <span class="nav-prev-text">%s</span>',
					is_rtl() ? '>' : '<',
					esc_html__( 'Older comments', 'twentytwentyone' )
				),
				'next_text'          => sprintf(
					'<span class="nav-next-text">%s</span> %s',
					esc_html__( 'Newer comments', '' ),
					is_rtl() ? '<' : '>'
				),
			)
		);
		?>

	<?php endif; ?>

    <script>
	  	jQuery(document).ready(function($){
              $(".comment-reply-link").text("답글");
              $(".update-comment").click(function(e){
                e.preventDefault();
                if($(this).parent().find(".comment-password").css("display") == "inline-block") {
                      if($(this).parent().find(".comment-password").val() != '') {
                        var $this_comment_id = $(this).attr('id');
                      $.ajax({
                        type: "post",
                        dataType: "json",
                        url: '/wp-admin/admin-ajax.php',
                        data: {
                            action : 'check_comment_password', // wp_ajax_*, wp_ajax_nopriv_*
                            'comment_id': $this_comment_id,
                            'password': $(this).parent().find(".comment-password").val(),
                             },
                        success: function(msg){
                            console.log(msg);
                            if(msg[0].result === 'ok') {
                                location.reload();
                            } else {
                                alert('비밀번호가 다릅니다.');
                            }
                        },
                        error: function (errorThrown) {
                            console.log(errorThrown);
                        }
                    });
                      } else {
                        alert('비밀번호를 입력해주세요.');
                      }
                      
                  } else {
                    $(this).parent().find(".comment-password").css("display", "inline-block");
                  }
              });
			  $(".delete-comment").click(function(e) {
                    e.preventDefault();
                  if($(this).parent().find(".comment-password").css("display") == "inline-block") {
                      if($(this).parent().find(".comment-password").val() != '') {
                        var $this_comment_id = $(this).attr('id');
                      $.ajax({
                        type: "post",
                        dataType: "json",
                        url: '/wp-admin/admin-ajax.php',
                        data: {
                            action : 'check_comment_password', // wp_ajax_*, wp_ajax_nopriv_*
                            'comment_id': $this_comment_id,
                            'password': $(this).parent().find(".comment-password").val(),
                             },
                        success: function(msg){
                            console.log(msg);
                            if(msg[0].result === 'ok') {
                                location.reload();
                            } else {
                                alert('비밀번호가 다릅니다.');
                            }
                        },
                        error: function (errorThrown) {
                            console.log(errorThrown);
                        }
                    });
                      } else {
                        alert('비밀번호를 입력해주세요.');
                      }
                      
                  } else {
                    $(this).parent().find(".comment-password").css("display", "inline-block");
                  }
				  
			  });
		  });
	  </script>
      <style>
      .comment-form {
          margin: 0 0 30px;
      }
      .comment-form, .comment-form-comment {
          position: relative;
          float: left;
          width: 100%;
      }
	  	.comment-form > p input[type=email], .comment-form > p input[type=text], .comment-form > p input[type=password], .comment-form > p textarea {
			  border: 1px solid #ececec; 

		}
        input::placeholder, textarea::placeholder {
            font-size: 12px;
            padding: 5px;
            vertical-align: middle;
            
        }
        .comment-form > p textarea {
            width: 100%;
            resize: none;
        }
		.comment-form > p {
			position: relative;
			float: left;
		}
		.comment-form > p input[type=email], .comment-form > p input[type=text], .comment-form > p input[type=password] {
			padding: 5px; 
			font-size: 15px;
            width: 300px;
            margin: 0px 20px 20px 0;
		}
        .comment-reply-title { font-size: 15px; padding-top: 30px; padding-bottom: 20px;}
        .comment-form > p label { display: none;}
	  	.comment-list { background: white; margin: 0}
          .comment-password { display: none; background: white; margin: 0 5px; border: 1px solid #aaa;}
          .comment-password::placeholder {
              vertical-align: middle;
          }
		.comment { border-top: 1px solid #ddd; float: left; position: relative; width: 100%; background: white;}
		.comment-author.vcard {padding: 10px 15px; }
		.children .comment {padding: 0; background: #ececec;}
		.children .comment .comment-author.vcard { }
		.children .comment .reply { display: none; }
		.comment-author.vcard span.comment-author { font-size: 15px; font-weight: bold; }
		.commentmetadata { margin-left: 10px; font-size: 12px; color: #ccc; letter-spacing: -1px; font-weight: 400; }
		.delete-comment { font-size: 12px; color: #aaa; padding-left: 5px;}
		.comment p { padding: 10px 15px; font-size: 14px; color: #333;}
		.children .comment p { padding: 10px 35px; }
		.reply { position: relative; float: left; width: 100%; padding: 10px 15px;}
		.btn-reply { float: left; position: relative; border: 1px solid #ccc; font-size: 13px; padding: 2px 5px; }
		.btn-reply a { text-decoration: none; }
		.children .comment .comment-author.vcard span.comment-author:before {
			content: 'ㄴ ';
			font-weight: 400;
			color: #aaa;
		}
		.comment-list .children > li, .comment-list > li { margin: 0; }
		.comments-area input[type=submit] { border: 1px solid #ececec;
            background: #ccc;
            padding: 5px 13px;
            font-size: 12px;
            color: #333;
        }
        .comment-form .form-submit {
            position: relative;
            float: left;
            width: 100%;
        }
	.comment-form > p:last-of-type { text-align: right;}
	.comments-title { font-size: 15px; font-weight: bold;  margin-bottom: 10px; }
	.comment-notes { display: none !important;}
    .comments-area { position: relative; float: left; padding-bottom: 50px; width: 100%; }
    @media screen and (max-width: 1024px) {
        .comment-form > p input[type=email], .comment-form > p input[type=text], .comment-form > p input[type=password] {
            width: 100%;
            margin: 10px 0;
        }
        .comment-form-author, .comment-form-password  {
            width: 49%;
        }
        .comment-form-password {
            margin-left: 2%;
        }
        .comment-password {
            margin: 10px 0;
            position: relative;
            float: left;
            width: 100%;
        }
    }
	  </style>
</div><!-- #comments -->
