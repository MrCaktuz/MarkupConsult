<?php
/**
 * Template Part: Contact cards (sidebar + mobile)
 * Utilisé dans page-parcours.php pour la sidebar et le bloc mobile.
 */
$email    = mc_get_option('mc_email',    'contact@markupconsult.com');
$phone    = mc_get_option('mc_phone',    '+32 476 52 42 85');
$location = mc_get_option('mc_location', 'Liège – BE');
$github   = mc_get_option('mc_github',   'MrCaktuz');
$linkedin = mc_get_option('mc_linkedin', 'mathieuclaessens');
?>
<ul class="aside-list">

    <?php if ( $email ) : ?>
    <li class="aside-item-link">
        <span class="aside-item-label"><?php _e( 'Email', 'markup-consult' ); ?></span>
        <svg class="aside-link-icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="1em" width="1em">
            <path d="M15 3h6v6"></path>
            <path d="M10 14 21 3"></path>
            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
        </svg>
        <a href="mailto:<?php echo esc_attr( $email ); ?>" class="aside-link">
            <?php echo esc_html( $email ); ?>
        </a>
    </li>
    <?php endif; ?>

    <?php if ( $phone ) : ?>
    <li class="aside-item-link">
        <span class="aside-item-label"><?php _e( 'Téléphone', 'markup-consult' ); ?></span>
        <svg class="aside-link-icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="1em" width="1em">
            <path d="M15 3h6v6"></path>
            <path d="M10 14 21 3"></path>
            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
        </svg>
        <a href="tel:<?php echo esc_attr( preg_replace('/\s+/', '', $phone) ); ?>" class="aside-link">
            <?php echo esc_html( $phone ); ?>
        </a>
    </li>
    <?php endif; ?>

    <?php if ( $location ) : ?>
    <li class="aside-item">
        <span class="aside-item-label"><?php _e( 'Localisation', 'markup-consult' ); ?></span>
        <span class="aside-link"><?php echo esc_html( $location ); ?></span>
    </li>
    <?php endif; ?>

    <?php if ( $github ) : ?>
    <li class="aside-item-link">
        <span class="aside-item-label">GitHub</span>
        <svg class="aside-link-icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="1em" width="1em">
            <path d="M15 3h6v6"></path>
            <path d="M10 14 21 3"></path>
            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
        </svg>
        <a href="https://github.com/<?php echo esc_attr( $github ); ?>" target="_blank" rel="noopener noreferrer" class="aside-link">
            /<?php echo esc_html( $github ); ?>
        </a>
    </li>
    <?php endif; ?>

    <?php if ( $linkedin ) : ?>
    <li class="aside-item-link">
        <span class="aside-item-label">LinkedIn</span>
        <svg class="aside-link-icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="1em" width="1em">
            <path d="M15 3h6v6"></path>
            <path d="M10 14 21 3"></path>
            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
        </svg>
        <a href="https://linkedin.com/in/<?php echo esc_attr( $linkedin ); ?>" target="_blank" rel="noopener noreferrer" class="aside-link">
            /<?php echo esc_html( $linkedin ); ?>
        </a>
    </li>
    <?php endif; ?>

</ul>
