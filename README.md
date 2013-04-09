Description

This project was forked from:
http://chaos-laboratory.com/cakephp-securimage-component/ 
to add support for CakePHP 2.x

All credits go to the original author.

The url to the original project is:
http://chaos-laboratory.com/projects/cakephp-securimage-component/

This is a slightly modified version of the docs, to mirror the changes to use
with CakePHP 2.x:

INSTALLATION:

First and foremost, you are required to obtain the SecurImage library and unzip
it into your app/Vendor folder.

If you are familiar with the CakePHP framework, usage of this component should
be fairly evident to you.

The zip file contains:

    A component file that should be placed in Controller/Components folder of
    your application, and A view file, that is to be placed in View/Elements
    folder.

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

