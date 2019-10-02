<?php
/**
 * Table of contents
 * 
 * page_takeover_admin_enqueues ( Enqueue Admin Scripts and Styles )
 * page_takeover_admin_settings_page_add ( Add menu page for settings )
 * page_takeover_admin_settings_page_output ( Output settings page )
 * page_takeover_admin_display_settings ( Display admin settings)
 * page_takeover_get_settings_sections ( Returns an array of settings sections )
 * page_takeover_get_settings ( Return an array of settings  )
 * page_takeover_register_settings ( Register settings )
 * page_takeover_display_setting_field ( Displays a settings field )
 * page_takeover_get_fonts_options ( Returns <option> HTML for font family select box )
 * 
 */

if ( ! function_exists( 'page_takeover_admin_enqueues' ) ) {

	/**
	 * Enqueue Admin Scripts and Styles
	 *
	 * @since 1.1.0
	 */
	function page_takeover_admin_enqueues( $hook ) {

        if ( $hook !== 'toplevel_page_page-takeover' ) {
            return;
        }
        
        wp_enqueue_style( 'page-takeover-admin-css', PAGE_TAKEOVER_URL . 'css/page-takeover-admin.css', array(), PAGE_TAKEOVER_VERSION );        
        wp_enqueue_style( 'wp-color-picker' );

        wp_enqueue_script( 'page-takeover-admin-color', PAGE_TAKEOVER_URL . 'js/page-takeover-color.js', array( 'wp-color-picker' ), PAGE_TAKEOVER_VERSION );
        wp_enqueue_script( 'page-takeover-toggle', PAGE_TAKEOVER_URL . 'js/custom.js', array(), PAGE_TAKEOVER_VERSION );
        wp_enqueue_script( 'page-takeover-autosize', PAGE_TAKEOVER_URL . 'js/autosize.js', array(), PAGE_TAKEOVER_VERSION );
        wp_enqueue_script( 'page-takeover-webfont', 'https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js' );

	} add_action( 'admin_enqueue_scripts', 'page_takeover_admin_enqueues' );

}

if ( ! function_exists( 'page_takeover_admin_settings_page_add' ) ) {

    /**
     * Add menu page for settings
     * 
     * @since 1.1.0
     */
    function page_takeover_admin_settings_page_add() {
        
        add_menu_page( __('Discount Coupon','page-takeover'), __('Discount Coupon','page-takeover'), 'manage_options', 'page-takeover', 'page_takeover_admin_settings_page_output', PAGE_TAKEOVER_URL . '/images/icon.png', '30.3');

    } add_action( 'admin_menu', 'page_takeover_admin_settings_page_add' );

}

if ( ! function_exists( 'page_takeover_admin_settings_page_output' ) ) {

    /**
     * Output settings page
     * 
     * @since 1.1.0
     */
    function page_takeover_admin_settings_page_output() {
        
        ?>

            <div class="wrap">
                <h2><?php echo __('Discount Coupon', 'page-takeover'); ?></h2>
            </div><!--wrap-->

            <?php if ( isset( $_GET['settings-updated'] ) ) : ?>
                <div id="message" class="updated">
                    <p><strong><?php _e('Settings updated') ?></strong></p>
                </div>
            <?php endif; ?>
            
            <div id="page-takeover">

                <div>
                    <div class="page-takeover-container-left">
                        <?php page_takeover_display(); ?>
                    </div><!--page-takeover-container-left-->
                    <div class="page-takeover-container-right">
                        <?php page_takeover_admin_display_settings(); ?>
                    </div><!--page-takeover-container-right-->
                    <div class="page-takeover-clear"></div>
                </div>
                
            </div><!--page-takeover-->

        <?php

    }

}

