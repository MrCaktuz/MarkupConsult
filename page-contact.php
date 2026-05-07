<?php
/**
 * Template Name: Contact
 */
get_header();
$success = get_transient('mc_contact_success');
$error   = get_transient('mc_contact_error');
if ($success) delete_transient('mc_contact_success');
if ($error)   delete_transient('mc_contact_error');
?>
<div class="container">
    <div class="contact-page">
        <div class="contact-header">
            <h1 class="parcours-title"><?php echo esc_html(mc_t('contact_title',"Let's get in touch")); ?></h1>
            <p class="parcours-subtitle"><?php echo esc_html(mc_t('contact_subtitle','Send me a message and I will reply promptly.')); ?></p>
        </div>
        <?php if($success):?><div class="contact-notice contact-notice--success" role="alert"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg><?php echo esc_html($success);?></div><?php endif;?>
        <?php if($error):?><div class="contact-notice contact-notice--error" role="alert"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><?php echo esc_html($error);?></div><?php endif;?>
        <div class="contact-layout">
            <form class="contact-form" method="post" novalidate>
                <?php wp_nonce_field('mc_contact','mc_contact_nonce');?>
                <div class="form-group">
                    <label class="form-label" for="mc_from"><?php echo esc_html(mc_t('contact_from_label','Your email'));?></label>
                    <input type="email" id="mc_from" name="mc_from" class="form-input" value="<?php echo esc_attr($_POST['mc_from']??'');?>" placeholder="you@example.com">
                </div>
                <div class="form-group">
                    <label class="form-label" for="mc_subject"><?php echo esc_html(mc_t('contact_subject_label','Subject'));?> <span class="form-required" aria-hidden="true">*</span></label>
                    <input type="text" id="mc_subject" name="mc_subject" class="form-input" value="<?php echo esc_attr($_POST['mc_subject']??'');?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="mc_message"><?php echo esc_html(mc_t('contact_message_label','Message'));?> <span class="form-required" aria-hidden="true">*</span></label>
                    <textarea id="mc_message" name="mc_message" class="form-textarea" rows="7" required><?php echo esc_textarea($_POST['mc_message']??'');?></textarea>
                </div>
                <button type="submit" name="mc_contact_submit" class="btn btn--primary contact-submit">
                    <?php echo esc_html(mc_t('contact_btn','Send message'));?>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                </button>
            </form>
            <aside class="contact-aside">
                <h2 class="aside-section-title">Contact</h2>
                <?php get_template_part('template-parts/contact-cards');?>
            </aside>
        </div>
    </div>
</div>
<?php get_footer(); ?>
