<?php

/**
 * Import SecurImage library
 */
App::import( 'Vendor', 'Securimage', array( 'file' => 'securimage' . DS . 'securimage.php' ) );
/**
 * Define path to library
 */
define( 'SECURIMAGE_VENDOR_DIR', APP . 'Vendor' . DS . 'securimage/' );

/**
 * Project:     Securimage Captcha Component<br />
 * File:        securimage.php<br />
 *
 * Coded to be compatible with the Configuration Options of<br />
 * SecurImage Library v2.0.2 available from http://www.phpcaptcha.org or<br />
 * The SecurImage GitHub Respository at https://github.com/dapphp/securimage<br />
 *
 * @link http://chaos-laboratory.com/projects/cakephp-securimage-component/ Securimage Captcha Component homepage
 * @link https://github.com/sourjya/cakephp-securimage/ Securimage Captcha Component repository on GitHub
 * @author Sourjya Sankar Sen <sourjya@chaos-laboratory.com>
 * @license MIT
 * @version 0.5
 */
class SecurimageComponent extends Object {

    /**
     * Controller reference
     * @var object
     */
    var $controller = null;
    /**
     * Type of image to be generated (jpg, gif or png)
     * @var string
     */
    var $image_type = 'jpg';
    /**
     * Height of the Captcha image
     * @var int
     */
    var $image_height = 75;
    /**
     * Width of the captcha image
     * @var int
     */
    var $image_width = 350;
    /**
     * The background color for the image as a hexadecmial value (prepended by a '#')
     * @var string
     */
    var $image_bg_color = '#ffffff';
    /**
     * Whether to draw the lines over the text
     * @var bool
     */
    var $draw_lines_over_text = false;
    /**
     * Number of vertical and horizontal lines to draw on the image<br />
     * Ignored if $draw_lines_over_text is set to false
     * @see $draw_lines_over_text
     * @var int
     */
    var $num_lines = 6;
    /**
     * Color of lines drawn over text as a hexadecmial value (prepended by a '#')<br />
     * Ignored if $draw_lines_over_text is set to false
     * @see $draw_lines_over_text
     * @var string
     */
    var $line_color = '#cccccc';
    /**
     * Text to write at the bottom corner of captcha image<br />
     * No signature is drawn if this parameter is not set
     * @var string
     */
    var $image_signature = null;
    /**
     * Color to use for writing signature text<br />
     * Ignored if $image_signature is set to null
     * @see $image_signature
     * @var string
     */
    var $signature_color = '#000000';
    /**
     * Absolute path to the directory containing background images<br />
     * A random one is picked everytime. GIF, JPG or PNG files only<br />
     * No background images are used if this parameter is not set
     * @var string
     */
    var $background_directory = null;
    /**
     * Whether to use a gd font instead of a ttf font
     * @var bool
     */
    var $use_gd_font = false;
    /**
     * Path to GD font file<br />
     * relative to SecurImage library folder under vendors<br />
     * Used only if $use_gd_font is set to true
     * @var string
     */
    var $gd_font_file = null;
    /**
     * The approxiate size of the font in pixels<br />
     * Used only if $use_gd_font is set to true
     * @see $use_gd_font
     * @var int
     */
    var $gd_font_size = 50;
    /**
     * The path to the ttf font file to load<br />
     * relative to SecurImage library folder in vendors
     * @var string
     */
    var $ttf_file = 'fonts/AHGBold.ttf';
    /**
     * The color of the text as a hexadecmial value (prepended by a '#')<br />
     * Ignored if $use_multi_text set to true
     * @see $use_multi_text
     * @var string
     */
    var $text_color = '#000000';
    /**
     * Whether to use multiple colors for each character
     * @var bool
     */
    var $use_multi_text = false;
    /**
     * Comma separated list of colors as hexadecmial values (prepended by '#'-es)<br />
     * Used only if $use_multi_text is set to true
     * @see $use_multi_text
     * @var string
     */
    var $multi_text_color = '#E36B63,#B78D89,#3A9E67,#A7878D,#B78D89,#F3705E,#8B47FD,#2D985D,#9A4CF9';
    /**
     * Whether to make characters appear transparent
     * @var bool
     */
    var $use_transparent_text = true;
    /**
     * The percentage of transparency, 0 to 100<br />
     * Works only if $use_transparent_text is set to true
     * @var int
     * @see $use_transparent_text
     */
    var $text_transparency_percentage = 45;
    /**
     * How much to distort image, higher = more distortion<br />
     * Distortion is only available when using TTF fonts i.e.<br />
     * when $use_gd_font is set to false
     * @var float
     * @see $use_gd_font
     */
    var $perturbation = 0;
    /**
     * Maximum angle of text in degrees
     * @var int
     */
    var $text_angle_maximum = 21;
    /**
     * Minimum angle of text in degrees
     * @var int
     */
    var $text_angle_minimum = -21;
    /**
     * The character set used for generating Captcha codes in the image<br />
     * Used only if $use_wordlist is set to false
     * @see $use_wordlist
     * @var string
     */
    var $charset = 'ABCDEFGHKLMNPRSTUVWYZ23456789';
    /**
     * The length of the code to generate<br />
     * Used only if $use_wordlist is set to false
     * @see $use_wordlist
     * @var int
     */
    var $code_length = 5;
    /**
     * Use wordlist instead of random codes
     * @var bool
     */
    var $use_wordlist = false;
    /**
     * Generate codes using words in this file<br />
     * relative to SecurImage library folder under vendors<br />
     * Used only if $use_wordlist is set to true
     * @see $use_wordlist
     * @var string
     */
    var $wordlist_file = 'words/words.txt';
    /**
     * Path to the WAV files to use for the audio playback of Captcha text, include trailing /<br />
     * relative to SecurImage library folder in vendors
     * @var string
     */
    var $audio_path = 'audio/';
    /**
     * Type of audio file to generate (mp3 or wav)
     * @var string
     */
    var $audio_format = 'mp3';
    /**
     * The session name to use (blank for default)
     * @var string
     */
    var $session_name = ''; //
    /**
     * The amount of time in seconds that a code remains valid<br />
     * Any non-numeric or value less than 1 disables this functionality
     * @var int
     */
    var $expiry_time = -1;
    /**
     * Use an SQLite database for storing codes as a backup to sessions
     * @var bool
     */
    var $use_sqlite_db = false;
    /**
     * Absolute path to SQLite database for storing codes as a backup to sessions<br />
     * For Security reasons, put this file in a directory below the web root or one that is restricted
     * Works only if $use_sqlite_db is set to true
     * @see $use_sqlite_db
     * @var string
     */
    var $sqlite_database = null;