if ( ! function_exists( 'page_takeover_admin_display_settings' ) ) {

    /**
     * Display the admin settings
     * 
     * @since 1.1.0
     */
    function page_takeover_admin_display_settings() {
        
        ?>
        <form method="post" action="options.php" id="frm1">

            <?php settings_fields( 'page-takeover-settings-group' ); ?>

            <?php 

                // get all sections and settings
                $sections = page_takeover_get_settings_sections();
                $settings = page_takeover_get_settings();    

                // loop throuugh sections
                foreach ( $sections as $section_id => $section_data ) :

                    // get settings for this section
                    $section_settings = array_filter( $settings, function ($var) use ( $section_id ) {
                        return ( $var['section'] == $section_id );
                    });

                    ?>

                    <div class="toggle-wrap">

                        <span class="trigger">
                            <a><?php echo $section_data['label']; ?></a>
                        </span><!-- .trigger -->

                        <div class="toggle-container" style="display: none;">

                            <div class="page-takeover-option-group">

                                <?php foreach ( $section_settings as $setting_id => $setting_data ) : ?>
                                    
                                    <div class="page-takeover-option-wide">
                                        <label><?php echo $setting_data['label']; ?></label>
                                        <?php page_takeover_display_setting_field( $setting_id, $setting_data ); ?>
                                    </div><!-- .page-takeover-option-wide -->

                                <?php endforeach; ?>

                            </div><!-- .page-takeover-option-group -->

                        </div><!-- .toggle-container -->

                    </div><!-- .toggle-wrap -->

                    <?php

                endforeach;
            ?>

            <?php $status = page_takeover_get_option( 'status' ); ?>
            <?php if ( $status == 'inactive' ) : ?>
                <div class="page-takeover-admin-note">
                    <?php _e( 'The takeover is currently inactive. To active it click on "Status" ( just above ), switch to active and save changes.', 'page-takeover' ); ?>
                </div><!-- .page-takeover-admin-note -->
            <?php endif; ?>

            <div class="page-takeover-save-container">
                <input type="submit" class="page-takeover-save-button" value="<?php _e( 'Save Changes', 'page-takeover' ) ?>" />
                <div class="clear"></div>
            </div><!--page-takeover-save-container-->
            
            <div class="clear"></div>

        </form>
        <?php

    }

}

if ( ! function_exists( 'page_takeover_get_settings_sections' ) ) {

    /**
     * Returns an array of settings sections
     * 
     * @since 1.1.0
     */
    function page_takeover_get_settings_sections() {

        $sections = array();

        $sections['background'] = array(
            'label' => __( 'Background', 'page-takeover' ),
        );

        $sections['title'] = array(
            'label' => __( 'Title', 'page-takeover' ),
        );

        $sections['subtitle'] = array(
            'label' => __( 'Subtitle', 'page-takeover' ),
        );

        $sections['button'] = array(
            'label' => __( 'Button', 'page-takeover' ),
        );

              

        $sections['trigger'] = array(
            'label' => __( 'Trigger', 'page-takeover' ),
        );

		$sections['bottom'] = array(
            'label' => __( 'Bottom', 'page-takeover' ),
        );

        $sections['status'] = array(
            'label' => __( 'Status', 'page-takeover' ),
        );

		       
		
		return $sections;

    }

}

