<?php get_header(); ?>

<section class="hero" aria-labelledby="hero-heading">
    <div class="hero-content">
        <div class="hero-badge">
            <span class="hero-badge-star">✦</span>
            Expert Front-End &amp; Team Leader
        </div>
        <h1 id="hero-heading" class="hero-title">
            <?php echo esc_html( mc_t('hero_line1', 'Turn your ideas into') ); ?>
            <span class="hero-title-colored"><?php echo esc_html( mc_t('hero_line2', 'digital experiences') ); ?></span>
        </h1>
        <p class="hero-subtitle"><?php echo esc_html( mc_t('hero_subtitle', 'Passionate Front-End developer and experienced Team Leader.') ); ?></p>
        <div class="hero-actions">
            <a href="<?php echo esc_url( get_page_link( get_page_by_path('parcours') ) ?: home_url('/parcours/') ); ?>" class="btn btn--primary">
                <?php echo esc_html( mc_t('hero_btn1', 'Discover my portfolio') ); ?>
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M2 7h10M7 2l5 5-5 5"/></svg>
            </a>
            <a href="#services" class="btn btn--ghost"><?php echo esc_html( mc_t('hero_btn2', 'My services') ); ?></a>
        </div>
    </div>
    <div class="hero-scroll" aria-hidden="true">
        <span>Scroll</span>
        <div class="scroll-track"><div class="scroll-fill"></div></div>
    </div>
</section>

<section class="services-section" id="services" aria-labelledby="services-heading">
    <div class="container">
        <div class="section-header">
            <h2 id="services-heading" class="section-title"><?php echo esc_html( mc_t('services_title', 'Services') ); ?></h2>
            <p class="section-subtitle"><?php echo esc_html( mc_t('services_subtitle', 'A complete expertise for your web projects') ); ?></p>
        </div>
        <?php
        $services = new WP_Query(['post_type'=>'mc_service','posts_per_page'=>-1,'meta_key'=>'_mc_order','orderby'=>'meta_value_num','order'=>'ASC']);
        if ($services->have_posts()) :
        ?>
        <ul class="services-grid">
            <?php $i=0; while($services->have_posts()) : $services->the_post(); ?>
            <?php
                $title = mc_post_t(get_the_ID(), 'title', get_the_title());
                $desc  = mc_post_t(get_the_ID(), 'content', '');
            ?>
            <li class="svc-item js-reveal" style="--delay:<?php echo $i*100; ?>ms">
                <div class="svc-card">
                    <div class="svc-top">
                        <div class="svc-icon" style="<?php echo mc_get_service_icon_style(get_the_ID(),$i); ?>" aria-hidden="true">
                            <?php echo mc_get_service_icon_html(get_the_ID(),$i); ?>
                        </div>
                        <h3 class="svc-title"><?php echo esc_html($title); ?></h3>
                    </div>
                    <div class="svc-desc"><?php echo wp_kses_post($desc); ?></div>
                </div>
            </li>
            <?php $i++; endwhile; wp_reset_postdata(); ?>
        </ul>
        <?php else : ?>
        <p style="text-align:center;color:var(--text3);padding:4rem 0">Aucun service créé — ajoutez-en dans <em>Services</em> dans l'admin.</p>
        <?php endif; ?>
    </div>
</section>

<section class="cta-section" aria-labelledby="cta-heading">
    <div class="container">
        <div class="cta-band js-reveal">
            <h2 id="cta-heading" class="cta-title"><?php echo esc_html( mc_t('cta_title', 'Ready to start your project?') ); ?></h2>
            <p class="cta-subtitle"><?php echo esc_html( mc_t('cta_subtitle', "Let's discuss your needs and find the best solution together.") ); ?></p>
            <a href="<?php echo esc_url( get_page_link(get_page_by_path('contact')) ?: home_url('/contact/') ); ?>" class="btn btn--primary cta-btn">
                <?php echo esc_html( mc_t('cta_btn', 'Contact me') ); ?>
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M2 7h10M7 2l5 5-5 5"/></svg>
            </a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
