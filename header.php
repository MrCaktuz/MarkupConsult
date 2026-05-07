<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">

    <header id="masthead" class="site-header" role="banner">
        <div class="container">
            <div class="header-inner">

                <a href="<?php echo esc_url( home_url('/') ); ?>" class="brand" aria-label="<?php bloginfo('name'); ?> – Accueil">
                    <svg class="brand-logo" xmlns="http://www.w3.org/2000/svg" width="150" height="150" viewBox="0 0 150 150" fill="none">
                        <circle cx="75" cy="75" r="72.5" fill="#263238" stroke="#455C63" stroke-width="5"/>
                        <path d="M95.9143 58.9085C97.9034 57.0841 100.225 55.9807 102.735 55.9807C106.688 55.9807 110.164 57.6243 113.793 61.5688L121.91 53.5403C117.146 47.1313 110.33 43.0898 102.735 43.0898C97.5333 43.0898 92.1006 44.7862 88.6308 48.2414L62.9535 73.8787L32.09 43.0898V107.547H45.1229V74.0559L62.8035 91.7215L95.9143 58.9085Z" fill="#EDF0F2"/>
                        <path d="M101.126 94.6552C94.0236 94.6552 88.2729 86.2149 88.1216 75.7308L76.8721 86.8582C80.6346 98.933 90.0343 107.547 101.126 107.547C109.626 107.547 117.152 102.494 121.91 94.7107L113.793 86.7041C109.741 91.5389 105.884 94.6552 101.126 94.6552Z" fill="#F58020"/>
                    </svg>
                    <span class="brand-name">Markup <em>Consult</em></span>
                </a>

                <nav class="site-nav" id="site-nav" role="navigation" aria-label="Navigation principale">
                    <a href="<?php echo esc_url( home_url('/#services') ); ?>" class="nav-link <?php echo is_front_page() ? 'is-active' : ''; ?>">
                        <?php echo esc_html( mc_t('nav_services', 'Services') ); ?>
                    </a>
                    <a href="<?php echo esc_url( get_page_link( get_page_by_path('parcours') ) ?: home_url('/parcours/') ); ?>"
                       class="nav-link <?php echo is_page('parcours') ? 'is-active' : ''; ?>">
                        <?php echo esc_html( mc_t('nav_parcours', 'Portfolio') ); ?>
                    </a>
                    <a href="<?php echo esc_url( get_page_link( get_page_by_path('contact') ) ?: home_url('/contact/') ); ?>"
                       class="nav-cta <?php echo is_page('contact') ? 'is-active' : ''; ?>">
                        <?php echo esc_html( mc_t('nav_contact', 'Contact me') ); ?>
                    </a>

                    <?php
                    $active_langs = mc_get_active_langs();
                    if ( count($active_langs) > 1 ) :
                        $current = mc_current_lang();
                    ?>
                    <div class="lang-switcher" aria-label="Choisir la langue">
                        <button class="lang-current" aria-expanded="false" aria-haspopup="listbox">
                            <span><?php echo strtoupper($current); ?></span>
                            <svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.5" width="10" height="10" aria-hidden="true">
                                <path d="M2 4l4 4 4-4"/>
                            </svg>
                        </button>
                        <ul class="lang-dropdown" role="listbox" aria-label="Langues disponibles">
                            <?php foreach ($active_langs as $code => $label) : ?>
                            <li role="option" <?php echo $code === $current ? 'aria-selected="true"' : ''; ?>>
                                <a href="<?php echo esc_url( add_query_arg('lang', $code) ); ?>"
                                   class="lang-option <?php echo $code === $current ? 'is-active' : ''; ?>">
                                    <span class="lang-code"><?php echo strtoupper($code); ?></span>
                                    <span class="lang-name"><?php echo esc_html($label); ?></span>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                </nav>

                <button class="nav-toggle" id="nav-toggle" aria-expanded="false" aria-controls="site-nav" aria-label="Ouvrir le menu">
                    <span></span><span></span><span></span>
                </button>

            </div>
        </div>
    </header>

    <main id="main" class="site-main">