if ( ! function_exists( 'page_takeover_get_settings') ) {

    /**
     * Return an array of settings 
     * 
     * @since 1.1.0
     */
    function page_takeover_get_settings() {

        $settings = array();

        /**
         * Background
         */

        $settings['background'] = array(
            'label'   => __( 'Overlay BG Color', 'page-takeover' ),
            'type'    => 'color',
            'default' => '#2c3644',
            'section' => 'background',
            'preview' => '.page-takeover|background-color',
        );

        /**
         * Title
         */

        $settings['title'] = array(
            'label'   => __( 'Title', 'page-takeover' ),
            'type'    => 'text',
            'default' => 'Title goes here',
            'section' => 'title',
            'preview' => '#page-takeover-title|html',
        );

        $settings['title_font'] = array(
            'label'   => __( 'Font Family', 'page-takeover' ),
            'type'    => 'font_family',
            'default' => 'Open Sans',
            'section' => 'title',
            'preview' => '#page-takeover-title|font-family',
        );

        $settings['title_size'] = array(
            'label'   => __( 'Font Size', 'page-takeover' ),
            'type'    => 'font_size',
            'default' => '3em',
            'section' => 'title',
            'preview' => '#page-takeover-title|font-size',
        );

        $settings['title_color'] = array(
            'label'   => __( 'Color', 'page-takeover' ),
            'type'    => 'color',
            'default' => '#ffffff',
            'section' => 'title',
            'preview' => '#page-takeover-title|color',
        );

        /**
         * Subtitle
         */

        $settings['subtitle'] = array(
            'label'   => __( 'Subtitle', 'page-takeover' ),
            'type'    => 'editor',
            'default' => 'Subtitle goes here',
            'section' => 'subtitle',
            'preview' => '#page-takeover-subtitle-inner|html',
        );

        $settings['subtitle_font'] = array(
            'label'   => __( 'Font Family', 'page-takeover' ),
            'type'    => 'font_family',
            'default' => 'Open Sans',
            'section' => 'subtitle',
            'preview' => '#page-takeover-subtitle-inner|font-family',
        );

        $settings['subtitle_size'] = array(
            'label'   => __( 'Font Size', 'page-takeover' ),
            'type'    => 'font_size',
            'default' => '1.4em',
            'section' => 'subtitle',
            'preview' => '#page-takeover-subtitle-inner|font-size',
        );

        $settings['subtitle_color'] = array(
            'label'   => __( 'Color', 'page-takeover' ),
            'type'    => 'color',
            'default' => '#ffffff',
            'section' => 'subtitle',
            'preview' => '#page-takeover-subtitle-inner|color',
        );

        /**
         * Button
         */

        $settings['button'] = array(
            'label'   => __( 'Button Text', 'page-takeover' ),
            'type'    => 'text',
            'default' => 'Button Label',
            'section' => 'button',
            'preview' => '#page-takeover-button a|html',
        );

        $settings['button_url'] = array(
            'label'   => __( 'Button URL', 'page-takeover' ),
            'type'    => 'text',
            'default' => '#',
            'section' => 'button',
        );

        $settings['button_rel'] = array(
            'label'   => __( 'Rel Attribute <small>for example the value can be nofollow</small>', 'page-takeover' ),
            'type'    => 'text',
            'default' => '',
            'section' => 'button',
        );

        $settings['button_font'] = array(
            'label'   => __( 'Font Family', 'page-takeover' ),
            'type'    => 'font_family',
            'default' => 'Open Sans',
            'section' => 'button',
            'preview' => '#page-takeover-button a|font-family',
        );

        $settings['button_size'] = array(
            'label'   => __( 'Font Size', 'page-takeover' ),
            'type'    => 'font_size',
            'default' => '1.4em',
            'section' => 'button',
            'preview' => '#page-takeover-button a|font-size',
        );

        $settings['button_background'] = array(
            'label'   => __( 'BG Color', 'page-takeover' ),
            'type'    => 'color',
            'default' => '#fec230',
            'section' => 'button',
            'preview' => '#page-takeover-button a|background-color',
        );

        $settings['button_color'] = array(
            'label'   => __( 'Text Color', 'page-takeover' ),
            'type'    => 'color',
            'default' => '#000000',
            'section' => 'button',
            'preview' => '#page-takeover-button|color',
        );


      

        /**
         * Trigger
         */

        $settings['trigger_type'] = array(
            'label'   => __( 'When to load the overlay?', 'page-takeover' ),
            'type'    => 'select',
            'default' => '',
            'section' => 'trigger',
            'choices' => array(
                'time' => __( 'After X seconds', 'page-takeover' ),
                'exit' => __( 'On Exit', 'page-takeover' ),
            ),
        );

        $settings['trigger'] = array(
            'label'   => __( 'After how many seconds to load the overlay?', 'page-takeover' ),
            'type'    => 'text',
            'default' => '0',
            'section' => 'trigger',
        );

        /**
         * Bottom
         */

        $settings['bottom'] = array(
            'label'   => __( 'Bottom', 'page-takeover' ),
            'type'    => 'text',
            'default' => 'Bottom goes here',
            'section' => 'bottom',
            'preview' => '#page-takeover-bottom|html',
        );

        $settings['bottom_font'] = array(
            'label'   => __( 'Font Family', 'page-takeover' ),
            'type'    => 'font_family',
            'default' => 'Open Sans',
            'section' => 'bottom',
            'preview' => '#page-takeover-bottom|font-family',
        );

        $settings['bottom_size'] = array(
            'label'   => __( 'Font Size', 'page-takeover' ),
            'type'    => 'font_size',
            'default' => '1em',
            'section' => 'bottom',
            'preview' => '#page-takeover-bottom|font-size',
        );

        $settings['bottom_color'] = array(
            'label'   => __( 'Color', 'page-takeover' ),
            'type'    => 'color',
            'default' => '#ffffff',
            'section' => 'bottom',
            'preview' => '#page-takeover-bottom|color',
        );

        /**
         * Status
         */

        $settings['status'] = array(
            'label'   => 'Status',
            'type'    => 'select',
            'default' => 'inactive',
            'choices' => array(
                'inactive' => __( 'Inactive', 'page-takeover' ),
                'active' => __( 'Active', 'page-takeover' ),
            ),
            'section' => 'status',
        );

		
        return $settings;

    }

}

if ( ! function_exists( 'page_takeover_register_settings' ) ) {

    /**
     * Register settings
     * 
     * @since 1.1.0
     */
    function page_takeover_register_settings() {

        $settings = page_takeover_get_settings();

        foreach ( $settings as $setting_id => $setting_data ) {
            register_setting( 'page-takeover-settings-group', PAGE_TAKOVER_OPTION_PREFIX . $setting_id );    
        }

    } add_action( 'admin_init', 'page_takeover_register_settings' );

}

