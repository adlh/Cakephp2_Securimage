Description

This project was forked from:
https://github.com/sourjya/cakephp-securimage
to add support for CakePHP 2.x

All credits go to the original authors of securimage and the author of this
CakePHP component.

The url to the original project is:
http://chaos-laboratory.com/projects/cakephp-securimage-component/

This is a slightly modified version of the docs found on the url above, to
mirror the changes for using with CakePHP 2.x:

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
named ContactsController. The corresponding model’s name is Contact.

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
class ContactsController extends AppController {

    // Components
    public $components = array(
        'Securimage' => array(
            'code_length' => 6,
            'font_size' => 60,
            'image_width' => 200,
            'image_height' => 70,
            'image_bg_color' => '#F7F7F7',
            'line_color' => '#D3D3D3',
            'multi_text_color' => '#8E67D6,#B98B83,#529071,#7C3E39,#E07E6A,#46765D',
            'num_lines' => 8,
            'perturbation' => 0.5,
            'text_transparency_percentage' => 20,
            'use_multi_text' => true,
            'use_wordlist' => true,
        ),
    );

}
```

    Note: For a full list of available parameters (configuration options) please
    take a look into the component file. Details of each option are included in it
    in PHP Doc format. 



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
@@ -3,11 +3,11 @@
/**
* Import SecurImage library
*/
-App::import( 'Vendor', 'Securimage', array( 'file' => 'securimage' . DS . 'securimage.php' ) );
+require_once(APP . 'Plugin' . DS . 'MyPlugin' . DS . 'Vendor' . DS . 'securimage' . DS . 'securimage.php');
/**
* Define path to library
*/
-define( 'SECURIMAGE_VENDOR_DIR', APP . 'Vendor' . DS . 'securimage/' );
+define( 'SECURIMAGE_VENDOR_DIR', APP . 'Plugin' . DS . 'MyPlugin' . DS . 'Vendor' . DS . 'securimage/' );

/**
* Project:     Securimage Captcha Component<br />
@@ -24,7 +24,7 @@
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
CroogoRouter::connect('/contact/securimage/*', array('plugin' => 'my_plugin', 'controller' => 'contact', 'action' => 'securimage'));

```

In your view file the CAPTCHA image can be displayed in the following manner:

```html
    <div id="captcha_container">
        <img id="captcha_img" src="/contacts/securimage/0" alt="CAPTCHA image" />
        <img id="captcha_reload" src="/img/icon-reload.png" title="Refresh" />
        <input id="captcha_text" name="data[Contact][captcha_text]" value="" />
    </div>
```

The purpose of the second image is to act like a refresh button, in case your
captcha is unintelligible. A little bit of javascript can aid in refreshing the
image dynamically.

```js
    // Using jQuery
    $('#captcha_reload').click( function() {
        // Append random number to prevent caching
        $('#captcha_img').attr('src', '/contacts/securimage/' + Math.random());
        $('#captcha_text').val('');
    });
```

