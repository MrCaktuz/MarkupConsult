<?php
/**
 * Fallback template – redirige vers la page d'accueil.
 */
get_header();
?>

<div class="container" style="padding: 8rem 0; text-align:center;">
    <h1><?php _e('Page introuvable', 'markup-consult'); ?></h1>
    <p><a href="<?php echo esc_url(home_url('/')); ?>"><?php _e('← Retour à l\'accueil', 'markup-consult'); ?></a></p>
</div>

<?php
get_footer();
