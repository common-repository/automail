<?php

/**
 * The admin-specific functionality of the plugin. Defines the plugin name, version, and two examples 
 * hooks for how to enqueue the admin-specific stylesheet and JavaScript.
 * @link       https://profiles.wordpress.org/javmah/
 * @since      1.0.0
 * @package    Automail
 * @subpackage Automail/admin
 * @author     javmah <jaedmah@gmail.com>
*/
class Automail_Admin
{
    /**
     * The ID of this plugin.
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private  $version ;
    /**
     * The current Date.
     * @since    1.0.0
     * @access   Public
     * @var      string    $Date    The current version of the plugin.
     */
    public  $Date = "" ;
    /**
     * The current Time.
     * @since    1.0.0
     * @access   Public
     * @var      string    $Time   The current Time.
     */
    public  $Time = "" ;
    /**
     * Events list.
     * @since    1.0.0
     * @access   Public
     * @var      array    $events    events list.
     */
    public  $events = array() ;
    /**
     * Events Children titles.
     * @since    1.0.0
     * @access   Public
     * @var      array    $eventsAndTitles   events list.
     */
    public  $eventsAndTitles = array() ;
    /**
     * WooCommerce Order Statuses.
     * @since    1.0.0
     * @access   Public
     * @var      array    $active_plugins  list of active plugins .
     */
    public  $wooCommerceOrderStatuses = array() ;
    /**
     * List of active plugins.
     * @since    1.0.0
     * @access   Public
     * @var      array    $active_plugins     List of active plugins .
     */
    public  $active_plugins = array() ;
    /**
     * Initialize the class and set its properties.
     * @since    1.0.0
     * @param    string    $plugin_name the name of this plugin.
     * @param    string    $version the version of this plugin.
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    /**
     * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ Improve this Functor ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     * Register the Custom Post type.
     * Post are Automation AND Log ,Currently Not Working 
     * @since    1.0.0
     */
    public function automail_register_custom_post_type()
    {
        // register_post_type( "automail", array());
        // register_post_type( "automailLog", array());
    }
    
