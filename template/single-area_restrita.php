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
