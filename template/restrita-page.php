<?php
/*
	Template Name: Área Restrita
*/
?>
<?php get_header(); ?>

<div id="primary" class="site-content">
  <div id="content" role="main">
    <?php the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <header class="entry-header">
        <?php 
				if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
					the_post_thumbnail();
					} 
			?>
        <h1 class="entry-title">
          <?php the_title(); ?>
        </h1>
      </header>
      <div class="entry-content">
        <?php the_content(); ?>
        <?php wp_link_pages( array( 'before' => '<div class="page-links">Pages:', 'after' => '</div>' ) ); ?>
      </div>
      <!-- .entry-content -->
      <div>
      <section>
        <div class="container">
          <?php $args = array(
					'post_type'   => 'area_restrita',
					'post_status' => 'publish',
					'orderby'     => 'title',
					'order'       => 'ASC'
				);	
				$ar_query = new WP_Query( $args ); 
				if ( $ar_query->have_posts() ) : while ( $ar_query->have_posts() ) : $ar_query->the_post(); ?>
          <h2><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2
          <div class="item-entry-img">
            <?php 
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'restrita_image' );;
					} 
				?>
          </div>
          <div class="item-entry-text">
            <?php the_content(); ?>
          </div>
          <?php endwhile; 
		  else: ?>
          <p>Sorry, nothing found.</p>
          <?php endif; ?>
          <?php wp_reset_postdata(); ?>
        </div>
      </section>
      <footer class="entry-meta">
        <?php edit_post_link( 'Edit', '<span class="edit-link">', '</span>' ); ?>
      </footer>
      <!-- .entry-meta --> 
    </article>
    <!-- #post --> 
  </div>
  <!-- #content --> 
</div>
<!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
