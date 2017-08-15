<?php

/**
 * Đặt các đoạn code cần tùy biến của bạn vào bên dưới
 */


 /**

  * Tao mot section moi Customizer */

 function customizer_section_slideshow( $wp_customize ) {

     // Tao section

     $wp_customize->add_section (

         'section_slideshow',

         array(

             'title' => 'Homepage Slideshow',

             'description' => 'Các tùy chọn cho Slideshow',

             'priority' => 69

         )

     );


     /* Image */

     $wp_customize->add_setting( 'slideshow_image_1' );

     $wp_customize->add_control(

         new WP_Customize_Image_Control(

             $wp_customize,

             'slideshow_image_1',

             array(

                 'label' => 'Ảnh 1',

                 'section' => 'section_slideshow',

                 'settings' => 'slideshow_image_1'

             )

         )

     );


     /* URL */

     $wp_customize->add_setting(

       'slideshow_url_1',

       array(

           'default' => '#'

       ));

     $wp_customize->add_control (

       'slideshow_url_1',

       array(

         'label' => 'Link 1',

         'section' => 'section_slideshow',

         'type' => 'text',

         'settings' => 'slideshow_url_1'

       )

     );



     /* Image */

     $wp_customize->add_setting( 'slideshow_image_2' );

     $wp_customize->add_control(

         new WP_Customize_Image_Control(

             $wp_customize,

             'slideshow_image_2',

             array(

                 'label' => 'Ảnh 2',

                 'section' => 'section_slideshow',

                 'settings' => 'slideshow_image_2'

             )

         )

     );


     /* URL */

     $wp_customize->add_setting(

       'slideshow_url_2',

       array(

           'default' => '#'

       ));

     $wp_customize->add_control (

       'slideshow_url_2',

       array(

         'label' => 'Link 2',

         'section' => 'section_slideshow',

         'type' => 'text',

         'settings' => 'slideshow_url_2'

       )

     );



     /* Image */

     $wp_customize->add_setting( 'slideshow_image_3' );

     $wp_customize->add_control(

         new WP_Customize_Image_Control(

             $wp_customize,

             'slideshow_image_3',

             array(

                 'label' => 'Ảnh 3',

                 'section' => 'section_slideshow',

                 'settings' => 'slideshow_image_3'

             )

         )

     );


     /* URL */

     $wp_customize->add_setting(

       'slideshow_url_3',

       array(

           'default' => '#'

       ));

     $wp_customize->add_control (

       'slideshow_url_3',

       array(

         'label' => 'Link 3',

         'section' => 'section_slideshow',

         'type' => 'text',

         'settings' => 'slideshow_url_3'

       )

     );
 }

 add_action( 'customize_register', 'customizer_section_slideshow' );
