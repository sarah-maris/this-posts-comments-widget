<?php
/*
Plugin Name: This Post's Comments
Description: Displays the comments for the current post 
Author: Sarah Maris
Version: 1.0
*/

class Posts_Comments extends WP_Widget {
	// widget setup
	public function __construct() {
    	$widget_ops = array( 
        'classname' => 'posts_comments',
        'description' => 'A plugin to show the current post comments',
      );
	    parent::__construct( 'posts_comments', 'Posts Comments', $widget_ops );
  }
	
	// front-end content
	public function widget( $args, $instance ) {
      // display the title
    	echo $args['before_widget'];
      if ( ! empty( $instance['title'] ) ) {
        echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
      }

      // get the post id for current post
      global $post;
      $postID = get_the_ID();

      // get the comments arra
      $comments = get_comments( array( 'post_id' => $postID ) );

      // if no comments, post message
      if ( empty($comments)) {
          echo esc_html__( 'No comments!', 'text_domain' );	
      } else {
          // if comments, display comment content
          foreach ( $comments as $comment ) :
              $author = $comment -> comment_author;
              $content = $comment -> comment_content;
              $date = $comment -> comment_date;
              echo '<p>'. $content . '</br>';
              echo  '<strong>' . $author . '</strong></br>';
              echo  date_format( new DateTime($date), "d.m.Y" ) . '</p>';
          endforeach;
      }
        
      echo $args['after_widget'];
  }

	// widget page content 
	public function form( $instance ) {
    	$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Title', 'text_domain' );
        ?>
        <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
        <?php esc_attr_e( 'Title:', 'text_domain' ); ?>
        </label> 
        
        <input 
          class="widefat" 
          id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" 
          name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" 
          type="text" 
          value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
  }

	// update
	public function update( $new_instance, $old_instance ) {
    	$instance = array();
      $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
      return $instance;
  }
}

// register Posts_Comments
add_action( 'widgets_init', function(){
	register_widget( 'Posts_Comments' );
});