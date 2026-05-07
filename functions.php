<?php
/**
 * Markup Consult – functions.php v5
 * - Langues configurables (ajout/suppression depuis l'admin)
 * - Options textes par onglet de langue
 * - Meta boxes CPT avec onglets par langue
 * - Admin nettoyé
 * - Switcher de langue (cookie + ?lang=xx)
 * - Fallback EN, langue par défaut EN
 */
if ( ! defined('ABSPATH') ) exit;

/* ══════════════════════════════════════════════════════
   HELPERS LANGUE
══════════════════════════════════════════════════════ */

function mc_get_active_langs() {
    $saved  = (array) get_option('mc_active_langs', []);
    $all    = mc_all_langs();
    $result = ['en' => $all['en']];
    foreach ($saved as $code) {
        if (isset($all[$code]) && $code !== 'en') $result[$code] = $all[$code];
    }
    return $result;
}

function mc_all_langs() {
    return [
        'en' => 'English',   'fr' => 'Français',   'nl' => 'Nederlands',
        'de' => 'Deutsch',   'es' => 'Español',    'it' => 'Italiano',
        'pt' => 'Português', 'pl' => 'Polski',
    ];
}

function mc_current_lang() {
    static $lang = null;
    if ($lang !== null) return $lang;
    $active = array_keys(mc_get_active_langs());
    if (!empty($_GET['lang']) && in_array($_GET['lang'], $active, true)) {
        $lang = sanitize_key($_GET['lang']);
        if (!headers_sent()) setcookie('mc_lang', $lang, time() + 60*60*24*30, '/');
        return $lang;
    }
    if (!empty($_COOKIE['mc_lang']) && in_array($_COOKIE['mc_lang'], $active, true)) {
        $lang = sanitize_key($_COOKIE['mc_lang']);
        return $lang;
    }
    $lang = 'en';
    return $lang;
}

/** Texte de page depuis options, fallback EN, fallback $default */
function mc_t($key, $default = '') {
    $lang = mc_current_lang();
    $v    = get_option("mc_{$key}_{$lang}", '');
    if ($v !== '') return $v;
    $v_en = get_option("mc_{$key}_en", '');
    return $v_en !== '' ? $v_en : $default;
}

/** Texte depuis post meta, fallback EN, fallback $fallback */
function mc_post_t($post_id, $key, $fallback = '') {
    $lang = mc_current_lang();
    $v    = get_post_meta($post_id, "_mc_{$key}_{$lang}", true);
    if ($v !== '' && $v !== false) return $v;
    $v_en = get_post_meta($post_id, "_mc_{$key}_en", true);
    if ($v_en !== '' && $v_en !== false) return $v_en;
    return $fallback;
}

