<?php

    class CustomColorPanel{

        public function __construct(){
            global $wp_customize;
            static $color_arr;

            $wp_customize->add_control( 'navbar_height', array(
                'label'		=> __( 'Nav Bar Height', 'bwpy'),
                'section'	=> 'bwpy_content_options_section',
                'helper'	=> __( 'In PX' ),
                'priority'	=>	10,
                'type'		=>	'control',
                'input_attrs'       => array(
                    'min'  => 0.5,
                    'step' => 0.01,
                    'max'  => 5,
            ),
            ) );

        }

        public function add_color_setting($setting, $default, $label, $type){
            global $wp_customize;
            $wp_customize->add_setting($setting, array(
                'default' => $default
            ));
            $wp_customize->add_control( $setting, array(
                'label'     => __( $label, 'bwpy' ),
                'section'   => 'bwpy_color_options_section',
                'priority'  => 10,
                'type'      => $type
            ) );
            $color_arr[] = $setting;

        }

    }

function load_color_settings(){
    $cp = new CustomColorPanel;
    
    $cp->add_color_setting('heading_color_picker', '#f4444d', 'Choose Heading Color', 'color');
    $cp->add_color_setting('h2_color_picker', '#f4444d', 'Choose H2 Color', 'color');
    $cp->add_color_setting('menu_nav_link_color_picker', '#f4444d', 'TopNav Text Color', 'color');
    $cp->add_color_setting('menu_nav_background_color_picker', '#f4444d', 'TopNav Background Color', 'color');
    $cp->add_color_setting('body_text_color', '#f4444d', 'Default Body Text Color', 'color');
    $cp->add_color_setting('h2_color_picker', '#f4444d', 'Choose H2 Color', 'color');
}
// var_dump($cp->$color_arr);
// var_dump(CustomColorPanel::$color_arr);
add_action( 'customize_register', 'load_color_settings' );