    /** ==============================================================
     * 				Public
     *  ============================================================== */

    /**
     * Initializes the Component
     * @param object $controller
     * @param array $settings
     * @access public
     */
    public function initialize( &$controller, $settings = array( ) ) {
        // Saving the controller reference for later use
        $this->controller = &$controller;
        // Instantiate SecurImage class and store a reference
        $this->controller->Securimage = & new Securimage();
        // Set Configuration options for Component
        $this->_set( $settings );
        // Set the same for SecurImage
        // This second step is required as certain parameters need to be transformed to
        // native SecurImage formats
        $this->_setVendorConfigOptions( $settings );
    }

    /**
     *
     * @param object $controller
     * @access public
     */
    public function startup( &$controller ) {
        $this->controller->set( 'securimage', $controller->Securimage );
        $this->controller->set( 'captcha_image_url', Router::url( '/' . $this->controller->plugin . '/' . $this->controller->name . '/securimage/0', true ) ); //url for the captcha image
        // Generate Captcha
        if( $this->controller->params['action'] == 'securimage' )
            $this->_generateCaptcha();
    }

    /**
     *
     * @param object $controller
     * @access public
     */
    public function shutdown( &$controller ) {

    }

    /** ==============================================================
     * 				Private
     *  ============================================================== */

    /**
     * Sets configuration options for Securimage class
     *
     * @param array $settings
     * @access private
     * @return type
     */
    private function _setVendorConfigOptions( $settings = array( ) ) {
        if( empty( $settings ) )
            return;
        // Extract & set values that need to be transformed
        if( isset( $settings['image_type'] ) ) {
            switch( $settings['image_type'] ) {
                case 'jpg':
                default:
                    $this->controller->Securimage->image_type = SI_IMAGE_JPEG;
                    break;
                case 'gif':
                    $this->controller->Securimage->image_type = SI_IMAGE_GIF;
                    break;
                case 'png':
                    $this->controller->Securimage->image_type = SI_IMAGE_PNG;
                    break;
            }
            unset( $settings['image_type'] );
        }
        if( isset( $settings['ttf_file'] ) ) {
            $this->controller->Securimage->ttf_file = SECURIMAGE_VENDOR_DIR . $settings['ttf_file'];
            unset( $settings['ttf_file'] );
        }
        if( isset( $settings['gd_font_file'] ) ) {
            $this->controller->Securimage->gd_font_file = SECURIMAGE_VENDOR_DIR . $settings['gd_font_file'];
            unset( $settings['gd_font_file'] );
        }
        if( isset( $settings['audio_path'] ) ) {
            $this->controller->Securimage->audio_path = SECURIMAGE_VENDOR_DIR . $settings['audio_path'];
            unset( $settings['audio_path'] );
        }
        if( isset( $settings['wordlist_file'] ) ) {
            $this->controller->Securimage->wordlist_file = SECURIMAGE_VENDOR_DIR . $settings['wordlist_file'];
            unset( $settings['wordlist_file'] );
        }
        if( isset( $settings['audio_path'] ) ) {
            $this->controller->Securimage->audio_path = SECURIMAGE_VENDOR_DIR . $settings['audio_path'];
            unset( $settings['audio_path'] );
        }
        if( isset( $settings['image_bg_color'] ) ) {
            $this->controller->Securimage->image_bg_color = new Securimage_Color( $settings['image_bg_color'] );
            unset( $settings['image_bg_color'] );
        }
        if( isset( $settings['line_color'] ) ) {
            $this->controller->Securimage->line_color = new Securimage_Color( $settings['line_color'] );
            unset( $settings['line_color'] );
        }
        if( isset( $settings['signature_color'] ) ) {
            $this->controller->Securimage->signature_color = new Securimage_Color( $settings['signature_color'] );
            unset( $settings['signature_color'] );
        }
        if( isset( $settings['multi_text_color'] ) ) {
            $this->controller->Securimage->multi_text_color = $this->_toBgColors( $settings['multi_text_color'] );
            unset( $settings['multi_text_color'] );
        }
        // Set the rest
        foreach( $settings as $key => $value )
            $this->controller->Securimage->{$key} = $value;
    }

    /**
     * Display the Captcha Image
     * @access private
     * @param object $controller
     */
    private function _generateCaptcha() {
        // A blank layout
        $this->controller->autoLayout = false;
        // Create an image and store it in a viewVar
        $this->controller->set( 'captcha_data', $this->controller->Securimage->show() );
    }

    /**
     * Converts a comma delimited string of hexadecmial color codes to<br />
     * an array of native Securimage_Color objects
     * @access private
     * @param string $bgColors
     * @return Securimage_Color
     */
    private function _toBgColors( $bgColors = null ) {
        if( !$bgColors )
            return;
        foreach( explode( ',', trim( $bgColors, ' ,' ) ) as $color )
            $siColors[] = new Securimage_Color( $color );
        return $siColors;
    }

}
