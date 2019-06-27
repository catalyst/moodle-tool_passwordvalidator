<?php
function tool_password_check_password_policy($password){
    global $USER;
    //Only execute checks if user isn't admin
    if (!(is_siteadmin())) {
        $errs = '';

        //Check for fname, lname, city, username, email address, domain name of email
        $email = $USER->email;
        
        $badstrings = array($USER->firstname, $USER->lastname,
                        $USER->city, $USER->username,
                        );
        $found = false;
        
        foreach ($badstrings as $string) {
            if (stripos($password, $string) !== false) {
                $errs .= "Found occurance of $string in password. Please remove.\n";
            }
        }

        //Check for significant dates
        return $errs;
    }
}


