<?php
/**
 * The main template file
 */

get_header(); ?>

<main id="main" class="site-main">
    <?php if (is_front_page()) : ?>
        <!-- Hero Section with Parallax -->
        <section class="hero-section">
            <div class="parallax-bg" id="parallax-bg"
                 style="background-image: url('<?php echo get_theme_option('hero_bg_image', get_template_directory_uri() . '/assets/images/hero-bg.jpg'); ?>');"></div>
            <div class="hero-content">
                <h1 class="hero-title"><?php echo esc_html(get_theme_option('hero_title', 'Men\'s Core Therapy')); ?></h1>
                <p class="hero-subtitle"><?php echo esc_html(get_theme_option('hero_subtitle', 'Transforming Lives Through Holistic Wellness')); ?></p>
                <a href="#services" class="cta-button neomorphic">Explore Our Services</a>
            </div>
        </section>

        <!-- Services Section -->
        <section id="services" class="services-section">
            <div class="container">
                <h2 class="section-title">Our Services</h2>
                <div class="services-grid">
                    <div class="service-card neomorphic">
                        <h3>Individual Therapy</h3>
                        <p>Personalized therapy sessions focused on men's mental health and wellness needs.</p>
                    </div>
                    <div class="service-card neomorphic">
                        <h3>Group Sessions</h3>
                        <p>Connect with others in a supportive environment designed for shared healing.</p>
                    </div>
                    <div class="service-card neomorphic">
                        <h3>Wellness Coaching</h3>
                        <p>Comprehensive lifestyle coaching to improve overall health and wellbeing.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="about-section">
            <div class="container">
                <div class="about-content neomorphic-inset">
                    <h2 class="section-title">About Men's Core Therapy</h2>
                    <p>We specialize in providing comprehensive mental health and wellness services tailored specifically for men. Our approach combines traditional therapy methods with modern wellness practices to create a holistic healing experience.</p>
                    <a href="/about" class="cta-button neomorphic">Learn More About Us</a>
                </div>
            </div>
        </section>
    <?php else : ?>
        <!-- Regular page/post content -->
        <div class="container">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('neomorphic'); ?>>
                        <header class="entry-header">
                            <h1 class="entry-title"><?php the_title(); ?></h1>
                        </header>
                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php else : ?>
                <p><?php _e('Sorry, no posts matched your criteria.', 'menscore-therapy'); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>