    /**
     * Register the stylesheets for the admin area.
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'css/automail-admin.css',
            array(),
            $this->version,
            'all'
        );
    }
    
    /**
     * Register the JavaScript for the admin area.
     * This Function is One of The Most Important Function, it Passes all the Event and Event source to Views
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        # Limit The Code scope only for #toplevel_page_automail
        
        if ( get_current_screen()->id == 'toplevel_page_automail' ) {
            # There are come Default function for This, So Why Custom  Thing
            # Set date
            # Current Date
            $date_format = get_option( 'date_format' );
            $this->Date = ( $date_format ? current_time( $date_format ) : current_time( 'd/m/Y' ) );
            # Current Time
            $time_format = get_option( 'time_format' );
            $this->Time = ( $date_format ? current_time( $time_format ) : current_time( 'g:i a' ) );
            # Active Plugins, Checking Active And Inactive Plugin
            $this->active_plugins = get_option( 'active_plugins' );
            # WooCommerce order Statuses
            
            if ( function_exists( "wc_get_order_statuses" ) ) {
                $woo_order_statuses = wc_get_order_statuses();
                # for Woocommerce New orders;
                $this->wooCommerceOrderStatuses['wc-new_order'] = 'WooCommerce New Checkout Page Order';
                # For Default Status
                foreach ( $woo_order_statuses as $key => $value ) {
                    $this->wooCommerceOrderStatuses[$key] = 'WooCommerce ' . $value;
                }
            } else {
                # If Function didn't exist do it
                $this->wooCommerceOrderStatuses = array(
                    "wc-new_order"  => "WooCommerce New Checkout Page Order",
                    "wc-pending"    => "WooCommerce Order Pending payment",
                    "wc-processing" => "WooCommerce Order Processing",
                    "wc-on-hold"    => "WooCommerce Order On-hold",
                    "wc-completed"  => "WooCommerce Order Completed",
                    "wc-cancelled"  => "WooCommerce Order Cancelled",
                    "wc-refunded"   => "WooCommerce Order Refunded",
                    "wc-failed"     => "WooCommerce Order Failed",
                );
            }
            
            # User Starts
            # wordpress user events
            $wordpressUserEvents = array(
                "wp_newUser"           => 'Wordpress New User',
                "wp_UserProfileUpdate" => 'Wordpress User Profile Update',
                "wp_deleteUser"        => 'Wordpress Delete User',
                "wp_userLogin"         => 'Wordpress User Login',
                "wp_userLogout"        => 'Wordpress User Logout',
            );
            # Inserting User Events to All Events
            $this->events += $wordpressUserEvents;
            # New Code for User
            foreach ( $wordpressUserEvents as $key => $value ) {
                # This is For User
                $this->eventsAndTitles[$key] = array(
                    "userID"               => "User ID",
                    "userName"             => "User Name",
                    "firstName"            => "User First Name",
                    "lastName"             => "User Last Name",
                    "nickname"             => "User Nickname",
                    "displayName"          => "User Display Name",
                    "eventName"            => "Event Name",
                    "description"          => "User Description",
                    "userEmail"            => "User Email",
                    "userRegistrationDate" => "User Registration Date",
                    "userRole"             => "User Role",
                    "site_time"            => "Site Time",
                    "site_date"            => "Site Date",
                    "user_date_year"       => "Year of the Date",
                    "user_date_month"      => "Month of the Date",
                    "user_date_date"       => "Date of the Date",
                    "user_date_time"       => "Time of the Date",
                );
                
                if ( $key == 'wp_userLogin' ) {
                    $this->eventsAndTitles[$key]["userLogin"] = "Logged in ";
                    $this->eventsAndTitles[$key]["userLoginTime"] = "Logged in Time";
                    $this->eventsAndTitles[$key]["userLoginDate"] = "Logged in Date";
                }
                
                
                if ( $key == 'wp_userLogout' ) {
                    $this->eventsAndTitles[$key]["userLogout"] = "User Logout";
                    $this->eventsAndTitles[$key]["userLogoutTime"] = "Logout Time";
                    $this->eventsAndTitles[$key]["userLogoutDate"] = "Logout Date";
                }
            
            }
            # Post Event array
            $wordpressPostEvents = array(
                'wp_newPost'    => 'Wordpress New Post',
                'wp_editPost'   => 'Wordpress Edit Post',
                'wp_deletePost' => 'Wordpress Delete Post',
                'wp_page'       => 'Wordpress Page',
            );
            # Inserting WP Post Events to All Events
            $this->events += $wordpressPostEvents;
            # post loop
            foreach ( $wordpressPostEvents as $key => $value ) {
                # setting wordpress_page profile update events
                if ( $key != 'wp_page' ) {
                    # This For paid User
                    $this->eventsAndTitles[$key] = array(
                        "postID"              => "Post ID",
                        "post_authorID"       => "Post Author ID",
                        "authorUserName"      => "Post Author User name",
                        "authorDisplayName"   => "Post Author Display Name",
                        "authorEmail"         => "Post Author Email",
                        "authorRole"          => "Post Author Role",
                        "post_title"          => "Post Title",
                        "post_date"           => "Post Date",
                        "post_date_gmt"       => "Post Date GMT",
                        "site_time"           => "Site Time",
                        "site_date"           => "Site Date",
                        "post_date_year"      => "Post on Year",
                        "post_date_month"     => "Post on Month",
                        "post_date_date"      => "Post on Date",
                        "post_date_time"      => "Post on Time",
                        "post_content"        => "Post Content",
                        "post_excerpt"        => "Post Excerpt",
                        "post_status"         => "Post Status",
                        "eventName"           => "Event Name",
                        "comment_status"      => "Comment Status",
                        "ping_status"         => "Ping Status",
                        "post_password"       => "Post Password",
                        "post_name"           => "Post Name",
                        "to_ping"             => "To Ping",
                        "pinged"              => "Pinged",
                        "post_modified"       => "Post Modified Date",
                        "post_modified_gmt"   => "Post Modified GMT",
                        "post_modified_year"  => "Post modified Year",
                        "post_modified_month" => "Post modified Month",
                        "post_modified_date"  => "Post modified Date",
                        "post_modified_time"  => "Post modified Time",
                        "post_parent"         => "Post Parent",
                        "guid"                => "Guid",
                        "menu_order"          => "Menu Order",
                        "post_type"           => "Post Type",
                        "post_mime_type"      => "Post Mime Type",
                        "comment_count"       => "Comment Count",
                        "filter"              => "Filter",
                    );
                }
                if ( $key == 'wp_page' ) {
                    $this->eventsAndTitles[$key] = array(
                        "postID"              => "Page ID",
                        "post_authorID"       => "Page Author ID",
                        "authorUserName"      => "Page Author User name",
                        "authorDisplayName"   => "Page Author Display Name",
                        "authorEmail"         => "Page Author Email",
                        "authorRole"          => "Page Author Role",
                        "post_title"          => "Page Title",
                        "post_date"           => "Page Date",
                        "post_date_gmt"       => "Page Date GMT",
                        "site_time"           => "Site Time",
                        "site_date"           => "Site Date",
                        "post_date_year"      => "Page on Year",
                        "post_date_month"     => "Page on Month",
                        "post_date_date"      => "Page on Date",
                        "post_date_time"      => "Page on Time",
                        "post_content"        => "Page Content",
                        "post_excerpt"        => "Page Excerpt",
                        "post_status"         => "Page Status",
                        "eventName"           => "Event Name",
                        "comment_status"      => "Comment Status",
                        "ping_status"         => "Ping Status",
                        "post_password"       => "Page Password",
                        "post_name"           => "Page Name",
                        "to_ping"             => "To Ping",
                        "pinged"              => "Pinged",
                        "post_modified"       => "Page Modified",
                        "post_modified_gmt"   => "Page Modified GMT",
                        "post_modified_year"  => "Page modified Year",
                        "post_modified_month" => "Page modified Month",
                        "post_modified_date"  => "Page modified Date",
                        "post_modified_time"  => "Page modified Time",
                        "post_parent"         => "Page Parent",
                        "guid"                => "Guid",
                        "menu_order"          => "Menu Order",
                        "post_type"           => "Page Type",
                        "post_mime_type"      => "Page Mime Type",
                        "comment_count"       => "Comment Count",
                        "filter"              => "Filter",
                    );
                }
            }
            # Loop Ends
            # Comment Starts
            $wordpressCommentEvents = array(
                'wp_comment'      => 'Wordpress Comment',
                'wp_edit_comment' => 'Wordpress Edit Comment',
            );
            # Inserting comment Events to All Events
            $this->events += $wordpressCommentEvents;
            # setting wordpress comments events
            foreach ( $wordpressCommentEvents as $key => $value ) {
                # For Paid User
                $this->eventsAndTitles[$key] = array(
                    "comment_ID"           => "Comment ID",
                    "comment_post_ID"      => "Comment Post ID",
                    "comment_author"       => "Comment Author",
                    "comment_author_email" => "Comment Author Email",
                    "comment_author_url"   => "Comment Author Url",
                    "comment_content"      => "Comment Content",
                    "comment_type"         => "Comment Type",
                    "user_ID"              => "Comment User ID",
                    "comment_author_IP"    => "Comment Author IP",
                    "comment_agent"        => "Comment Agent",
                    "comment_date"         => "Comment Date",
                    "comment_date_gmt"     => "Comment Date GMT",
                    "site_time"            => "Site Time",
                    "site_date"            => "Site Date",
                    "year_of_comment"      => "Year of the Comment",
                    "month_of_comment"     => "Month of the Comment",
                    "date_of_comment"      => "Date of the Comment",
                    "time_of_comment"      => "Time of the Comment",
                    "filtered"             => "Filtered",
                    "comment_approved"     => "Comment Approved",
                );
            }
            # Below are Contact forms
            # Contact Form 7
            $cf7 = $this->cf7_forms_and_fields();
            
            if ( $cf7[0] ) {
                foreach ( $cf7[1] as $form_id => $form_name ) {
                    $this->events[$form_id] = $form_name;
                }
                foreach ( $cf7[2] as $form_id => $fields_array ) {
                    $this->eventsAndTitles[$form_id] = $fields_array;
                }
            }
            
            # For Ninja Form
            $ninja = $this->ninja_forms_and_fields();
            
            if ( $ninja[0] ) {
                foreach ( $ninja[1] as $form_id => $form_name ) {
                    $this->events[$form_id] = $form_name;
                }
                foreach ( $ninja[2] as $form_id => $fields_array ) {
                    $this->eventsAndTitles[$form_id] = $fields_array;
                }
            }
            
            # formidable form
            $formidable = $this->formidable_forms_and_fields();
            
            if ( $formidable[0] ) {
                foreach ( $formidable[1] as $form_id => $form_name ) {
                    $this->events[$form_id] = $form_name;
                }
                foreach ( $formidable[2] as $form_id => $fields_array ) {
                    $this->eventsAndTitles[$form_id] = $fields_array;
                }
            }
            
            # wpforms-lite/wpforms.php
            $wpforms = $this->wpforms_forms_and_fields();
            
            if ( $wpforms[0] ) {
                foreach ( $wpforms[1] as $form_id => $form_name ) {
                    $this->events[$form_id] = $form_name;
                }
                foreach ( $wpforms[2] as $form_id => $fields_array ) {
                    $this->eventsAndTitles[$form_id] = $fields_array;
                }
            }
            
            # weforms/weforms.php
            $weforms = $this->weforms_forms_and_fields();
            
            if ( $weforms[0] ) {
                foreach ( $weforms[1] as $form_id => $form_name ) {
                    $this->events[$form_id] = $form_name;
                }
                foreach ( $weforms[2] as $form_id => $fields_array ) {
                    $this->eventsAndTitles[$form_id] = $fields_array;
                }
            }
            
            # gravity forms/gravity forms.php
            $gravityForms = $this->gravity_forms_and_fields();
            
            if ( $gravityForms[0] ) {
                foreach ( $gravityForms[1] as $form_id => $form_name ) {
                    $this->events[$form_id] = $form_name;
                }
                foreach ( $gravityForms[2] as $form_id => $fields_array ) {
                    $this->eventsAndTitles[$form_id] = $fields_array;
                }
            }
            
            # forminator forminator/forminator.php
            $forminatorForms = $this->forminator_forms_and_fields();
            
            if ( $forminatorForms[0] ) {
                foreach ( $forminatorForms[1] as $form_id => $form_name ) {
                    $this->events[$form_id] = $form_name;
                }
                foreach ( $forminatorForms[2] as $form_id => $fields_array ) {
                    $this->eventsAndTitles[$form_id] = $fields_array;
                }
            }
            
            # enqueueing the Script
            wp_enqueue_script(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'js/automail-admin.js',
                array( 'jquery' ),
                $this->version,
                false
            );
        }
        
        # End of Scope
        
        if ( get_current_screen()->id == 'toplevel_page_automail' ) {
            wp_register_script(
                'Vue',
                plugin_dir_url( __FILE__ ) . 'js/vue.js',
                array(),
                FALSE,
                FALSE
            );
            wp_enqueue_script(
                'automail-admin',
                plugin_dir_url( __FILE__ ) . 'js/automail-admin.js',
                array( 'Vue' ),
                '1.0',
                TRUE
            );
            # freemius Starts.
            # default is Free.
            $freemiusStatus = false;
            # freemius Starts
            # Change from Here
            
            if ( isset( $_GET["action"], $_GET["id"] ) and !empty($_GET["id"]) ) {
                # getting the integration post from DB
                $wpPost = get_post( sanitize_text_field( $_GET["id"] ), ARRAY_A );
                # Creating an array for Frontend, for Preselecting
                $frontEnd = array(
                    "ID"              => ( (isset( $wpPost['ID'] ) and !empty($wpPost['ID'])) ? $wpPost['ID'] : "" ),
                    "automatonName"   => ( (isset( $wpPost['post_title'] ) and !empty($wpPost['post_title'])) ? $wpPost['post_title'] : "" ),
                    "selectedEvent"   => ( (isset( $wpPost['post_excerpt'] ) and !empty($wpPost['post_excerpt'])) ? $wpPost['post_excerpt'] : "" ),
                    "eventsAndTitles" => $this->eventsAndTitles,
                    "mailReceiver"    => json_encode( get_post_meta( sanitize_text_field( $_GET["id"] ), "mailReceiver", TRUE ) ),
                    "freemiusStatus"  => $freemiusStatus,
                );
            } else {
                $frontEnd = array(
                    "eventsAndTitles" => $this->eventsAndTitles,
                    "freemiusStatus"  => $freemiusStatus,
                );
            }
            
            # Sending data to Front-end
            wp_localize_script( 'automail-admin', 'automailJsData', $frontEnd );
        }
    
    }
    
    /**
     * This is The menu Function, This Functor add Menus and Sub-menus.
     * Currently this function had a Main Menu and One Sub-menu
     * @since    1.0.0
     */
    public function automail_menu_pages()
    {
        # Remove the Log Starts
        $automailLogs = get_posts( array(
            'post_type'      => 'automailLog',
            'posts_per_page' => -1,
        ) );
        if ( count( $automailLogs ) > 50 ) {
            foreach ( $automailLogs as $key => $log ) {
                if ( $key > 50 ) {
                    wp_delete_post( $log->ID, true );
                }
            }
        }
        # Remove the Log Ends
        # Menu
        add_menu_page(
            __( 'autoMail', 'automail' ),
            __( 'autoMail', 'automail' ),
            'manage_options',
            'automail',
            array( $this, 'automail_menu_pages_view' ),
            'dashicons-email-alt',
            25
        );
        # Sub-menu
        add_submenu_page(
            'automail',
            'Settings',
            'settings',
            'manage_options',
            'automail-settings',
            array( $this, 'automail_settings_view' )
        );
    }
    
