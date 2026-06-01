<?php
/**
 * Generic page template
 */
get_header();
if ( have_posts() ) : the_post(); ?>
<section class="page-hero">
    <div class="container">
        <h1><?php the_title(); ?></h1>
    </div>
</section>
<div class="container" style="padding:3rem 1rem 7rem;max-width:900px">
    <div style="font-size:14px;color:var(--text);line-height:1.8">
        <?php the_content(); ?>
    </div>
</div>
<?php endif;
get_footer(); ?>
