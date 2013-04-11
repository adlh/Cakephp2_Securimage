Description

This project was forked from:
https://github.com/sourjya/cakephp-securimage
to add support for CakePHP 2.x

All credits go to the original authors of securimage and the author of this
CakePHP component.

The url to the original project is:
http://chaos-laboratory.com/projects/cakephp-securimage-component/

This is a modified version of the docs found on the url above, to
mirror the changes for using with CakePHP 2.x.

Some major changes is the removal of all option-handling from the component
and the ability to override these through the $options array passed within the
components declaration in the controller. Please refer to the USAGE section for
more details. Also, there are some examples of how to integrate validation within
the form's model and integration within a plugin.


INSTALLATION:

First and foremost, you are required to obtain the SecurImage library and unzip
it into your app/Vendor folder.

If you are familiar with the CakePHP framework, usage of this component should
be fairly evident to you.

The zip file contains:

A component file that should be placed in Controller/Components folder of your
application, and A view file, that is to be placed in View/Elements folder.

If effect, the folder structure should be as follows:

```
app
├── Controller
│   └── Component
│       └── SecurimageComponent.php
├── Vendor
│   └── securimage (The folder you get from unzipping the Securimage library)
└── View
    └── Elements
        └── securimage.ctp
```

USAGE:

The component should be included like a standard CakePHP component, in
whichever controller you wish to use it. In my examples, I’m using a controller
named ContactsController. The corresponding model’s name is ContactForm.

Simple example:

```php
class ContactsController extends AppController {

    // Components
    public $components = array(
        'Securimage',
    );

}
```

Example with parameters:

```php
/**
 * Import SecurImage library to be able to use class constants like:
 *     Securimage::SI_CAPTCHA_MATHEMATIC
 */
require_once(APP . 'Plugin' . DS . 'MyPlugin' . DS . 'Vendor' . DS . 
    'securimage' . DS . 'securimage.php');

App::uses('RuetzAppController', 'Ruetz.Controller');

class ContactController extends RuetzAppController {

    // Components
    public $components = array(
        'Securimage' => array(
            'options' => array(
                'image_width' => 150,
                'image_height' => 56,
                'image_bg_color' => '#F7F7F7',
                'captcha_type' => Securimage::SI_CAPTCHA_MATHEMATIC,
            ),
        ),
    );

}
```

    Note: For a full list of available parameters (configuration options)
    please take a look into the Vendor/securimage/securimage.php file.
    



Example if using this Component inside a plugin, e.g. 'MyPlugin':

```php
class ContactsController extends MyPluginAppController {

    // Components
    public $components = array(
        'MyPlugin.Securimage',
    );

}
```

Also, if using this Component inside a plugin, then the Component should be
adjusted to use the controller and paths inside the plugin. The diff would look
something like this:

```diff
--- Controller/Component/SecurimageComponent.php
+++ Plugin/MyPlugin/Controller/Component/SecurimageComponent.php
@@ -3,7 +3,8 @@
 /**
  * Import SecurImage library
  */
-require_once(APP . 'Vendor' . DS . 'securimage' . DS . 'securimage.php');
+require_once(APP . 'Plugin' . DS . 'MyPlugin' . DS . 'Vendor' . DS .
+    'securimage' . DS . 'securimage.php');
 /**
  * Project:     Securimage Captcha Component<br />
  * File:        SecurimageComponent.php<br />
@@ -23,7 +24,7 @@
  * @version 0.5
  */
 
-App::uses('Component', 'Controller');
+App::uses('Component', 'MyPlugin.Controller');
 
 class SecurimageComponent extends Component {
```

It is important to add the new route to the securimage action of the
controller. This would look something like this:

```php
// Config/routes.php

CroogoRouter::connect('/contact/securimage/*', array('controller' => 'contact', 'action' => 'securimage'));

```
... or if using inside a plugin:

```php
// Plugin/MyPlugin/Config/routes.php

CroogoRouter::connect('/contact/securimage/*', array('plugin' => 'my_plugin', 'controller' => 'contact', 
    'action' => 'securimage'));

```

In your view file the CAPTCHA image can be displayed in the following manner:

```php
    $captcha = (
        '<img id="captcha" src="/contact/securimage/0" alt="CAPTCHA image" />' . 
        ' <a href="#" title="' . 
        __('Load a different image.') . '" onclick="' .
        "document.getElementById('captcha').src = " .
        "'/contact/securimage/' + Math.random(); return false;" .
        '"><i class="icon-refresh"></i></a> ');
    $this->Form->input('captcha_code', array(
        'label' => __('Please solve the following puzzle:'),
        'before' => '',
        'between' => $captcha,
        'after' => '',
        'class' => 'input-mini'));

```

For the validation, you can add the 'captcha_code' field to the form's Model (in my
case I'm using a model within my plugin without a table to handle the validation):

```php
// Plugin/MyPlugin/Model/ContactForm.php

App::uses('MyPluginAppModel', 'MyPlugin.Model');
/**
 * Import SecurImage library for checking captcha_code field
 */
require_once(APP . 'Plugin' . DS . 'MyPlugin' . DS . 'Vendor' . DS . 'securimage' . DS . 'securimage.php');

class ContactForm extends MyPluginAppModel {
    public $name = 'ContactForm';

    // don't want this model on db, just use it for validation
    public $useTable = false;

    protected $_schema = array(
        // many fields
        'captcha_code' => array('type' => 'string', 'null' => false, 'default' => '',
            'length' => '3'),
    );

    public $validate = array(
        // some field validations
        'captcha_code' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'You need to solve the puzzle to continue.',
            ),
            'validCode' => array(
                'rule' => array('checkCaptcha'),
                'message' => 'The answer you entered was incorrect.',
            ),
        ),
    );

    public function checkCaptcha($check) {
        $securimage = new Securimage();
        return $securimage->check($check['captcha_code']);
    }
}
```
