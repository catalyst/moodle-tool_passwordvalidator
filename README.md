<a href="https://travis-ci.org/catalyst/moodle-tool_passwordvalidator">
<img src="https://travis-ci.org/catalyst/moodle-tool_passwordvalidator.svg?branch=master">
</a>

# Password Policy Enforcer

A tool for enforcing various security standards and guidelines for passwords for Moodle. This plugin aims for compliance with
the Australian Information Security Manual (currently May 2019), and above that, the NIST standards from the document 800-63B.

Many of the controls are optional and user configurable, with the most safe values set by default, but allow for great customization
for any configuration, while enforcing safe, sensible guidelines for passwords.

* [Security Controls](#security-controls)
* [Moodle Configuration Checker](#moodle-configuration-checker)
* [Password Tester](#password-tester)
* [Branches](#branches)
* [Installation](#installation)
* [Templates](#templates)
* [Testing](#testing)
* [Support](#support)
* [Thanks](#thanks)

Security Controls
-----------------

### Enable Plugin:

This control enables or disables the plugin. This is set by off by default, to prevent admins from setting an unwanted password policy simply by installing the plugin. It is recommended to configure a suitable password policy, and then enable this control to begin enforcing it.

### ISM Complexity Standards

The Australian ISM recommends a minimum password length of 13 characters for passwords consisting of only letters,
both uppercase and lowercase. For passwords that contain at least 3 of the the following: lowercase letters, uppercase letters, numbers,
special characters, the minimum length must be 10 characters. Enable this control to enforce this minimum length policy.

### Minimum Simple Complexity Length

This box allows for input of an integer value to be the minimum number of characters allowed in simple passwords, consisting of only letters, both uppercase and lowercase. The Australian ISM recommends this length be 13, which is the default value.

### Minimum Complex Complexity Length

This box allows for input of an integer value to be the minimum number of characters allowed in complex passwords, consisting
of a combination of lowercase letters, uppercase letters, numbers and/or special characters. The Australian USM recommend this length be 10, which is the default value.

### Enforce Letters and Characters in Password

The Australian ISM recommends that passwords are not allowed to be constructed of just
numbers and special characters, and must contain some letters. Enable this control to enforce that passwords are not constructed of only numeric characters or combinations
of both numbers and special characters, and must contain letters.

### Dictionary Word Count Checking

The Australian Cyber Security Centre recommends that passwords not be based off of a single dictionary word. This control checks words contained in passwords against a dictionary file, checking the number of occurences to ensure passwords are based off more than 1 word.

### Dictionary File Name

This box allows for inputs of a file to use as the dictionary file for checking against. The location that these files are stored in is /passwordvalidator/dictionary.

### Maximum Sequential Digits Input

The Australian ISM recommends that passwords may not contain sequences of numbers, which may be a date or other significant number. This box allows for input of an integer value to be the maximum number of sequential digits enforced in the control above. This defaults to 2. It is recommended to not allow this control to be higher, as people may include dates in their password, e.g. DDM or YYY. Set this to 0 to disable this control.

### Maximum Repeated Characters Input

This control stops users from constructing passwords that contain repeated series of the same character, such as 'TTTTTTTTTTTTT', which satisfies length requirements but is very easy to guess. This box allows for input of an integer value to be the maximum number of the same character sequentially inside the password.
This defaults to 2, which allows for words that contain double letters, but excludes longer series of characters. Set this to 0 to disable this control.

### Personal Information Checker

The Australian ISM recommends that passwords do not contain any identifying information about the user.
This control checks the password for any known information about the user: Firstname, Lastname, City, Username. If any of the supplied information
is found within a password, case insensitive, the password is rejected. Enable this control to check for personal information in the password.

### Enforce Phrase Blacklisting

This control is based on NIST recommendations that passwords not be able to allow the service name inside of passwords.
This control allows for admins to specify a list of bad words of phrases to disallow in passwords, such as the platform or service name.
Enable this control to blacklist phrases from passwords.

### Phrase Blacklist Entry

This box allows for the input of phrases to blacklist from passwords. The default phrase is moodle here. Additional phrases
should be entered on a new line each. Phrases are case-insensitive, e.g. moodle will match to MOOdle. Multiple word phrases should be broken to seperate lines.

### Password Change Lockout Period

Enable control to enforce a lockout period, during which users are unable to change their passwords.

### Password Change Lockout Period Input

The Australian ISM recommends that users are not able to change their passwords more often than once every 24 hours. This box allows users to specify a lockout period from the time the password was last changed. Entering a positive number in seconds will enforce a period of that long from the time of last change. Set this to 0 to disable this control.

### Check Password Against Blacklist

NIST recommends that passwords are checked against a blacklist of known bad passwords from data breaches. Enabling this control checks the **hash** of the password against the HaveIBeenPwned breached passwords API, and disallows passwords that have been found in any of the catalogued breaches. The API only receives a partial hash so even if haveibeenpwned was compromised your good password hashes haven't been leaked. 

See the full details here:

https://haveibeenpwned.com/API/v3#PwnedPasswords

Moodle Configuration Checker
----------------------------
This will automatically check the moodle configuration for settings that are either relied on by the plugin, or conflict with the plugin. It checks the password policy enforced by Moodle, and the controls within the policy. It is recommended to have the Moodle control "Password Policy" enabled, as it is required for new users to view information about the password policy inside of the plugin. It is recommended to set all of the controls for Password Policy to 0, as it is better to use the password policy controls inside this plugin. The specific controls that should be 0 are Password Length, Digits, Lowercase Letters, Uppercase Letters, Non-alphanumeric characters and Consecutive Identical characters.

Alternatively, to enfore the correct configuration, add these lines to config.php:
``` php
$CFG->passwordpolicy = true;
$CFG->minpasswordlength = 0;
$CFG->minpassworddigits = 0;
$CFG->minpasswordlower = 0;
$CFG->minpasswordupper = 0;
$CFG->minpasswordnonalphanum = 0;
$CFG->maxconsecutiveidentchars = 0;
```

Password Tester
---------------
The password tester allows admins to enter a password. Upon clicking 'Test Password', the password will be validated against the above configuration, and the user will be alerted as to the status of the password. For the purposes of password lockout testing and information testing, the password will be checked against the current user account accessing the settings, typically the administration account, by default.
To test passwords against other user accounts, enter the username or email address of the account to test against in the box underneath the password entry box. The plugin will attempt to match this to a user account, and validate the password against that account. If no valid user account can be located, the password will be matched against the current logged in account, as if nothing were entered into the box.

Branches
--------
For all Moodle versions, use the master branch.

Installation
------------
**Requirements:** This plugin will work with any version of moodle from 3.6 onwards. It can be use with older installations of Moodle back to 3.1, they just require a cherrypick of commit: https://github.com/moodle/moodle/commit/99405aa7e2a34174a3eeaf9f9ffc9db3bc9f6192, which was integrated into Moodle core in version 3.6.

Note: Only PHP 7.0 or greater is supported by this plugin.

To install the plugin simply drop it into the ```/path/to/moodle/admin/tool/passwordvalidator``` directory. When moodle is accessed it will prompt for installation of the plugin. Press upgrade database now, and the plugin will be installed.

When the plugin is first installed, controls will have default values that enforce strong passwords, however the plugin will not perform any validation. Enable the control 'Enable Plugin' to perform validation. It is recommended to review the default security control values before enabling the plugin. 

For more instructions on installation, visit [the Moodle Plugin Installation Guide](https://docs.moodle.org/37/en/Installing_plugins)

This plugin can be configured to have config settings forced as part of the global configuration. See the below section [Templates](#templates) on how to configure this.
This plugin relies on the moodle core security setting "Password Rotation Limit" This must be set to at least 1, so that moodle stores the time that a password was last changed.
If this setting is not enabled, the settings page for this plugin will alert you, and the time lockout functionality of the plugin will not work.

Templates
---------
This plugin comes with some templates, that enforce policies drawn from the particular cyber security standards. To use these forced configuration templates, users must include:

```php
require(__DIR__.'/admin/tool/passwordvalidator/config_policies/<TEMPLATE HERE>.php');
```

this code inside of the Moodle config.php file. This will include the template commands inside of Moodle's core configuration, and prevent
changes from being made to the configurations.


Testing
-------
All of the password validation functionality has accompanying unit tests, that validate that the program is operating correctly. These tests can be executed via PHPUnit from the Moodle installation if it is installed.

To test the functionality of the plugin itself, visit the admin settings menu at Site Administration->Plugins->Admin Tools->Password Validator. There is a section at the bottom of the settings page, [Password Tester](#password-tester), which allows an admin to test the settings that are currently applied.

Support
-------
For any issue with the plugin, please log the in the github repository here:

https://github.com/catalyst/moodle-tool_password/issues

Please note our time is limited, so if you need urgent support or want to
sponsor a new feature then please contact Catalyst IT Australia:

https://www.catalyst-au.net/contact-us

Thanks
------
Thanks to Josh Kaufman who performed some data formatting on [Google's Trillion Word Corpus](https://ai.googleblog.com/2006/08/all-our-n-gram-are-belong-to-you.html), who's dictionary lists were implemented in this plugin.

https://github.com/first20hours/google-10000-english

This plugin was developed by Catalyst IT Australia:

https://www.catalyst-au.net/

<a href="https://www.catalyst-au.net/"><img alt="Catalyst IT" src="https://cdn.rawgit.com/CatalystIT-AU/moodle-auth_saml2/master/pix/catalyst-logo.svg" width="400"></a>

