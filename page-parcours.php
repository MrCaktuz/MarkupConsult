<?php
/**
 * Template Name: Parcours (CV)
 */
get_header();

$career_items = new WP_Query([
    'post_type'      => 'mc_career',
    'posts_per_page' => -1,
    'meta_query'     => [[ 'key' => '_mc_type', 'value' => 'career', 'compare' => '=' ]],
    'orderby'        => [ 'meta_value_num' => 'ASC', 'date' => 'DESC' ],
    'meta_key'       => '_mc_order',
]);

$education_items = new WP_Query([
    'post_type'      => 'mc_career',
    'posts_per_page' => -1,
    'meta_query'     => [[ 'key' => '_mc_type', 'value' => 'education', 'compare' => '=' ]],
    'orderby'        => [ 'meta_value_num' => 'ASC', 'date' => 'DESC' ],
    'meta_key'       => '_mc_order',
]);
?>
<div class="container">
    <div class="parcours-layout">

        <div class="photo-wrap">
            <?php if (has_post_thumbnail()): the_post_thumbnail('medium',['alt'=>esc_attr(mc_t('parcours_name','Mathieu Claessens'))]);
            else: ?><img src="<?php echo esc_url(get_template_directory_uri().'/assets/img/portrait.webp'); ?>" alt="<?php echo esc_attr(mc_t('parcours_name','Mathieu Claessens')); ?>" width="500" height="500" loading="eager"><?php endif; ?>
        </div>

        <div class="title-wrap">
            <h1 class="parcours-title"><?php echo esc_html(mc_t('parcours_name','Mathieu Claessens')); ?></h1>
            <p class="parcours-subtitle"><?php echo esc_html(mc_t('parcours_subtitle','Team Leader & Web Developer')); ?></p>
            <div class="parcours-actions">
                <button class="btn--outline" onclick="window.print()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    <?php echo esc_html(mc_t('pdf_btn','Download as PDF')); ?>
                </button>
            </div>
        </div>

        <div class="contact-mobile">
            <h2 class="aside-section-title">Contact</h2>
            <?php get_template_part('template-parts/contact-cards'); ?>
        </div>

        <aside class="parcours-sidebar">
            <h2 class="aside-section-title">Contact</h2>
            <?php get_template_part('template-parts/contact-cards'); ?>
            <button id="aside-print-btn" class="btn--outline btn--outline-full" onclick="window.print()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                <?php echo esc_html(mc_t('pdf_btn','Download as PDF')); ?>
            </button>
        </aside>

        <div class="sections-wrap">
            <?php if($career_items->have_posts()):?>
            <section class="cv-section" id="sec-career">
                <h2 class="cv-section-title"><?php echo esc_html(mc_t('parcours_career_label','Career')); ?></h2>
                <ul class="timeline-list">
                <?php while($career_items->have_posts()):$career_items->the_post();
                    $pid   = get_the_ID();
                    $from  = get_post_meta($pid,'_mc_date_from',true);
                    $to    = get_post_meta($pid,'_mc_date_to',true);
                    $link  = get_post_meta($pid,'_mc_link',true);
                    $tags  = get_post_meta($pid,'_mc_tags',true);
                    $tlist = $tags ? array_map('trim',explode(';',$tags)) : [];
                    $title = mc_post_t($pid,'title',get_the_title());
                    $desc  = mc_post_t($pid,'content','');
                ?>
                <li class="timeline-item <?php echo $link?'timeline-item-link':''; ?> js-reveal">
                    <?php echo $link?'<a class="cv-card-link" href="'.esc_url($link).'" target="_blank" rel="noopener noreferrer">':'<div class="cv-card">'; ?>
                    <div class="cv-card-legend">
                        <?php if($from):?><span class="cv-legend-pill"><?php echo esc_html($from);?></span><?php endif;?>
                        <?php if($to):?><span class="cv-legend-pill"><?php echo esc_html($to);?></span><?php elseif($from):?><span class="cv-legend-pill"><?php echo date('Y'); ?></span><?php endif;?>
                    </div>
                    <?php if($link):?>
                        <svg class="cv-ext-icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="1em" width="1em">
                            <path d="M15 3h6v6"></path>
                            <path d="M10 14 21 3"></path>
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                        </svg>
                    <?php endif;?>
                    <h3 class="cv-card-title"><?php echo esc_html($title);?></h3>
                    <div class="cv-card-desc"><?php echo wp_kses_post($desc);?></div>
                    <?php if(!empty($tlist)):?>
                    <div class="tag-list"><?php foreach($tlist as $t):?><span class="tag"><?php echo esc_html($t);?></span><?php endforeach;?></div>
                    <?php endif;?>
                    <?php echo $link?'</a>':'</div>';?>
                </li>
                <?php endwhile; wp_reset_postdata();?>
                </ul>
            </section>
            <?php endif;?>

            <?php if($education_items->have_posts()):?>
            <section class="cv-section" id="sec-education">
                <h2 class="cv-section-title"><?php echo esc_html(mc_t('parcours_education_label','Education')); ?></h2>
                <ul class="timeline-list">
                <?php while($education_items->have_posts()):$education_items->the_post();
                    $pid   = get_the_ID();
                    $from  = get_post_meta($pid,'_mc_date_from',true);
                    $to    = get_post_meta($pid,'_mc_date_to',true);
                    $title = mc_post_t($pid,'title',get_the_title());
                    $desc  = mc_post_t($pid,'content','');
                ?>
                <li class="timeline-item js-reveal">
                    <div class="cv-card">
                        <div class="cv-card-legend">
                            <?php if($from):?><span class="cv-legend-pill"><?php echo esc_html($from);?></span><?php endif;?>
                            <?php if($to):?><span class="cv-legend-pill"><?php echo esc_html($to);?></span><?php elseif($from):?><span class="cv-legend-pill"><?php echo date('Y'); ?></span><?php endif;?>
                        </div>
                        <h3 class="cv-card-title"><?php echo esc_html($title);?></h3>
                        <div class="cv-card-desc"><?php echo wp_kses_post($desc);?></div>
                    </div>
                </li>
                <?php endwhile; wp_reset_postdata();?>
                </ul>
            </section>
            <?php endif;?>
        </div>
    </div>
</div>
<?php get_footer(); ?>
