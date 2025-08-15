<?php

namespace Indapoint\EmailTemplate;

use Illuminate\Support\Facades\Facade;

/**
 * Email Template Facade
 */
class EmailTemplateFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'email_template';
    }
}
