<?php get_header(); ?>
<div id="content">
    <div id="inner-content" class="wrap clearfix">
        <div id="main" class="eightcol first clearfix" role="main">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
                    <header class="article-header">
                        <h1 class="page-title"><?php the_title(); ?></h1>
                        <p class="byline vcard">
                            <?php printf(__('Posted <time class="updated" datetime="%1$s" pubdate>%2$s</time> by <span class="author">%3$s</span>.', 'giraffetheme'), get_the_time('Y-m-j'), get_the_time(__('F jS, Y', 'giraffetheme')), giraffe_get_the_author_posts_link()); ?>
                        </p>
                    </header> <?php // end article header ?>
                    <section class="entry-content clearfix" itemprop="articleBody">
                        <?php the_content(); ?>
                    </section> <?php // end article section ?>
                    <footer class="article-footer">
                        <p class="clearfix"><?php the_tags('<span class="tags">' . __('Tags:', 'giraffetheme') . '</span> ', ', ', ''); ?></p>
                    </footer> <?php // end article footer ?>
                    <?php // comments_template(); ?>
                </article> <?php // end article ?>
                <?php if (false): ?>
                    <?php comment_form(); ?>
                    <?php wp_link_pages(array()); ?>
                    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>></div>
                    <?php posts_nav_link(); ?>
                    <?php wp_link_pages(); ?>
                    <?php comment_form(); ?>
                    <?php get_post_format(); ?>
                    <?php has_post_format(); ?>
                    <?php if ( ! isset( $content_width ) ) $content_width = 900; ?>
                <?php endif; ?>
            <?php endwhile; else : ?>
                <article id="post-not-found" class="hentry clearfix">
                    <header class="article-header">
                        <h1><?php _e('Oops, Post Not Found!', 'giraffetheme'); ?></h1>
                    </header>
                    <section class="entry-content">
                        <p><?php _e('Uh Oh. Something is missing. Try double checking things.', 'giraffetheme'); ?></p>
                    </section>
                    <footer class="article-footer">
                        <p><?php _e('This is the error message in the page-custom.php template.', 'giraffetheme'); ?></p>
                    </footer>
                </article>
            <?php endif; ?>
        </div> <?php // end #main ?>
        <?php get_sidebar(); ?>
    </div> <?php // end #inner-content ?>
</div> <?php // end #content ?>
<?php get_footer(); ?>
