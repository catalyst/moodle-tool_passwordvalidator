<?php
require_once(__DIR__.'/../../../config.php');
function password_validate($password, $test){
    global $USER;
    global $DB;
    //Only execute checks if user isn't admin or is test mode
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

        // Check for password changes on the user account within lockout period.
        if (get_config('tool_password', 'time_lockout')) {
            $lastchanges = $DB->get_records('user_password_history', array('userid' => ($USER->id)), 'timecreated DESC');
            $currenttime = time();
            //get first elements timecreated, order from DB query
            $timechanged = reset($lastchanges)->timecreated;
            //Calculate 24 hr constant in seconds
            $day = 24*60*60;

            //Set the time modifier based on configuration
            $inputtime = get_config('tool_password', 'time_lockout_input');
            if ($inputtime <= 0){
                $modifier = $day;
            } else {
                $modifier = $inputtime;
            }

            if ($timechanged >= ($currenttime - $modifier)){
                $errs .= 'Password already changed recently. Please try again later.';
            }
        }

        //Check against HaveIBeenPwned.com password breach API
        if (get_config('tool_password', 'password_blacklist')) {
            $API = 'https://api.pwnedpasswords.com/range/';
            //Get first 5 chars of hash to search API for
            $pwhash = sha1($password);
            $searchstring = substr($pwhash, 0, 5);

            //Get API response
            $URL = $API .= $searchstring;
            $response = file_get_contents($URL);

            //Check for presence of hash in response
            $shorthash = substr($pwhash, 5);
            if (stripos($response, $shorthash) !== false) {
                $errs .= 'Password found in online breached passwords collection.';
            }
        }

        return $errs;
    }
}

//Wrapper function
function tool_password_check_password_policy($password) {
    return password_validate($password, false);
}

