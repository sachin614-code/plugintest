<?php
/*
Plugin Name: Booking Vehicle
Plugin URI: 
Description: Simple Booking vehicle 
Version: 1.0
Author: dev
Author URI: 
*/
 
    function html_frontend_form() {
        echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
        echo '<p>';
        echo 'Your Name (required) <br />';
        echo '<input type="text" name="cf-name" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["cf-name"] ) ? esc_attr( $_POST["cf-name"] ) : '' ) . '" size="40" />';
        echo '</p>';
        echo '<p>';
        echo 'Your Email (required) <br />';
        echo '<input type="email" name="cf-email" value="' . ( isset( $_POST["cf-email"] ) ? esc_attr( $_POST["cf-email"] ) : '' ) . '" size="40" />';
        echo '</p>';
        echo '<p>';
        echo 'Subject (required) <br />';
        echo '<input type="text" name="cf-subject" pattern="[a-zA-Z ]+" value="' . ( isset( $_POST["cf-subject"] ) ? esc_attr( $_POST["cf-subject"] ) : '' ) . '" size="40" />';
        echo '</p>';
        echo '<p>';
        echo 'Your Message (required) <br />';
        echo '<textarea rows="10" cols="35" name="cf-message">' . ( isset( $_POST["cf-message"] ) ? esc_attr( $_POST["cf-message"] ) : '' ) . '</textarea>';
        echo '</p>';
        echo '<p><input type="submit" name="cf-submitted" value="Send"/></p>';
        echo '</form>';
    }

    function deliver_mail() {

        // if the submit button is clicked, send the email
        if ( isset( $_POST['cf-submitted'] ) ) {
    
            // sanitize form values
            $name    = sanitize_text_field( $_POST["cf-name"] );
            $email   = sanitize_email( $_POST["cf-email"] );
            $subject = sanitize_text_field( $_POST["cf-subject"] );
            $message = esc_textarea( $_POST["cf-message"] );
    
            // get the blog administrator's email address
            $to = get_option( 'admin_email' );
    
            $headers = "From: $name <$email>" . "\r\n";
    
            // If email has been process for sending, display a success message
            if ( wp_mail( $to, $subject, $message, $headers ) ) {
                echo '<div>';
                echo '<p>Thanks for contacting me, expect a response soon.</p>';
                echo '</div>';
            } else {
                echo 'An unexpected error occurred';
            }
        }
    }

        //Create table function 

        function create_plugin_database_table()
        {
            global $table_prefix, $wpdb;
        
            $tblname = 'vehicle_booking';
            $wp_track_table = $table_prefix . "$tblname ";
        
            #Check to see if the table exists already, if not, then create it
        
            if($wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table) 
            {
        
                $sql = "CREATE TABLE `". $wp_track_table. "` ( ";
                $sql .= "  `id`  int(11)   NOT NULL auto_increment, ";
                $sql .= "  `first_name`  VARCHAR(100)   DEFAULT NULL, ";
                $sql .= "  `last_name`  VARCHAR(100)   DEFAULT NULL, ";
                $sql .= "  `email`  VARCHAR(100)   DEFAULT NULL, ";
                $sql .= "  `phone`  VARCHAR(100)   DEFAULT NULL, ";
                $sql .= "  `vehicle_type_id`  int(11)   DEFAULT NULL, ";
                $sql .= "  `vehicle_post_id`  int(11)   DEFAULT NULL, ";     
                $sql .= "  `message`  VARCHAR(255)   DEFAULT NULL, ";
                $sql .= "  `created_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, ";
                $sql .= "  `updated_at`  TIMESTAMP NOT NULL DEFAULT NULL, ";
                $sql .= "  PRIMARY KEY (`id`) "; 
                $sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ; ";
               // echo $sql ; die;
                require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
                dbDelta($sql);
            }
        }

        //Deactivate Function

        function my_plugin_remove_database() {
            global $wpdb;
            $table_name = $wpdb->prefix . 'vehicle_booking';
            $sql = "DROP TABLE IF EXISTS $table_name";
            $wpdb->query($sql);
            delete_option("my_plugin_db_version");
        }   



        

    function cf_shortcode() {
        ob_start();
        deliver_mail();
        html_frontend_form();
    
        return ob_get_clean();
    }


    //Activation hook for creating table in db while activating
    register_activation_hook( __FILE__, 'create_plugin_database_table' );

    //Deactivation hook for droping table in db while Deactivating
    register_deactivation_hook( __FILE__, 'my_plugin_remove_database' );


    // Frontend Form shortr code
    add_shortcode( 'wp_booking_form', 'cf_shortcode' );
?>