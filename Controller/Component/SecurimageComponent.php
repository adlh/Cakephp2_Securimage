<?php

/**
 * Import SecurImage library
 */
require_once(APP . 'Vendor' . DS . 'securimage' . DS . 'securimage.php');
/**
 * Project:     Securimage Captcha Component<br />
 * File:        SecurimageComponent.php<br />
 *
 * Coded to be compatible with the Configuration Options of<br />
 * SecurImage Library v2.0.2 available from http://www.phpcaptcha.org or<br />
 * The SecurImage GitHub Respository at https://github.com/dapphp/securimage<br />
 *
 * Securimage Captcha Component homepage
 * @link http://chaos-laboratory.com/projects/cakephp-securimage-component/
 *
 * Securimage Captcha Component repository on GitHub
 * @link https://github.com/sourjya/cakephp-securimage/
 *
 * @author Sourjya Sankar Sen <sourjya@chaos-laboratory.com>
 * @license MIT
 * @version 0.5
 */

App::uses('Component', 'Controller');

class SecurimageComponent extends Component {

    protected $controller = null;

    /**
     * The $options array may contain all overriding settings found on
     * securimage.php file and can be passed within the components array
     * on the controller. Example of usage:
     *
     * public $components = array(
     *     'Securimage' => array(
     *         'options' => array(
     *              'captcha_type' => 1,
     *              'image_width' => 150,
     *              'image_height' => 56,
     *          ),
     *     ),
     * );
     */
    public $options = array();

    /**
     * Initializes the Component
     * @param object $controller
     * @access public
     */
    public function initialize(Controller $controller) {
        $controller->Securimage = new Securimage($this->options);
    }

    /**
     *
     * @param object $controller
     * @access public
     */
    public function startup(Controller $controller) {
        $controller->set('securimage', $controller->Securimage);
        // Generate Captcha
        if($controller->params['action'] == 'securimage')
            $this->_generateCaptcha($controller);
    }

    /**
     *
     * @param object $controller
     * @access public
     */
    public function shutdown(Controller $controller) {}

    /**
     * Display the Captcha Image
     * @access private
     * @param object $controller
     */
    private function _generateCaptcha(Controller $controller) {
        // A blank layout
        $controller->autoLayout = false;
        // Create an image and store it in a viewVar
        $controller->set('captcha_data',
            $controller->Securimage->show());
    }
}