if ( ! function_exists( 'page_takeover_display_setting_field' ) ) {

    /**
     * Displays a settings field
     */
    function page_takeover_display_setting_field( $id, $data ) {

        $setting_id = PAGE_TAKOVER_OPTION_PREFIX . $id;
        $setting_value = page_takeover_get_option( $id );

        $data_live_preview = '';
        $class_append = '';
        if ( ! empty( $data['preview'] ) ) {
            $data_live_preview = 'data-page-takeover-live-preview="' . $data['preview'] . '"';
            $class_append = 'page-takeover-live-preview';
        }

        if ( $data['type'] == 'text' ) {
            ?>
                <input 
                    type="text" 
                    name="<?php echo $setting_id; ?>" 
                    class="page-takeover-input-text page-takeover-live-preview <?php echo $class_append; ?>" 
                    <?php echo $data_live_preview; ?>
                    value="<?php echo $setting_value; ?>" />
            <?php
        } elseif ( $data['type'] == 'textarea' ) {
            ?>
                <textarea
                    name="<?php echo $setting_id; ?>" 
                    class="page-takeover-input-textarea page-takeover-textarea-wide autoExpand <?php echo $class_append; ?>"
                    <?php echo $data_live_preview; ?>
                    rows="3" 
                    data-min-rows="3"><?php echo $setting_value; ?></textarea>
            <?php
        } elseif ( $data['type'] == 'select' ) {
            ?>
                <select 
                    name="<?php echo $setting_id; ?>"
                    <?php echo $data_live_preview; ?>
                    class="page-takeover-select-option <?php echo $class_append; ?>">
                    <?php foreach ( $data['choices'] as $choice_value => $choice_label ) : ?>
                        <option value="<?php echo $choice_value; ?>" <?php selected( $setting_value, $choice_value ); ?>><?php echo $choice_label; ?></option>
                    <?php endforeach; ?>
                </select>
            <?php
        } elseif ( $data['type'] == 'font_family' ) {
            ?>
                <select 
                    name="<?php echo $setting_id; ?>"
                    <?php echo $data_live_preview; ?>
                    class="page-takeover-select-option page-takeover-select-font-family <?php echo $class_append; ?>">
                    <?php echo page_takeover_get_fonts_options( $setting_value ); ?>
                </select>
            <?php
        } elseif ( $data['type'] == 'font_size' ) {
            ?>
                <select 
                    name="<?php echo $setting_id; ?>"
                    <?php echo $data_live_preview; ?>
                    class="page-takeover-select-option page-takeover-select-font-size <?php echo $class_append; ?>">
                    <?php foreach ( range( 0.5, 4.5, 0.1 ) as $number ) : ?>
                        <option value="<?php echo $number . 'em'; ?>" <?php selected( $setting_value, $number . 'em'); ?>><?php echo $number . 'em'; ?></option>
                    <?php endforeach; ?>
                </select>
            <?php
        } elseif ( $data['type'] == 'editor' ) {
            $wysiwyg = array( 'textarea_name' => 'page_takeover_subtitle', 'wpautop' => false, 'tinymce' => array(
                'setup' => 'function(ed) {
                                ed.onChange.add(function() {
                                    jQuery("#page_takeover_subtitle_text",window.parent.document).val(tinyMCE.activeEditor.getContent());
                                    jQuery("#page_takeover_subtitle_text",window.parent.document).trigger("change");
                                    });
                            }',
            ));
            echo wp_editor( $setting_value, 'page_takeover_subtitle_text', $wysiwyg );
        } elseif ( $data['type'] == 'color' ) {
            ?>
                <input 
                    type="text" 
                    name="<?php echo $setting_id; ?>"
                    class="page-takeover-color" 
                    <?php echo $data_live_preview; ?>
                    value="<?php echo $setting_value; ?>"
                    data-default-color="<?php echo page_takeover_get_option_default( $id ); ?>" />
            <?php
        }

    }

}

if ( ! function_exists( 'page_takeover_get_fonts_options') ) {

    /**
     * Returns <option> HTML for font family select box
     *
     * @since 1.1.0
     */
    function page_takeover_get_fonts_options( $current = '' ) {
        
        $fonts_regular = page_takeover_get_fonts_regular();
        $fonts_google = page_takeover_get_fonts_google();
        $fonts_all = array_merge( $fonts_regular, $fonts_google );

        $output = '';
        
        foreach ( $fonts_all as $font ) {
            $selected = '';
            if ( $font == $current ) $selected = 'selected="selected"';        
            $output .= '<option value="' . $font . '" '. $selected .'>' . $font . '</option>';
        }

        return $output;

    }

}