    /**
     * autoMail Main landing page and Router.
     * This is The Main User interaction and Routeing function  of this Class. I routed to different functor and Page from Here.
     * @since    1.0.0
     */
    public function automail_menu_pages_view()
    {
        # router aka request dispatcher;
        
        if ( isset( $_GET['action'] ) and $_GET['action'] == 'new' ) {
            # including the new automaton view File;
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/automail-new-automaton.php';
        } elseif ( isset( $_GET['action'], $_GET['id'] ) and ($_GET['action'] == 'edit' and !empty($_GET['id'])) ) {
            # Getting the Post data;
            $post = get_post( sanitize_text_field( $_GET["id"] ), ARRAY_A );
            
            if ( $post ) {
                $ID = ( (isset( $post['ID'] ) and !empty($post['ID'])) ? $post['ID'] : "" );
                $automatonName = ( (isset( $post['post_title'] ) and !empty($post['post_title'])) ? $post['post_title'] : "" );
                $eventName = ( (isset( $post['post_excerpt'] ) and !empty($post['post_excerpt'])) ? $post['post_excerpt'] : "" );
                $automailEmail = ( (isset( $post['post_content'] ) and !empty($post['post_content'])) ? $post['post_content'] : "" );
                $mailReceiver = get_post_meta( sanitize_text_field( $_GET["id"] ), "mailReceiver", TRUE );
            } else {
                # No Post found in the database so redirecting.
                wp_redirect( admin_url( 'admin.php?page=automail&status=Post ID is Incorrect! No post in the Database.' ) );
                exit;
            }
            
            # including edit view File.
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/automail-edit-automaton.php';
        } elseif ( isset( $_GET['action'], $_GET['id'] ) and ($_GET['action'] == 'status' and !empty($_GET['id'])) ) {
            # Change Automation status Redirect.
            $this->automail_automation_status( sanitize_text_field( $_GET["id"] ) );
        } elseif ( isset( $_GET['action'], $_GET['id'] ) and ($_GET['action'] == 'delete' and !empty($_GET['id'])) ) {
            # Delete and Redirect.
            
            if ( is_array( $_GET['id'] ) ) {
                # deleting Bulk Automation.
                foreach ( $_GET['id'] as $id ) {
                    wp_delete_post( sanitize_text_field( $id ) );
                }
                # redirecting user to list
                wp_redirect( admin_url( '/admin.php?page=automail&status=success' ) );
            } else {
                # Deleting Single Automation.
                ( wp_delete_post( sanitize_text_field( $_GET["id"] ) ) ? wp_redirect( admin_url( '/admin.php?page=automail&status=success' ) ) : wp_redirect( admin_url( '/admin.php?page=automail&status=failed' ) ) );
            }
        
        } else {
            # Adding List table
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-automail-list-table.php';
            # Creating view Page layout
            echo  "<div class='wrap'>" ;
            echo  "<h1 class='wp-heading-inline'> Email Automatons  </h1>" ;
            echo  "<a href=" . admin_url( '/admin.php?page=automail&action=new' ) . " class='page-title-action'>Add New Email Automaton</a>" ;
            # Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions
            echo  "<form id='newIntegration' method='get'>" ;
            # For plugins, we also need to ensure that the form posts back to our current page
            echo  "<input type='hidden' name='page' value='" . esc_attr( $_REQUEST['page'] ) . "' />" ;
            echo  "<input type='hidden' name='automail_nonce' value='" . wp_create_nonce( 'automail_nonce_bulk_action' ) . "' />" ;
            # Now we can render the completed list table
            $automail_table = new Automail_List_Table( $this->eventsAndTitles );
            $automail_table->prepare_items();
            $automail_table->display();
            echo  "</form>" ;
            echo  "</div>" ;
        }
    
    }
    
