<?php

Validator::extend('alpha_spaces', function($attribute, $value)
{
    return preg_match('/^[\pL\s]+$/u', $value);
});

Validator::extend('alpha_numeric_spaces', function($attribute, $value)
{   return preg_match('/^[a-z0-9 .\-]+$/i', $value);
});
Validator::extend('alpha_numeric_spaces_comma', function($attribute, $value)
{   return preg_match('/^[a-z0-9, .\-]+$/i', $value);
});
