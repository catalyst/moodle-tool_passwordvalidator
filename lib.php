<?php
require_once(__DIR__.'/../../../config.php');
function password_validate($password, $test){
    global $USER;
    global $DB;
    //Only execute checks if user isn't admin or is test more
    if ((!(is_siteadmin()) || $test == true)) {
        $errs = '';

        //=====IRAP Certification checks=========
        //Complexity reqs X
        //Not only numbers X
        //no personal information X
        //no repeated characters X
        //no numeric sequences X

        //a single dictionary word
        //No repeat passwords

        //NIST Recommendations - beyond above IRAP
        //Black list of compromised passwords - HARD
        //No service name in password
        //=========================================

        // IRAP Complexity Reqs
        //ACSC Security Control 0421
        //Check for character sets.
        if (get_config('tool_password', 'irap_complexity')) {
            $lowercasepattern = '/[a-z]/';
            $lowercase = preg_match($lowercasepattern, $password);

            $uppercasepattern = '/[A-Z]/';
            $uppercase = preg_match($uppercasepattern, $password);

            $numberspattern = '/[0-9]/';
            $numbers = preg_match($numberspattern, $password);

            $specialcharspattern = '/[^a-z,A-Z,0-9]/';
            $specialchars = preg_match($specialcharspattern, $password);

            //Minimum length checks based on character sets used
            if (($lowercase === 1) && ($uppercase === 1)) {
                if (($specialchars === 1) || ($numbers === 1)){
                    // At least 3 character sets found
                    $minchars = 10;
                }
            } else {
                //Less than 3 charsets
                $minchars = 13;
            }

            if (strlen($password) < $minchars){
                $errs .= 'Password does not meet minimum length requirements. Passwords of only letters and numbers must be length 13. Adding numbers and special characters must be length 10';
            } 
        }

        //ACSC Security Control 0417
        //Not only numbers
        if (get_config('tool_password', 'irap_numbers')) {
            if (($lowercase === 0 && $uppercase === 0 && $specialchars === 0) && $numberspattern === 1){
                $errs .= 'Password can not consist of only numbers';
            }
        }

        // Personal Information Check.
        if (get_config('tool_password', 'personal_info')) { 
            //Check for fname, lname, city, username
            $badstrings = array($USER->firstname, $USER->lastname,
                            $USER->city, $USER->username);
            
            foreach ($badstrings as $string) {
                if (stripos($password, $string) !== false) {
                    $errs .= "Password contains identifying information.\n";
                    break;
                }
            }
        }


        // Check for sequential digits.
        if (get_config('tool_password', 'sequential_digits')) { 
            $seqdigits = 3;
            $digitpattern = '/\d{'.$seqdigits.',}/u';
            if (preg_match($digitpattern, $password) === 1){
                $errs .= "Password contains numeric sequence.\n";
            }
        }

        // Check for repeated characters.
        if (get_config('tool_password', 'repeated_chars')) {
            $characterpattern = '/(.)\1{1,}/';
            if (preg_match($characterpattern, $password) === 1){
                $errs .= "Password contains repeated characters.\n";
            }
        }

        // Check for blacklist phrases - eg Service name
        if (get_config('tool_password', 'phrase_blacklist')) {
            $phrasesraw = get_config('tool_password', 'phrase_blacklist_input');
            $phrases = explode(PHP_EOL, $phrasesraw);
            
            foreach ($phrases as $string) {
                $tstring = trim($string);
                if (stripos($password, $tstring) !== false) {
                    $errs .= "Password contains blacklisted phrase such as service name.\n";
                    break;
                }
            }
        }

        // Get time user last changed password
        $lastchange = 0;
        //$DB->get_field('tool_password', 'changetime', array('id' => ($USER->id)));

        return $lastchange;
    }
}

//Wrapper function
function tool_password_check_password_policy($password) {
    return password_validate($password, false);
}