/* ══════════════════════════════════════════════════════
   1. THEME SETUP
══════════════════════════════════════════════════════ */
function mc_setup() {
    load_theme_textdomain('markup-consult', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption']);
    add_theme_support('custom-logo');
    register_nav_menus(['primary' => 'Menu principal']);
}
add_action('after_setup_theme', 'mc_setup');
add_filter('show_admin_bar', '__return_false');
/* ══════════════════════════════════════════════════════
   2. ENQUEUE
══════════════════════════════════════════════════════ */
function mc_enqueue_assets() {
    $ver = wp_get_theme()->get('Version');
    wp_enqueue_style('mc-fonts', 'https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,300;0,400;0,700;1,300;1,400&display=swap', [], null);
    wp_enqueue_style('mc-main', get_template_directory_uri() . '/assets/css/main.css', ['mc-fonts'], $ver);
    wp_enqueue_script('mc-main', get_template_directory_uri() . '/assets/js/main.js', [], $ver, true);
    wp_localize_script('mc-main', 'mcLang', [
        'current' => mc_current_lang(),
        'active'  => mc_get_active_langs(),
    ]);
}
add_action('wp_enqueue_scripts', 'mc_enqueue_assets');

/* ══════════════════════════════════════════════════════
   3. CPT SERVICE
══════════════════════════════════════════════════════ */
function mc_register_cpt_service() {
    register_post_type('mc_service', [
        'labels'       => ['name'=>'Services','singular_name'=>'Service','add_new_item'=>'Ajouter un service','edit_item'=>'Modifier le service'],
        'public'       => false, 'show_ui' => true, 'show_in_rest' => false,
        'menu_icon'    => 'dashicons-clipboard',
        'supports'     => ['title'],
        'rewrite'      => false,
    ]);
}
add_action('init', 'mc_register_cpt_service');

/* ══════════════════════════════════════════════════════
   4. CPT CAREER
══════════════════════════════════════════════════════ */
function mc_register_cpt_career() {
    register_post_type('mc_career', [
        'labels'       => ['name'=>'Parcours','singular_name'=>'Expérience / Formation','add_new_item'=>'Ajouter une entrée','edit_item'=>"Modifier l'entrée"],
        'public'       => false, 'show_ui' => true, 'show_in_rest' => false,
        'menu_icon'    => 'dashicons-businessman',
        'supports'     => ['title'],
        'rewrite'      => false,
    ]);
}
add_action('init', 'mc_register_cpt_career');

/* ══════════════════════════════════════════════════════
   5. GUTENBERG OFF
══════════════════════════════════════════════════════ */
add_filter('use_block_editor_for_post_type', function($use, $post_type) {
    return in_array($post_type, ['mc_service','mc_career','page']) ? false : $use;
}, 10, 2);

/* ══════════════════════════════════════════════════════
   6. NETTOYAGE ADMIN
══════════════════════════════════════════════════════ */
add_action('admin_menu', function() {
    remove_menu_page('edit.php');
    remove_menu_page('edit-comments.php');
});

// Colonnes CPT
foreach (['mc_service','mc_career'] as $_cpt) {
    add_filter("manage_{$_cpt}_posts_columns", function($cols) {
        unset($cols['date']);
        $cols['mc_order'] = 'Ordre';
        return $cols;
    });
    add_action("manage_{$_cpt}_posts_custom_column", function($col, $pid) {
        if ($col === 'mc_order') echo esc_html(get_post_meta($pid,'_mc_order',true) ?: '—');
    }, 10, 2);
}

/* ══════════════════════════════════════════════════════
   7. META BOXES
══════════════════════════════════════════════════════ */
add_action('add_meta_boxes', function() {
    add_meta_box('mc_service_meta', 'Icône & Contenu',   'mc_service_meta_cb', 'mc_service', 'normal', 'high');
    add_meta_box('mc_career_meta',  'Détails & Contenu', 'mc_career_meta_cb',  'mc_career',  'normal', 'high');
});

/** Rendu des onglets de langue pour un post */
function mc_render_lang_tabs($post_id, $fields) {
    $langs = mc_get_active_langs();
    $first = array_key_first($langs);
    static $css_printed = false;
    if (!$css_printed) { $css_printed = true; mc_admin_tabs_assets(); }
    ?>
    <div class="mc-lang-tabs">
      <div class="mc-lang-tab-nav">
        <?php foreach ($langs as $code => $label) : ?>
        <button type="button" class="mc-tab-btn<?php echo $code===$first?' active':''; ?>" data-lang="<?php echo esc_attr($code); ?>">
          <?php echo esc_html($label); ?><?php if($code==='en'):?> <span class="mc-badge">EN</span><?php endif;?>
        </button>
        <?php endforeach; ?>
      </div>
      <?php foreach ($langs as $code => $label) : ?>
      <div class="mc-tab-pane<?php echo $code===$first?' active':''; ?>" data-lang="<?php echo esc_attr($code); ?>">
        <?php foreach ($fields as $f) :
          $name = "mc_{$f['key']}_{$code}";
          $val  = get_post_meta($post_id, "_mc_{$f['key']}_{$code}", true);
        ?>
        <p>
          <label style="font-weight:600;font-size:13px"><?php echo esc_html($f['label']); ?>
            <?php if($code==='en'):?><span style="color:#f58020;font-size:11px"> ★</span><?php endif;?>
          </label><br>
          <?php if ($f['type']==='textarea'): ?>
          <textarea name="<?php echo esc_attr($name); ?>" style="width:100%;min-height:80px"><?php echo esc_textarea($val); ?></textarea>
          <?php else: ?>
          <input type="text" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($val); ?>" style="width:100%">
          <?php endif; ?>
        </p>
        <?php endforeach; ?>
      </div>
      <?php endforeach; ?>
    </div>
    <?php
}

function mc_admin_tabs_assets() { ?>
<style>
.mc-lang-tabs{margin-top:12px}
.mc-lang-tab-nav{display:flex;flex-wrap:wrap;gap:3px;border-bottom:2px solid #ddd;margin-bottom:10px}
.mc-tab-btn{padding:5px 12px;border:none;background:#f1f1f1;cursor:pointer;border-radius:4px 4px 0 0;font-size:12px;color:#555;border-bottom:2px solid transparent;margin-bottom:-2px;transition:all .15s}
.mc-tab-btn:hover{background:#e8e8e8;color:#333}
.mc-tab-btn.active{background:#fff;color:#f58020;border-bottom-color:#f58020;font-weight:700}
.mc-badge{font-size:10px;background:#f58020;color:#fff;border-radius:3px;padding:1px 4px;margin-left:3px}
.mc-tab-pane{display:none}
.mc-tab-pane.active{display:block}
.mc-icon-grid{display:flex;flex-wrap:wrap;gap:5px;margin:6px 0 10px}
.mc-icon-opt{width:38px;height:38px;border:2px solid #ddd;border-radius:6px;display:flex;align-items:center;justify-content:center;cursor:pointer;background:#f9f9f9;flex-shrink:0}
.mc-icon-opt:hover{border-color:#f58020}
.mc-icon-opt.mc-selected{border-color:#f58020;background:#fff3e8}
.mc-icon-opt svg{width:18px;height:18px;pointer-events:none}
.mc-color-row{display:flex;align-items:center;gap:8px;margin:4px 0}
.mc-preview-wrap{width:48px;height:48px;border-radius:8px;display:flex;align-items:center;justify-content:center;margin:6px 0}
.mc-preview-wrap svg{width:24px;height:24px}
</style>
<script>
document.addEventListener('click',function(e){
  var btn=e.target.closest('.mc-tab-btn');
  if(!btn)return;
  var c=btn.closest('.mc-lang-tabs'),l=btn.dataset.lang;
  c.querySelectorAll('.mc-tab-btn').forEach(function(b){b.classList.remove('active')});
  c.querySelectorAll('.mc-tab-pane').forEach(function(p){p.classList.remove('active')});
  btn.classList.add('active');
  var pane=c.querySelector('.mc-tab-pane[data-lang="'+l+'"]');
  if(pane)pane.classList.add('active');
});
</script>
<?php }

/* Meta box Service */
function mc_service_meta_cb($post) {
    wp_nonce_field('mc_service_nonce','mc_service_nonce');
    $order   = get_post_meta($post->ID,'_mc_order',true);
    $isvg    = get_post_meta($post->ID,'_mc_icon_svg',true);
    $icolor  = get_post_meta($post->ID,'_mc_icon_color',true) ?: '#99dbf6';
    $ibgcol  = get_post_meta($post->ID,'_mc_icon_bg_color',true) ?: '#99dbf6';
    $ikey    = get_post_meta($post->ID,'_mc_icon_preset',true) ?: 'code';
    $icons   = mc_preset_icons();
    $cursvg  = $isvg ?: ($icons[$ikey] ?? $icons['code']);
    ?>
    <div style="display:flex;gap:20px;flex-wrap:wrap;align-items:flex-start">
    <div style="flex:0 0 auto;min-width:160px">
      <p style="font-weight:600;margin-bottom:6px">Icône</p>
      <div class="mc-icon-grid" id="mc_icon_grid">
        <?php foreach($icons as $k=>$svg):?>
        <div class="mc-icon-opt<?php echo $ikey===$k?' mc-selected':'';?>" data-key="<?php echo esc_attr($k);?>"><?php echo $svg;?></div>
        <?php endforeach;?>
      </div>
      <input type="hidden" name="mc_icon_preset" id="mc_icon_preset" value="<?php echo esc_attr($ikey);?>">
      <input type="hidden" name="mc_icon_svg"    id="mc_icon_svg"    value="<?php echo esc_attr($cursvg);?>">
      <div class="mc-color-row">
        <input type="color" name="mc_icon_color"    id="mc_icon_color"    value="<?php echo esc_attr($icolor);?>">
        <span style="font-size:11px">Couleur</span>
      </div>
      <div class="mc-color-row">
        <input type="color" name="mc_icon_bg_color" id="mc_icon_bg_color" value="<?php echo esc_attr($ibgcol);?>">
        <span style="font-size:11px">Fond</span>
      </div>
      <div id="mc_preview_wrap" class="mc-preview-wrap" style="background:<?php echo esc_attr($ibgcol);?>33;color:<?php echo esc_attr($icolor);?>"><?php echo $cursvg;?></div>
      <p style="margin-top:8px;font-weight:600">Ordre</p>
      <input type="number" name="mc_order" value="<?php echo esc_attr($order);?>" style="width:70px" placeholder="1">
    </div>
    <div style="flex:1;min-width:240px">
      <?php mc_render_lang_tabs($post->ID,[
        ['key'=>'title',  'label'=>'Titre',       'type'=>'text'],
        ['key'=>'content','label'=>'Description',  'type'=>'textarea'],
      ]); ?>
    </div>
    </div>
    <script>
    (function(){
      var icons=<?php echo wp_json_encode(mc_preset_icons(), JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP);?>;
      function upPreview(){
        var w=document.getElementById('mc_preview_wrap');
        w.style.color=document.getElementById('mc_icon_color').value;
        w.style.background=document.getElementById('mc_icon_bg_color').value+'33';
      }
      document.getElementById('mc_icon_grid').addEventListener('click',function(e){
        var opt=e.target.closest('.mc-icon-opt');
        if(!opt||!icons[opt.dataset.key])return;
        document.querySelectorAll('.mc-icon-opt').forEach(function(el){el.classList.remove('mc-selected')});
        opt.classList.add('mc-selected');
        document.getElementById('mc_icon_preset').value=opt.dataset.key;
        document.getElementById('mc_icon_svg').value=icons[opt.dataset.key];
        document.getElementById('mc_preview_wrap').innerHTML=icons[opt.dataset.key];
        upPreview();
      });
      document.getElementById('mc_icon_color').addEventListener('input',upPreview);
      document.getElementById('mc_icon_bg_color').addEventListener('input',upPreview);
    })();
    </script>
    <?php
}

/* Meta box Career */
function mc_career_meta_cb($post) {
    wp_nonce_field('mc_career_nonce','mc_career_nonce');
    $df=$post->ID; // shorthand
    ?>
    <div style="display:flex;gap:20px;flex-wrap:wrap;align-items:flex-start">
    <div style="flex:0 0 180px">
      <p><label><b>Type</b></label><br>
      <select name="mc_type" style="width:100%">
        <option value="career"    <?php selected(get_post_meta($df,'_mc_type',true),'career');    ?>>Expérience pro</option>
        <option value="education" <?php selected(get_post_meta($df,'_mc_type',true),'education'); ?>>Formation</option>
      </select></p>
      <p><label><b>De</b></label><br><input type="text" name="mc_date_from" value="<?php echo esc_attr(get_post_meta($df,'_mc_date_from',true));?>" style="width:100%" placeholder="2023"></p>
      <p><label><b>À</b> <em style="font-weight:400;font-size:11px">(vide=actuel)</em></label><br><input type="text" name="mc_date_to" value="<?php echo esc_attr(get_post_meta($df,'_mc_date_to',true));?>" style="width:100%" placeholder="2025"></p>
      <p><label><b>Tags</b> <em style="font-weight:400;font-size:11px">(séparés par ;)</em></label><br><input type="text" name="mc_tags" value="<?php echo esc_attr(get_post_meta($df,'_mc_tags',true));?>" style="width:100%"></p>
      <p><label><b>Lien</b></label><br><input type="url" name="mc_link" value="<?php echo esc_attr(get_post_meta($df,'_mc_link',true));?>" style="width:100%" placeholder="https://"></p>
      <p><label><b>Ordre</b></label><br><input type="number" name="mc_order" value="<?php echo esc_attr(get_post_meta($df,'_mc_order',true));?>" style="width:70px"></p>
    </div>
    <div style="flex:1;min-width:240px">
      <?php mc_render_lang_tabs($post->ID,[
        ['key'=>'title',  'label'=>'Titre du poste', 'type'=>'text'],
        ['key'=>'content','label'=>'Description',    'type'=>'textarea'],
      ]); ?>
    </div>
    </div>
    <?php
}

/* ══════════════════════════════════════════════════════
   8. SAVE META
══════════════════════════════════════════════════════ */
add_action('save_post_mc_service', function($pid) {
    if (!isset($_POST['mc_service_nonce'])||!wp_verify_nonce($_POST['mc_service_nonce'],'mc_service_nonce')) return;
    if (defined('DOING_AUTOSAVE')&&DOING_AUTOSAVE) return;
    foreach(['mc_order'=>'_mc_order','mc_icon_preset'=>'_mc_icon_preset','mc_icon_color'=>'_mc_icon_color','mc_icon_bg_color'=>'_mc_icon_bg_color'] as $k=>$mk) {
        if(isset($_POST[$k])) update_post_meta($pid,$mk,sanitize_text_field($_POST[$k]));
    }
    if(isset($_POST['mc_icon_svg'])) update_post_meta($pid,'_mc_icon_svg',wp_kses($_POST['mc_icon_svg'],mc_svg_kses()));
    if(isset($_POST['mc_icon_bg_color'])) {
        $hex=sanitize_hex_color($_POST['mc_icon_bg_color'])?:'#99dbf6';
        update_post_meta($pid,'_mc_icon_bg',$hex);
    }
    foreach(array_keys(mc_all_langs()) as $lc) {
        foreach(['title','content'] as $f) {
            $k="mc_{$f}_{$lc}";
            if(!isset($_POST[$k])) continue;
            update_post_meta($pid,"_mc_{$f}_{$lc}",$f==='content'?wp_kses_post($_POST[$k]):sanitize_text_field($_POST[$k]));
        }
    }
});

add_action('save_post_mc_career', function($pid) {
    if (!isset($_POST['mc_career_nonce'])||!wp_verify_nonce($_POST['mc_career_nonce'],'mc_career_nonce')) return;
    if (defined('DOING_AUTOSAVE')&&DOING_AUTOSAVE) return;
    foreach(['mc_date_from'=>'_mc_date_from','mc_date_to'=>'_mc_date_to','mc_tags'=>'_mc_tags','mc_link'=>'_mc_link','mc_type'=>'_mc_type','mc_order'=>'_mc_order'] as $k=>$mk) {
        if(isset($_POST[$k])) update_post_meta($pid,$mk,sanitize_text_field($_POST[$k]));
    }
    foreach(array_keys(mc_all_langs()) as $lc) {
        foreach(['title','content'] as $f) {
            $k="mc_{$f}_{$lc}";
            if(!isset($_POST[$k])) continue;
            update_post_meta($pid,"_mc_{$f}_{$lc}",$f==='content'?wp_kses_post($_POST[$k]):sanitize_text_field($_POST[$k]));
        }
    }
});

/* ══════════════════════════════════════════════════════
   9. FORMULAIRE CONTACT
══════════════════════════════════════════════════════ */
add_action('init', function() {
    if (!isset($_POST['mc_contact_nonce'])||!wp_verify_nonce($_POST['mc_contact_nonce'],'mc_contact')) return;
    if (!isset($_POST['mc_contact_submit'])) return;
    $subject = sanitize_text_field($_POST['mc_subject']??'');
    $message = sanitize_textarea_field($_POST['mc_message']??'');
    $from    = sanitize_email($_POST['mc_from']??'');
    if (empty($subject)||empty($message)) {
        set_transient('mc_contact_error','Please fill in all required fields.',30);
        wp_safe_redirect(wp_get_referer()?:home_url('/contact/')); exit;
    }
    $headers = ($from&&is_email($from)) ? ['Reply-To: '.$from] : [];
    $body    = "From: ".($from?:'N/A')."\nSubject: $subject\n\n$message";
    $sent    = wp_mail(get_option('admin_email'),'[Markup Consult] '.$subject,$body,$headers);
    if ($sent) set_transient('mc_contact_success', mc_t('contact_success','Your message has been sent successfully!'),30);
    else       set_transient('mc_contact_error','An error occurred. Please try again.',30);
    wp_safe_redirect(wp_get_referer()?:home_url('/contact/')); exit;
});

/* ══════════════════════════════════════════════════════
   10. PAGE D'OPTIONS
══════════════════════════════════════════════════════ */
add_action('admin_menu', function() {
    add_theme_page('Options Markup Consult','Options MC','manage_options','mc-options','mc_options_page_cb');
});

function mc_options_page_cb() {
    $all_langs    = mc_all_langs();
    $active_langs = mc_get_active_langs();
    $tab          = sanitize_key($_GET['tab'] ?? 'settings');

    /* Sauvegarde */
    if (isset($_POST['mc_options_nonce']) && wp_verify_nonce($_POST['mc_options_nonce'],'mc_options')) {
        if ($tab === 'settings') {
            $new = ['en'];
            foreach ((array)($_POST['mc_langs']??[]) as $l) {
                if (isset($all_langs[$l]) && $l!=='en') $new[] = sanitize_key($l);
            }
            update_option('mc_active_langs',$new);
            foreach(['mc_email','mc_phone','mc_location','mc_github','mc_linkedin'] as $f) {
                if(isset($_POST[$f])) update_option($f,sanitize_text_field($_POST[$f]));
            }
        } elseif (isset($active_langs[$tab])) {
            foreach(mc_text_sections() as $fields) {
                foreach(array_keys($fields) as $key) {
                    $pk="mc_{$key}_{$tab}";
                    if(isset($_POST[$pk])) update_option($pk,sanitize_textarea_field($_POST[$pk]));
                }
            }
        }
        echo '<div class="notice notice-success is-dismissible"><p>Sauvegardé !</p></div>';
        $active_langs = mc_get_active_langs();
    }

    /* Onglets */
    $tabs = ['settings'=>'⚙ Paramètres'];
    foreach($active_langs as $code=>$label) $tabs[$code]=$label;
    ?>
    <div class="wrap">
    <h1>Options – Markup Consult</h1>
    <nav class="nav-tab-wrapper">
    <?php foreach($tabs as $k=>$l):?>
    <a href="<?php echo esc_url(add_query_arg(['page'=>'mc-options','tab'=>$k],admin_url('themes.php')));?>"
       class="nav-tab<?php echo $tab===$k?' nav-tab-active':'';?>">
      <?php echo esc_html($l);?>
      <?php if($k==='en'):?><span style="font-size:10px;background:#f58020;color:#fff;border-radius:3px;padding:1px 4px;margin-left:4px">défaut</span><?php endif;?>
    </a>
    <?php endforeach;?>
    </nav>
    <form method="post" style="margin-top:20px">
    <?php wp_nonce_field('mc_options','mc_options_nonce');?>

    <?php if($tab==='settings'):?>
    <h2>Langues actives</h2>
    <p class="description">L'anglais est toujours présent (fallback). Cochez les langues supplémentaires.</p>
    <table class="form-table"><tr><th>Langues</th><td>
    <?php foreach($all_langs as $code=>$label):
      if($code==='en') continue;
      $checked=in_array($code,array_keys($active_langs));
    ?>
    <label style="display:inline-flex;align-items:center;gap:5px;margin:4px 12px 4px 0">
      <input type="checkbox" name="mc_langs[]" value="<?php echo esc_attr($code);?>" <?php checked($checked);?>>
      <?php echo esc_html($label);?>
    </label>
    <?php endforeach;?>
    <p class="description">Après sauvegarde, un onglet par langue apparaît pour saisir les traductions.</p>
    </td></tr></table>

    <h2>Coordonnées <span style="font-weight:400;font-size:13px;color:#555">(indépendantes de la langue)</span></h2>
    <table class="form-table">
    <?php
    mc_opt_simple('mc_email',   'Email',             'contact@markupconsult.com');
    mc_opt_simple('mc_phone',   'Téléphone',         '+32 476 52 42 85');
    mc_opt_simple('mc_location','Localisation',      'Liège – BE');
    mc_opt_simple('mc_github',  'GitHub (handle)',   'MrCaktuz');
    mc_opt_simple('mc_linkedin','LinkedIn (handle)', 'mathieuclaessens');
    ?>
    </table>

    <?php elseif(isset($active_langs[$tab])):
        $is_default = ($tab==='en');
        $lang_label = $active_langs[$tab];
    ?>
    <p style="color:#555;margin-bottom:16px">
        Textes affichés en <strong><?php echo esc_html($lang_label);?></strong>.
        <?php if($is_default):?>
        <span style="color:#f58020;font-weight:600">★ Langue par défaut — sert de fallback si une traduction est manquante.</span>
        <?php else:?>
        Les champs vides afficheront le texte anglais.
        <?php endif;?>
    </p>
    <?php foreach(mc_text_sections() as $section=>$fields):?>
    <h2><?php echo esc_html($section);?></h2>
    <table class="form-table">
    <?php foreach($fields as $key=>$f):
        $opt_key="mc_{$key}_{$tab}";
        $default=$is_default?($f['default']??''):'';
        $val=get_option($opt_key,$default);
    ?>
    <tr><th><label for="<?php echo esc_attr($opt_key);?>"><?php echo esc_html($f['label']);?>
        <?php if($is_default):?><span style="color:#f58020">★</span><?php endif;?>
    </label></th><td>
    <?php if(!empty($f['textarea'])):?>
    <textarea id="<?php echo esc_attr($opt_key);?>" name="<?php echo esc_attr($opt_key);?>" class="large-text" rows="3"><?php echo esc_textarea($val);?></textarea>
    <?php else:?>
    <input type="text" id="<?php echo esc_attr($opt_key);?>" name="<?php echo esc_attr($opt_key);?>" value="<?php echo esc_attr($val);?>" class="regular-text">
    <?php endif;?>
    </td></tr>
    <?php endforeach;?>
    </table>
    <?php endforeach;?>
    <?php endif;?>

    <?php submit_button('Sauvegarder');?>
    </form>
    </div>
    <?php
}

function mc_opt_simple($key,$label,$default) {
    $val=get_option($key,$default);
    echo "<tr><th><label for='".esc_attr($key)."'>".esc_html($label)."</label></th>";
    echo "<td><input type='text' id='".esc_attr($key)."' name='".esc_attr($key)."' value='".esc_attr($val)."' class='regular-text'></td></tr>";
}

function mc_text_sections() {
    return [
        'Navigation' => [
            'nav_services' => ['label'=>'Lien Services',    'default'=>'Services'],
            'nav_parcours' => ['label'=>'Lien Portfolio',   'default'=>'Portfolio'],
            'nav_contact'  => ['label'=>'Bouton Contact',   'default'=>'Contact me'],
        ],
        'Hero' => [
            'hero_line1'    => ['label'=>'Ligne 1',         'default'=>'Turn your ideas into'],
            'hero_line2'    => ['label'=>'Ligne 2 (colorée)','default'=>'digital experiences'],
            'hero_subtitle' => ['label'=>'Sous-titre',      'default'=>'Passionate Front-End developer and experienced Team Leader, I build modern and performant web applications while guiding your teams towards technical excellence.','textarea'=>true],
            'hero_btn1'     => ['label'=>'Bouton principal','default'=>'Discover my portfolio'],
            'hero_btn2'     => ['label'=>'Bouton secondaire','default'=>'My services'],
        ],
        'Section Services' => [
            'services_title'    => ['label'=>'Titre',       'default'=>'Services'],
            'services_subtitle' => ['label'=>'Sous-titre',  'default'=>'A complete expertise for your web projects, from development to team management','textarea'=>true],
        ],
        'Section CTA' => [
            'cta_title'    => ['label'=>'Titre',            'default'=>'Ready to start your project?'],
            'cta_subtitle' => ['label'=>'Sous-titre',       'default'=>"Let's discuss your needs and find the best solution together."],
            'cta_btn'      => ['label'=>'Texte bouton',     'default'=>'Contact me'],
        ],
        'Page Portfolio / Parcours' => [
            'parcours_name'            => ['label'=>'Nom',                 'default'=>'Mathieu Claessens'],
            'parcours_subtitle'        => ['label'=>'Sous-titre',          'default'=>'Team Leader & Web Developer — A blend of good humour, rigour and kindness.','textarea'=>true],
            'parcours_career_label'    => ['label'=>'Label Carrière',      'default'=>'Career'],
            'parcours_education_label' => ['label'=>'Label Formation',     'default'=>'Education'],
            'pdf_btn'                  => ['label'=>'Bouton PDF',          'default'=>'Download as PDF'],
        ],
        'Page Contact' => [
            'contact_title'         => ['label'=>'Titre',                 'default'=>"Let's get in touch"],
            'contact_subtitle'      => ['label'=>'Sous-titre',            'default'=>'An idea, a project, a question? Send me a message and I will reply promptly.','textarea'=>true],
            'contact_from_label'    => ['label'=>'Label "Votre email"',   'default'=>'Your email'],
            'contact_subject_label' => ['label'=>'Label "Sujet"',         'default'=>'Subject'],
            'contact_message_label' => ['label'=>'Label "Message"',       'default'=>'Message'],
            'contact_btn'           => ['label'=>'Bouton envoi',          'default'=>'Send message'],
            'contact_success'       => ['label'=>'Message confirmation',  'default'=>'Your message has been sent successfully!'],
        ],
    ];
}

/* ══════════════════════════════════════════════════════
   11. HELPERS ICÔNES / OPTIONS
══════════════════════════════════════════════════════ */
function mc_get_option($key,$default='') { return get_option($key,$default); }

function mc_get_service_icon_html($post_id,$index=0) {
    $defaults=array_values(mc_preset_icons());
    $svg=get_post_meta($post_id,'_mc_icon_svg',true);
    return $svg?:($defaults[$index%count($defaults)]);
}

function mc_get_service_icon_style($post_id,$index=0) {
    $def=['#99dbf6','#99dbf6','#f58020','#99dbf6'];
    $bg   =get_post_meta($post_id,'_mc_icon_bg',true);
    $color=get_post_meta($post_id,'_mc_icon_color',true)?:($def[$index%4]);
    $bg   =$bg?:($def[$index%4].'33');
    return 'background:'.esc_attr($bg).';color:'.esc_attr($color).';';
}

function mc_preset_icons() {
    return [
        'code'     =>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>',
        'team'     =>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/><path d="M18 8c1.1.5 2 1.8 2 3.2M20 20c0-2.5-1.4-4.5-3.5-5.5"/></svg>',
        'lightning'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>',
        'grid'     =>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>',
        'eye'      =>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>',
        'settings' =>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>',
        'mobile'   =>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>',
        'globe'    =>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>',
        'chart'    =>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>',
        'palette'  =>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="13.5" cy="6.5" r=".5" fill="currentColor"/><circle cx="17.5" cy="10.5" r=".5" fill="currentColor"/><circle cx="8.5" cy="7.5" r=".5" fill="currentColor"/><circle cx="6.5" cy="12.5" r=".5" fill="currentColor"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"/></svg>',
    ];
}

function mc_svg_kses() {
    return [
        'svg'=>['viewBox'=>[],'fill'=>[],'stroke'=>[],'stroke-width'=>[],'stroke-linecap'=>[],'stroke-linejoin'=>[],'width'=>[],'height'=>[]],
        'path'=>['d'=>[],'fill'=>[],'stroke'=>[]],'polyline'=>['points'=>[]],'circle'=>['cx'=>[],'cy'=>[],'r'=>[],'fill'=>[]],
        'rect'=>['x'=>[],'y'=>[],'width'=>[],'height'=>[],'rx'=>[],'ry'=>[]],'line'=>['x1'=>[],'y1'=>[],'x2'=>[],'y2'=>[]],
    ];
}
