<?php
function tool_password_check_password_policy($password){
    global $USER;
    //Only execute checks if user isn't admin
    if (!(is_siteadmin())) {
        $errs = '';

        //Check for fname, lname, city
        $badstrings = array($USER->firstname, $USER->lastname,
                            $USER->city);
        $found = false;
        
        foreach ($badstrings as $string){
            $found = stripos($password, $string);
        }

        if ($found !== false){
            $errs .= 'Found Identifying substring';
        }

        return $errs;
    }
}