    /**
     * Sub-menu page  view function 
     * This is the Submenu page view function. This Function Will Display Log, Settings & Plugin configuration.
     * @since  1.0.0
     */
    public function automail_settings_view()
    {
        ?>
			<div class='wrap'>
				<!-- title -->
				<h2><?php 
        esc_attr_e( 'autoMail Settings', 'automail' );
        ?></h2>
				<!-- tab nav -->
				<h2 class="nav-tab-wrapper">
					<!-- <a href="#" class="nav-tab">Email template</a> -->
					<a href="#" class="nav-tab nav-tab-active">Logs</a>
					<!-- <a href="#" class="nav-tab">Tab #3</a> -->
				</h2>
				<!-- Container body -->
				<div id="container">
					<?php 
        # getting the logs
        $automailLog = get_posts( array(
            'post_type'      => 'automailLog',
            'posts_per_page' => -1,
        ) );
        $i = 1;
        foreach ( $automailLog as $key => $log ) {
            
            if ( $log->post_title == 200 ) {
                echo  "<div class='notice notice-success inline'>" ;
            } else {
                echo  "<div class='notice notice-error inline'>" ;
            }
            
            echo  "<p><span class='automail-circle'>" . esc_html( $log->ID ) ;
            echo  " .</span>" ;
            echo  "<code>" . esc_html( $log->post_title ) . "</code>" ;
            echo  "<code>" ;
            if ( isset( $log->post_excerpt ) ) {
                echo  esc_html( $log->post_excerpt ) ;
            }
            echo  "</code>" ;
            echo  esc_html( $log->post_content ) ;
            echo  " <code>" . esc_html( $log->post_date ) . "</code>" ;
            echo  "</p>" ;
            echo  "</div>" ;
            $i++;
        }
        ?>
				</div>
			</div>
		<?php 
    }
    
    /** 
     * Change connection status;
     * if Automail automaton status is publish it turn that to Pending vice versa 
     * @since    	1.0.0
     * @return 	   	array 	Integrations details.
     */
    public function automail_automation_status( $id )
    {
        # Check Valid INT
        if ( !is_numeric( $id ) ) {
            $this->automail_log(
                get_class( $this ),
                __METHOD__,
                "200",
                "ERROR: status change ID " . $id . " is not numeric."
            );
        }
        # check the Post type status
        
        if ( get_post( $id )->post_status == 'publish' ) {
            $custom_post = array(
                'ID'          => $id,
                'post_status' => 'pending',
            );
        } else {
            $custom_post = array(
                'ID'          => $id,
                'post_status' => 'publish',
            );
        }
        
        # Keeping Log
        $this->automail_log(
            get_class( $this ),
            __METHOD__,
            "200",
            "SUCCESS: ID " . $id . " Integration status  change to ." . get_post( $id )->post_status
        );
        # redirect
        
        if ( wp_update_post( $custom_post ) ) {
            wp_redirect( admin_url( '/admin.php?page=automail&rms=success_from_status_change' ) );
        } else {
            wp_redirect( admin_url( '/admin.php?page=automail&rms=fail' ) );
        }
    
    }
    
    /**
     * This a Admin Notice Display Function for *** TESTING And DEBUG ***
     * I used This function for testing and Debug purpose 
     * @since    1.0.0
     */
    public function automail_admin_notice()
    {
        echo  "<pre>" ;
        // print_r($this->automail_wooCommerce_product_metaKeys());
        // echo"<h><br><h>";
        // print_r($this->automail_wooCommerce_order_metaKeys());
        echo  "</pre>" ;
    }
    
    /**
     * Saving Automation Submission, 
     * This Function will save New and Edit Automaton.
     * @since    1.0.0
     */
    public function automail_saveAutomation()
    {
        # automatonName
        
        if ( isset( $_POST['automatonName'] ) and !empty($_POST['automatonName']) ) {
            $automatonName = sanitize_text_field( $_POST['automatonName'] );
        } else {
            wp_redirect( admin_url( 'admin.php?page=automail&status=automatonName is not set or empty!' ) );
            exit;
        }
        
        # eventName
        
        if ( isset( $_POST['eventName'] ) and !empty($_POST['eventName']) ) {
            $eventName = sanitize_text_field( $_POST['eventName'] );
        } else {
            wp_redirect( admin_url( 'admin.php?page=automail&status=eventName is not set or empty!' ) );
            exit;
        }
        
        # For mailReceiver
        
        if ( isset( $_POST['mailReceiver'] ) and !empty($_POST['mailReceiver']) and is_array( $_POST['mailReceiver'] ) ) {
            $mailReceiver = array_map( 'sanitize_text_field', $_POST['mailReceiver'] );
        } else {
            wp_redirect( admin_url( 'admin.php?page=automail&status=mailReceiver_is_not_set_or_empty_or_not_array!' ) );
            exit;
        }
        
        # For Email Body
        
        if ( isset( $_POST['automailEmail'] ) and !empty($_POST['automailEmail']) ) {
            $allowed_html = array(
                'a'          => array(
                'href'  => array(),
                'title' => array(),
            ),
                'br'         => array(),
                'em'         => array(),
                'strong'     => array(),
                'ol'         => array(),
                'li'         => array(),
                'del'        => array(),
                'blockquote' => array(),
                'ins'        => array(),
            );
            $automailEmail = wp_kses( $_POST['automailEmail'], $allowed_html );
        } else {
            wp_redirect( admin_url( 'admin.php?page=automail&status=automailEmail is not set or empty!' ) );
            exit;
        }
        
        # Post Status
        
        if ( isset( $_POST['automatonStatus'] ) and $_POST['automatonStatus'] == "on" ) {
            $post_status = "publish";
        } else {
            $post_status = "pending";
        }
        
        # Save new integration
        
        if ( $_POST['status'] == "newAutomation" ) {
            # Preparing Post array for DB insert
            $customPost = array(
                'ID'           => '',
                'post_content' => $automailEmail,
                'post_title'   => $automatonName,
                'post_status'  => $post_status,
                'post_excerpt' => $eventName,
                'post_name'    => '',
                'post_type'    => 'automail',
                'menu_order'   => '',
                'post_parent'  => '',
                'meta_input'   => array(
                'mailReceiver' => $mailReceiver,
            ),
            );
            # Inserting New integration custom Post type
            $post_id = wp_insert_post( $customPost );
            //  Insert the post into the database
        } elseif ( $_POST['status'] == "editAutomation" and !empty($_POST['postID']) ) {
            # Preparing Post array for status Change
            $customPost = array(
                'ID'           => sanitize_text_field( $_POST['postID'] ),
                'post_content' => $automailEmail,
                'post_title'   => $automatonName,
                'post_status'  => $post_status,
                'post_excerpt' => $eventName,
                'post_name'    => '',
                'post_type'    => 'automail',
                'menu_order'   => '',
                'post_parent'  => '',
                'meta_input'   => array(
                'mailReceiver' => $mailReceiver,
            ),
            );
            # Updating Custom Post Type
            $post_id = wp_update_post( $customPost );
            // Insert the post into the database
        } else {
            # Nothing ...
        }
        
        # Redirect with message
        
        if ( $post_id ) {
            wp_redirect( admin_url( 'admin.php?page=automail&status=success' ) );
            exit;
        } else {
            wp_redirect( admin_url( 'admin.php?page=automail&status=action failed' ) );
            exit;
        }
    
    }
    
