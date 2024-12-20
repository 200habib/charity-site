<?php

namespace App\Form;

trait RegexTrait
{
    /*
     
Minimum eight characters
At least one upper case letter
At least one lower case letter
At least one number
At least one special character (#?!@$ %^&*-_)
*/
public const STRONGPASSWORD = "/^(?=.?[A-Z])(?=.?[a-z])(?=.?[0-9])(?=.?[#?!@$ %^&*-]).{6,}$/";
}