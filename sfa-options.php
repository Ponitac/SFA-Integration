<?php
class tLMSOptions
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Define Constructor
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_sfa_option_page' ) );
        add_action( 'admin_init', array( $this, 'initiate_page' ) );
    }

    /**
     * Add options page
     */
    public function add_sfa_option_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Talent LMS API', 
            'TalentLMS API Settings', 
            'administrator', 
            'sfa-tLMS-settings', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'sfa_tLMS_options' );
        ?>
        <div class="wrap">
            <h1>TalentLMS API Settings</h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'sfa_tLMS_option_group' );
                do_settings_sections( 'sfa-tLMS-settings' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function initiate_page()
    {        
        register_setting(
            'sfa_tLMS_option_group', // Option group
            'sfa_tLMS_options', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'tLMS_section_id', // ID
            'API Configuration', // Title
            array( $this, 'print_section_info' ), // Callback
            'sfa-tLMS-settings' // Page
        );  

        add_settings_field(
            'sfa_domain', // ID
            'TalentLMS Domain', // Title 
            array( $this, 'sfa_domain_callback' ), // Callback
            'sfa-tLMS-settings', // Page
            'tLMS_section_id' // Section           
        );      

        add_settings_field(
            'sfa_key', 
            'API Key', 
            array( $this, 'sfa_key_callback' ), 
            'sfa-tLMS-settings', 
            'tLMS_section_id'
        );      
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['sfa_domain'] ) )
            $new_input['sfa_domain'] = sanitize_text_field(( $input['sfa_domain'] );

        if( isset( $input['sfa_key'] ) )
            $new_input['sfa_key'] = sanitize_text_field( $input['sfa_key'] );

        return $new_input;
    }
    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Please enter the following Information to activate the API Interface:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function sfa_domain_callback()
    {
        printf(
            '<input type="text" id="sfa_domain" name="sfa_tLMS_options[sfa_domain]" value="%s" />',
            isset( $this->options['sfa_domain'] ) ? esc_attr( $this->options['sfa_domain']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function sfa_key_callback()
    {
        printf(
            '<input type="text" id="sfa_key" name="sfa_tLMS_options[sfa_key]" value="%s" />',
            isset( $this->options['sfa_key'] ) ? esc_attr( $this->options['sfa_key']) : ''
        );
    }
}

if( is_admin() )
    $tLMS_Options = new tLMSOptions();