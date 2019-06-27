<?php
function tool_password_check_password_policy($password){
    global $USER;
    //Only execute checks if user isn't admin
    if (!(is_siteadmin())) {
        $errs = '';

        //IRAP Certification checks
        //no repeated characters X
        //a single dictionary word
        //no numeric sequences X
        //no personal information X
        //No repeat passwords

        //NIST Recommendations - beyond above IRAP
        //Black list of compromised passwords - HARD
        //No service name in password

        // Personal Information
        //Check for fname, lname, city, username
        $badstrings = array($USER->firstname, $USER->lastname,
                        $USER->city, $USER->username);
        
        foreach ($badstrings as $string) {
            if (stripos($password, $string) !== false) {
                $errs .= "Found occurance of $string in password. Please remove.\n";
            }
        }

        // Check for sequential digits.
        $seqdigits = 3;
        $digitpattern = '/\d{'.$seqdigits.',}/u';
        if (preg_match($digitpattern, $password) === 1){
            $errs .= "Password contains numeric sequence.\n";
        }

        // Check for repeated characters.
        $characterpattern = '/(.)\1{1,}/';
        if (preg_match($characterpattern, $password) === 1){
            $errs .= "Password contains repeated characters.\n";
        }

        return $errs;
    }
}


