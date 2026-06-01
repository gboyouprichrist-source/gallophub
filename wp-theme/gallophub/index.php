<?php
/**
 * Fallback template
 */
get_header();
?>
<div class="container" style="padding:5rem 1rem;text-align:center">
    <h1 style="margin-bottom:1rem"><?php the_title(); ?></h1>
    <div style="font-size:14px;color:var(--sub);max-width:700px;margin:0 auto;line-height:1.75">
        <?php the_content(); ?>
    </div>
</div>
<?php get_footer(); ?>