    /**
     * This Function is For getting user Roles with Number of User per User-role 
     * Important and ERROR prone function 
     * @since    1.0.0
     */
    public function automail_userRoles()
    {
        # Empty Holder
        $userRoles = array();
        # If exist then loop
        if ( function_exists( "get_editable_roles" ) ) {
            foreach ( get_editable_roles() as $key => $valueArray ) {
                if ( isset( $valueArray['name'] ) ) {
                    $userRoles[$key] = $valueArray['name'];
                }
            }
        }
        # Setting the Numbers
        if ( function_exists( "count_users" ) and isset( count_users()['avail_roles'] ) ) {
            foreach ( count_users()['avail_roles'] as $key => $value ) {
                if ( isset( $userRoles[$key] ) and $value ) {
                    $userRoles[$key] = $userRoles[$key] . " (" . $value . ")";
                }
            }
        }
        # Adding user role prefix at the begging  keys
        $arrayWithPrefix = array();
        foreach ( $userRoles as $key => $value ) {
            $arrayWithPrefix["userRole_" . $key] = $value;
        }
        # return
        
        if ( empty($arrayWithPrefix) ) {
            return array( FALSE, "User role is empty." );
        } else {
            return array( TRUE, $arrayWithPrefix );
        }
    
    }
    
    /**
     *  Contact form 7, form  fields 
     *  @since    1.0.0
     */
    public function cf7_forms_and_fields()
    {
        # is there CF7
        if ( !in_array( 'contact-form-7/wp-contact-form-7.php', $this->active_plugins ) ) {
            return array( FALSE, "ERROR: Contact form 7 is Not installed or DB Table is Not Exist." );
        }
        $cf7forms = array();
        $fieldsArray = array();
        global  $wpdb ;
        $cf7Forms = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} INNER JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id WHERE {$wpdb->posts}.post_type = 'wpcf7_contact_form' AND {$wpdb->postmeta}.meta_key = '_form'" );
        # Looping the Forms
        foreach ( $cf7Forms as $form ) {
            # Inserting Fields 																			# Loop the Custom Post ;
            $cf7forms["cf7_" . $form->ID] = "Cf7 - " . $form->post_title;
            # Getting Fields Meta
            $formFieldsMeta = get_post_meta( $form->ID, '_form', true );
            # Replacing Quoted string
            $formFieldsMeta = preg_replace( '/"((?:""|[^"])*)"/', "", $formFieldsMeta );
            # Removing : txt
            $formFieldsMeta = preg_replace( '/\\w+:\\w+/', "", $formFieldsMeta );
            # Removing submit
            $formFieldsMeta = preg_replace( '/\\bsubmit\\b/', "", $formFieldsMeta );
            # if txt is Not empty
            
            if ( !empty($formFieldsMeta) ) {
                # Getting Only [] txt
                $bracketTxt = array();
                # Separating bracketed txt and inserting theme to  $bracketTxt array
                preg_match_all( '/\\[(.*?)\\]/', $formFieldsMeta, $bracketTxt );
                # Check is set & not empty
                if ( isset( $bracketTxt[1] ) && !empty($bracketTxt[1]) ) {
                    # Field Loop
                    foreach ( $bracketTxt[1] as $txt ) {
                        # Divide the TXT after every space
                        $tmpArr = explode( ' ', $txt );
                        # taking Only the second Element of every array || first one is Field type || Second One is Field key
                        $singleItem = array_slice( $tmpArr, 1, 1 );
                        # Remove Submit Empty Array || important i am removing submit
                        if ( isset( $singleItem[0] ) && !empty($singleItem[0]) ) {
                            $fieldsArray["cf7_" . $form->ID][$singleItem[0]] = $singleItem[0];
                        }
                    }
                }
            }
        
        }
        # Loop ends
        # Adding extra fields || like Date and Time || Add more in future
        if ( !empty($fieldsArray) ) {
            foreach ( $fieldsArray as $formID => $formFieldsArray ) {
                # For Time
                if ( !isset( $formFieldsArray['automail_submitted_time'] ) ) {
                    $fieldsArray[$formID]['automail_submitted_time'] = "automail Form submitted  time.";
                }
                # for Date
                if ( !isset( $formFieldsArray['automail_submitted_date'] ) ) {
                    $fieldsArray[$formID]['automail_submitted_date'] = "automail Form submitted date.";
                }
            }
        }
        return array( TRUE, $cf7forms, $fieldsArray );
    }
    
    /**
     *  Ninja  form  fields 
     *  @param     int     $user_id     username
     *  @param     int     $old_user_data     username
     *  @since     1.0.0
     */
    public function ninja_forms_and_fields()
    {
        if ( !in_array( 'ninja-forms/ninja-forms.php', $this->active_plugins ) ) {
            return array( FALSE, "ERROR:  Ninja form 7 is Not Installed" );
        }
        global  $wpdb ;
        $FormArray = array();
        # Empty Array for Value Holder
        $fieldsArray = array();
        $ninjaForms = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}nf3_forms", ARRAY_A );
        foreach ( $ninjaForms as $form ) {
            $FormArray["ninja_" . $form["id"]] = "Ninja - " . $form["title"];
            $ninjaFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}nf3_fields where parent_id = '" . $form["id"] . "'", ARRAY_A );
            foreach ( $ninjaFields as $field ) {
                # freemius
                $field_list = array(
                    "textbox",
                    "textarea",
                    "email",
                    "phone",
                    "number",
                    "checkbox",
                    "date",
                    "listmultiselect",
                    "listradio",
                    "listselect",
                    "liststate",
                    "starrating",
                    "hidden",
                    "address",
                    "city",
                    "zip"
                );
                if ( in_array( $field["type"], $field_list ) ) {
                    $fieldsArray["ninja_" . $form["id"]][$field["key"]] = $field["label"];
                }
            }
        }
        # Loop ends
        # Adding extra fields || like Date and Time || Add more in future
        if ( !empty($fieldsArray) ) {
            foreach ( $fieldsArray as $formID => $formFieldsArray ) {
                # For Time
                if ( !isset( $formFieldsArray['automail_submitted_time'] ) ) {
                    $fieldsArray[$formID]['automail_submitted_time'] = "automail Form submitted  time";
                }
                # for Date
                if ( !isset( $formFieldsArray['automail_submitted_date'] ) ) {
                    $fieldsArray[$formID]['automail_submitted_date'] = "automail Form submitted date";
                }
            }
        }
        return array( TRUE, $FormArray, $fieldsArray );
    }
    
    /**
     *  formidable form  fields
     *  @since    1.0.0
     */
    public function formidable_forms_and_fields()
    {
        if ( !in_array( 'formidable/formidable.php', $this->active_plugins ) ) {
            return array( FALSE, "ERROR: formidable form  is Not Installed OR DB table is Not Exist" );
        }
        global  $wpdb ;
        $FormArray = array();
        # Empty Array for Value Holder
        $fieldsArray = array();
        # Empty Array for Holder
        $frmForms = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}frm_forms" );
        # Getting  Forms Database
        foreach ( $frmForms as $form ) {
            $FormArray["frm_" . $form->id] = "Formidable - " . $form->name;
            # Inserting ARRAY title
            # Getting Meta Fields || maybe i don't Know ;-D
            $fields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}frm_fields WHERE form_id= " . $form->id . " ORDER BY field_order" );
            # Getting  Data from Database
            foreach ( $fields as $field ) {
                # Default fields
                # freemius
                $field_list = array(
                    "text",
                    "textarea",
                    "number",
                    "email",
                    "phone",
                    "hidden",
                    "url",
                    "user_id",
                    "select",
                    "radio",
                    "checkbox",
                    "rte",
                    "date",
                    "time",
                    "star",
                    "range",
                    "password",
                    "address"
                );
                if ( in_array( $field->type, $field_list ) ) {
                    $fieldsArray["frm_" . $form->id][$field->id] = $field->name;
                }
            }
        }
        # Loop ends
        # Adding extra fields || like Date and Time || Add more in future
        if ( !empty($fieldsArray) ) {
            foreach ( $fieldsArray as $formID => $formFieldsArray ) {
                # For Time
                if ( !isset( $formFieldsArray['automail_submitted_time'] ) ) {
                    $fieldsArray[$formID]['automail_submitted_time'] = "automail Form submitted  time";
                }
                # for Date
                if ( !isset( $formFieldsArray['automail_submitted_date'] ) ) {
                    $fieldsArray[$formID]['automail_submitted_date'] = "automail Form submitted date";
                }
            }
        }
        return array( TRUE, $FormArray, $fieldsArray );
        # Inserting Data to the Main [$eventsAndTitles ] Array
    }
    
    /**
     *  wpforms fields 
     *  @since    1.0.0
     */
    public function wpforms_forms_and_fields()
    {
        if ( !count( array_intersect( $this->active_plugins, array( 'wpforms-lite/wpforms.php', 'wpforms/wpforms.php' ) ) ) ) {
            return array( FALSE, "ERROR: wp form is Not Installed OR DB Table is Not Exist" );
        }
        $FormArray = array();
        $fieldsArray = array();
        # Getting Data from Database
        global  $wpdb ;
        $wpforms = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} WHERE post_type = 'wpforms' " );
        foreach ( $wpforms as $wpform ) {
            $FormArray["wpforms_" . $wpform->ID] = "WPforms - " . $wpform->post_title;
            $post_content = json_decode( $wpform->post_content );
            foreach ( $post_content->fields as $field ) {
                # Default fields
                # freemius
                $field_list = array(
                    "name",
                    "text",
                    "email",
                    "textarea",
                    "number",
                    "number-slider",
                    "phone",
                    "address",
                    "date-time",
                    "url",
                    "password",
                    "hidden",
                    "rating",
                    "checkbox",
                    "radio",
                    "select",
                    "payment-single",
                    "payment-checkbox",
                    "payment-total",
                    "stripe-credit-card"
                );
                if ( in_array( $field->type, $field_list ) ) {
                    $fieldsArray["wpforms_" . $wpform->ID][$field->id] = $field->label;
                }
            }
            # Loop ends
        }
        # Loop ends
        # Adding extra fields || like Date and Time || Add more in future
        if ( !empty($fieldsArray) ) {
            foreach ( $fieldsArray as $formID => $formFieldsArray ) {
                # For Time
                if ( !isset( $formFieldsArray['automail_submitted_time'] ) ) {
                    $fieldsArray[$formID]['automail_submitted_time'] = "automail Form submitted  time";
                }
                # for Date
                if ( !isset( $formFieldsArray['automail_submitted_date'] ) ) {
                    $fieldsArray[$formID]['automail_submitted_date'] = "automail Form submitted date";
                }
            }
        }
        return array( TRUE, $FormArray, $fieldsArray );
    }
    
    #
    # FIXME:
    # do it after Upload || last off all forms
    /**
     *  WE forms fields 
     *  @since  1.0.0
     */
    public function weforms_forms_and_fields()
    {
        if ( !in_array( 'weforms/weforms.php', $this->active_plugins ) ) {
            return array( FALSE, "ERROR:  weForms  is Not Active  OR DB is not exist" );
        }
        $FormArray = array();
        $fieldsArray = array();
        $fieldTypeArray = array();
        global  $wpdb ;
        $weforms = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} WHERE post_type = 'wpuf_contact_form' " );
        $weFields = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} WHERE post_type = 'wpuf_input' " );
        foreach ( $weforms as $weform ) {
            $FormArray["we_" . $weform->ID] = 'weForms - ' . $weform->post_title;
        }
        foreach ( $weFields as $Field ) {
            foreach ( $FormArray as $weformID => $weformTitle ) {
                
                if ( $weformID == "we_" . $Field->post_parent ) {
                    $content_arr = unserialize( $Field->post_content );
                    $fieldsArray[$weformID][$content_arr['name']] = $content_arr['label'];
                    $fieldTypeArray[$weformID][$content_arr['name']] = $content_arr['template'];
                }
            
            }
        }
        # Adding extra fields || like Date and Time || Add more in future
        if ( !empty($fieldsArray) ) {
            foreach ( $fieldsArray as $formID => $formFieldsArray ) {
                # For Time
                if ( !isset( $formFieldsArray['automail_submitted_time'] ) ) {
                    $fieldsArray[$formID]['automail_submitted_time'] = "automail Form submitted  time";
                }
                # for Date
                if ( !isset( $formFieldsArray['automail_submitted_date'] ) ) {
                    $fieldsArray[$formID]['automail_submitted_date'] = "automail Form submitted date";
                }
            }
        }
        return array(
            TRUE,
            $FormArray,
            $fieldsArray,
            $fieldTypeArray
        );
    }
    
    /**
     * 	Under Construction 
     *  gravity forms fields 
     *  @since  1.0.0
     */
    public function gravity_forms_and_fields()
    {
        if ( !in_array( 'gravityforms/gravityforms.php', $this->active_plugins ) ) {
            return array( FALSE, "ERROR:  gravity forms  is Not Active  OR DB is not exist" );
        }
        if ( !class_exists( 'GFAPI' ) ) {
            return array( FALSE, "ERROR:  gravityForms class GFAPI is not exist" );
        }
        $gravityForms = GFAPI::get_forms();
        #check and Test
        
        if ( !empty($gravityForms) ) {
            # Empty array holder Declared
            $FormArray = array();
            $fieldsArray = array();
            $fieldTypeArray = array();
            # New Code Loop
            foreach ( $gravityForms as $form ) {
                $FormArray["gravity_" . $form["id"]] = "Gravity - " . $form["title"];
                # Form Fields || Check fields are set or Not
                if ( isset( $form['fields'] ) and is_array( $form['fields'] ) ) {
                    foreach ( $form['fields'] as $field ) {
                        
                        if ( empty($field['inputs']) ) {
                            # if there is no subfields
                            $fieldsArray["gravity_" . $form["id"]][$field["id"]] = $field["label"];
                            $fieldTypeArray["gravity_" . $form["id"]][$field["id"]] = $field["type"];
                        } else {
                            # Looping Subfields
                            foreach ( $field["inputs"] as $subField ) {
                                $fieldsArray["gravity_" . $form["id"]][$subField["id"]] = $field["label"] . ' (' . $subField["label"] . ')';
                                $fieldTypeArray["gravity_" . $form["id"]][$subField["id"]] = $field["type"];
                            }
                        }
                    
                    }
                }
            }
        } else {
            return array( FALSE, "ERROR: gravityForms form object is empty." );
        }
        
        return array(
            TRUE,
            $FormArray,
            $fieldsArray,
            $fieldTypeArray
        );
    }
    
    /**
     * forminator forms fields 
     * @since      1.0.0
     * @return     array   First one is CPS and Second one is CPT's Field source.
     */
    public function forminator_forms_and_fields()
    {
        if ( !in_array( 'forminator/forminator.php', $this->active_plugins ) ) {
            return array( FALSE, "ERROR: forminator form  is Not Installed OR no integration Exist" );
        }
        $FormArray = array();
        # Empty Array for Value Holder
        $fieldsArray = array();
        # Empty Array for Holder
        # Getting Forminator Fields
        $forms = Forminator_API::get_forms();
        # Check And Balance
        if ( !empty($forms) ) {
            # Looping the Forms
            foreach ( $forms as $form ) {
                # inserting Forms
                $FormArray["forminator_" . $form->id] = "forminator - " . $form->name;
                # Getting Fields
                $fields = get_post_meta( $form->id, 'forminator_form_meta' );
                # Check & balance
                
                if ( isset( $fields[0]['fields'] ) and !empty($fields[0]['fields']) ) {
                    # Looping the Fields
                    foreach ( $fields[0]['fields'] as $field ) {
                        if ( isset( $field['id'], $field['field_label'] ) ) {
                            $fieldsArray["forminator_" . $form->id][$field['id']] = $field['field_label'];
                        }
                    }
                    # Date And Time
                    $fieldsArray["forminator_" . $form->id]['automail_submitted_time'] = "automail Form submitted  time";
                    $fieldsArray["forminator_" . $form->id]['automail_submitted_time'] = "automail Form submitted date";
                }
            
            }
        }
        return array( TRUE, $FormArray, $fieldsArray );
    }
    
    /**
     * This Function will return [wordPress Pages] Meta keys.
     * @since      1.0.0
     * @return     array    This array has two values First one is Bool and Second one is the meta key array.
     */
    public function automail_pages_metaKeys()
    {
        # Global Db object
        global  $wpdb ;
        # Query
        $query = "SELECT DISTINCT({$wpdb->postmeta}.meta_key) \n\t\t\t\t\tFROM {$wpdb->posts} \n\t\t\t\t\tLEFT JOIN {$wpdb->postmeta} \n\t\t\t\t\tON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id \n\t\t\t\t\tWHERE {$wpdb->posts}.post_type = 'page' \n\t\t\t\t\tAND {$wpdb->postmeta}.meta_key != '' ";
        # execute Query
        $meta_keys = $wpdb->get_col( $query );
        # return Depend on the Query result
        
        if ( empty($meta_keys) ) {
            return array( FALSE, 'Error: Empty! No Meta key exist of the Post type page.' );
        } else {
            return array( TRUE, $meta_keys );
        }
    
    }
    
    /**
     * This Function will return [wordPress Posts] Meta keys.
     * @since      1.0.0
     * @return     array    This array has two values First one is Bool and Second one is the meta key array.
     */
    public function automail_posts_metaKeys()
    {
        # Global Db object
        global  $wpdb ;
        # Query
        $query = "SELECT DISTINCT({$wpdb->postmeta}.meta_key) \n\t\t\t\t  \tFROM {$wpdb->posts} \n\t\t\t\t\tLEFT JOIN {$wpdb->postmeta} \n\t\t\t\t\tON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id \n\t\t\t\t\tWHERE {$wpdb->posts}.post_type = 'post' \n\t\t\t\t\tAND {$wpdb->postmeta}.meta_key != '' ";
        # execute Query
        $meta_keys = $wpdb->get_col( $query );
        # return Depend on the Query result
        
        if ( empty($meta_keys) ) {
            return array( FALSE, 'Error: Empty! No Meta key exist of the Post.' );
        } else {
            return array( TRUE, $meta_keys );
        }
    
    }
    
    /**
     * This Function will return [wordPress Users] Meta keys.
     * @since      1.0.0
     * @return     array    This array has two values First one is Bool and Second one is meta key array.
     */
    public function automail_users_metaKeys()
    {
        # Global Db object
        global  $wpdb ;
        # Query
        $query = "SELECT DISTINCT({$wpdb->usermeta}.meta_key) FROM {$wpdb->usermeta}";
        # execute Query
        $meta_keys = $wpdb->get_col( $query );
        # return Depend on the Query result
        
        if ( empty($meta_keys) ) {
            return array( FALSE, 'Error: Empty! No Meta key exist of users.' );
        } else {
            return array( TRUE, $meta_keys );
        }
    
    }
    
    /**
     * This Function will return [wordPress Users] Meta keys.
     * @since      1.0.0
     * @return     array    This array has two vale First one is Bool and Second one is meta key array.
     */
    public function automail_comments_metaKeys()
    {
        # Global Db object
        global  $wpdb ;
        # Query
        $query = "SELECT DISTINCT({$wpdb->commentmeta}.meta_key) FROM {$wpdb->commentmeta}";
        # execute Query
        $meta_keys = $wpdb->get_col( $query );
        # return Depend on the Query result
        
        if ( empty($meta_keys) ) {
            return array( FALSE, 'ERROR: Empty! No Meta key exist on comment meta.' );
        } else {
            return array( TRUE, $meta_keys );
        }
    
    }
    
    /**
     * This Function will return [WooCommerce product] Meta keys.
     * @since      1.0.0
     * @return     array  This array has two values First one is Bool and Second one is meta key array.
     */
    public function automail_wooCommerce_product_metaKeys()
    {
        # Global Db object
        global  $wpdb ;
        # Query
        $query = "SELECT DISTINCT({$wpdb->postmeta}.meta_key) \n\t\t\t\t\tFROM {$wpdb->posts} \n\t\t\t\t\tLEFT JOIN {$wpdb->postmeta} \n\t\t\t\t\tON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id \n\t\t\t\t\tWHERE {$wpdb->posts}.post_type = 'product' \n\t\t\t\t\tAND {$wpdb->postmeta}.meta_key != '' ";
        # execute Query
        $meta_keys = $wpdb->get_col( $query );
        # return Depend on the Query result
        
        if ( empty($meta_keys) ) {
            return array( FALSE, 'Error: Empty! No Meta key exist of the Post type WooCommerce Product.' );
        } else {
            return array( TRUE, $meta_keys );
        }
    
    }
    
    /**
     * This Function will return [WooCommerce Order] Meta keys.
     * @since      1.0.0
     * @return     array    This array has two values First one is Bool and Second one is meta key array.
     */
    public function automail_wooCommerce_order_metaKeys()
    {
        # Global Db object
        global  $wpdb ;
        # Query
        $query = "SELECT DISTINCT({$wpdb->postmeta}.meta_key) \n\t\t\t\t\tFROM {$wpdb->posts} \n\t\t\t\t\tLEFT JOIN {$wpdb->postmeta} \n\t\t\t\t\tON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id \n\t\t\t\t\tWHERE {$wpdb->posts}.post_type = 'shop_order' \n\t\t\t\t\tAND {$wpdb->postmeta}.meta_key != '' ";
        # execute Query
        $meta_keys = $wpdb->get_col( $query );
        # return Depend on the Query result
        
        if ( empty($meta_keys) ) {
            return array( FALSE, 'Error: Empty! No Meta key exist of the post type WooCommerce Order.' );
        } else {
            return array( TRUE, $meta_keys );
        }
    
    }
    
    /**
     * This is a Helper function to check Table is Exist or Not 
     * If DB table Exist it will return True if Not it will return False
     * @since      1.0.0
     * @param      string    $data_source    Which platform call this function s
     */
    public function automail_dbTableExists( $tableName = null )
    {
        if ( empty($tableName) ) {
            return FALSE;
        }
        global  $wpdb ;
        $r = $wpdb->get_results( "SHOW TABLES LIKE '" . $wpdb->prefix . $tableName . "'" );
        
        if ( $r ) {
            return TRUE;
        } else {
            return FALSE;
        }
    
    }
    
    /**
     * This Function will get all Custom Post types 
     * @since      1.0.0
     * @return     array   First one is CPS and Second one is CPT's Field source.
     */
    public function automail_allCptEvents()
    {
        # Getting The Global wp_post_types array
        global  $wp_post_types ;
        # Check And Balance
        
        if ( isset( $wp_post_types ) && !empty($wp_post_types) ) {
            # CPT holder empty array declared
            $cpts = array();
            # List of items for removing
            $removeArray = array(
                "wpforms",
                "acf-field-group",
                "acf-field",
                "product",
                "product_variation",
                "shop_order",
                "shop_order_refund"
            );
            # Looping the Post types
            foreach ( $wp_post_types as $postKey => $PostValue ) {
                # if Post type is Not Default
                if ( isset( $PostValue->_builtin ) and !$PostValue->_builtin ) {
                    # Look is it on remove list, if not insert
                    if ( !in_array( $postKey, $removeArray ) ) {
                        # Pre populate $cpts array
                        
                        if ( isset( $PostValue->label ) and !empty($PostValue->label) ) {
                            $cpts[$postKey] = $PostValue->label . " (" . $postKey . ")";
                        } else {
                            $cpts[$postKey] = $postKey;
                        }
                    
                    }
                }
            }
            # Empty Holder Array for CPT events
            $cptEvents = array();
            # Creating events
            
            if ( !empty($cpts) ) {
                # Looping for Creating Extra Events Like Update and Delete
                foreach ( $cpts as $key => $value ) {
                    $cptEvents['cpt_new_' . $key] = 'CPT New ' . $value;
                    $cptEvents['cpt_update_' . $key] = 'CPT Update ' . $value;
                    $cptEvents['cpt_delete_' . $key] = 'CPT Delete ' . $value;
                }
                # Now setting default Event data Source Fields; Those events data source  are common in all WordPress Post type
                $eventDataFields = array(
                    "postID"            => "ID",
                    "post_authorID"     => "post author_ID",
                    "authorUserName"    => "author User Name",
                    "authorDisplayName" => "author Display Name",
                    "authorEmail"       => "author Email",
                    "authorRole"        => "author Role",
                    "post_title"        => "post title",
                    "post_date"         => "post date",
                    "post_date_gmt"     => "post date gmt",
                    "site_time"         => "Site Time",
                    "site_date"         => "Site Date",
                    "post_content"      => "post content",
                    "post_excerpt"      => "post excerpt",
                    "post_status"       => "post status",
                    "comment_status"    => "comment status",
                    "ping_status"       => "ping status",
                    "post_password"     => "post password",
                    "post_name"         => "post name",
                    "to_ping"           => "to ping",
                    "pinged"            => "pinged",
                    "post_modified"     => "post modified date",
                    "post_modified_gmt" => "post modified date GMT",
                    "post_parent"       => "post parent",
                    "guid"              => "guid",
                    "menu_order"        => "menu order",
                    "post_type"         => "post type",
                    "post_mime_type"    => "post mime type",
                    "comment_count"     => "comment count",
                    "filter"            => "filter",
                );
                # Global Db object
                global  $wpdb ;
                # Query for getting Meta keys
                $query = "SELECT DISTINCT({$wpdb->postmeta}.meta_key) \n\t\t\t\t\t\t\tFROM {$wpdb->posts} \n\t\t\t\t\t\t\tLEFT JOIN {$wpdb->postmeta} \n\t\t\t\t\t\t\tON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id \n\t\t\t\t\t\t\tWHERE {$wpdb->posts}.post_type != 'post' \n\t\t\t\t\t\t\tAND {$wpdb->posts}.post_type   != 'page' \n\t\t\t\t\t\t\tAND {$wpdb->posts}.post_type   != 'product' \n\t\t\t\t\t\t\tAND {$wpdb->posts}.post_type   != 'shop_order' \n\t\t\t\t\t\t\tAND {$wpdb->posts}.post_type   != 'shop_order_refund' \n\t\t\t\t\t\t\tAND {$wpdb->posts}.post_type   != 'product_variation' \n\t\t\t\t\t\t\tAND {$wpdb->posts}.post_type \t != 'wpforms' \n\t\t\t\t\t\t\tAND {$wpdb->postmeta}.meta_key != '' ";
                # execute Query for getting the Post meta key it will use for event data source
                $meta_keys = $wpdb->get_col( $query );
                # Inserting Meta keys to Main $eventDataFields Array;
                
                if ( !empty($meta_keys) and is_array( $meta_keys ) ) {
                    foreach ( $meta_keys as $value ) {
                        if ( !isset( $eventDataFields[$value] ) ) {
                            $eventDataFields[$value] = "CPT Meta " . $value;
                        }
                    }
                } else {
                    # insert to the log but don't return
                    # Error:  Meta keys  are empty;
                }
                
                # Everything seems ok, Now send the CPT events and Related Data source;
                return array(
                    TRUE,
                    $cpts,
                    $cptEvents,
                    $eventDataFields,
                    $meta_keys
                );
            } else {
                return array( FALSE, "ERROR: cpts Array is Empty." );
            }
        
        } else {
            return array( FALSE, "ERROR: wp_post_types global array is not exists or Empty." );
        }
    
    }
    
    /**
     * LOG ! For Good , This the log Method 
     * @since      1.0.0
     * @param      string    $file_name       	File Name . Use  [ get_class($this) ]
     * @param      string    $function_name     Function name.	 [  __METHOD__  ]
     * @param      string    $status_code       The name of this plugin.
     * @param      string    $status_message    The version of this plugin.
     */
    public function automail_log(
        $file_name = null,
        $function_name = null,
        $status_code = null,
        $status_message = null
    )
    {
        # Check and Balance
        if ( empty($status_code) ) {
            return array( FALSE, "ERROR: status_code is empty." );
        }
        # Status Message
        if ( empty($status_message) ) {
            return array( FALSE, "ERROR: status_message is empty." );
        }
        # Inserting to the Database
        $r = wp_insert_post( array(
            'post_content' => $status_message,
            'post_title'   => $status_code,
            'post_status'  => "publish",
            'post_excerpt' => json_encode( array(
            "fileName"     => $file_name,
            "functionName" => $function_name,
        ) ),
            'post_type'    => "automailLog",
        ) );
        # this function return
        if ( $r ) {
            return array( TRUE, "SUCCESS: log inserted successfully into database." );
        }
    }

}
# Wp mail Help
# https://developer.wordpress.org/reference/functions/wp_mail/
# Help user Roles
# https://publishpress.com/blog/where-are-wordpress-permissions-capabilities-in-the-database/
# wp_capabilities
#----------------------------------------------------------------------------------------------
# https://developer.wordpress.org/plugins/cron/
#----------------------------------------------------------------------------------------------
# Creating Custom Email template, Help link
# 1. https://webdesign.tutsplus.com/articles/build-an-html-email-template-from-scratch--webdesign-12770
# 2. IDEA for a Drag and Drop Email Template Builder