<?php

return [
    /*
      |--------------------------------------------------------------------------
      | Package Configuration Option
      |--------------------------------------------------------------------------
     */
    // Set this to false if you would like to use your own EmailTemplateController you then need to setup your EmailTemplate routes manually in your routes.php file
    'setup_email_template_routes' => true,
    //'override_validation' => FALSE,
    
    // Mention form validation array
    'validation' => [
        'template_code' => 'required|min:3|max:190|unique:email_templates,template_code',
        'email_subject' => 'required|min:3|max:190',
        'email_file_name' => 'required',
        'status' => 'required|in:Active,Inactive'
    ],
    'validation_update' => [
        'email_subject' => 'required|min:3|max:190',
    ],
];
