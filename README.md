# moodle-tool_password

<h1> Password Policy Enforcer </h1>

<p1> A tool for enforcing various security standards and guidelines for passwords for Moodle. This plugin aims for compliance with
the Australian Information Security Manual (currently May 2019), and above that, the NIST standards from the document 800-63B.

Many of the controls are optional and user configurable, with the most safe values set by default, but allow for great customization
for any configuration, while enforcing safe, sensible guidelines for passwords. </p1>

<h2> Security Controls </h2>
<p1> <b>ISM Complexity Standards:</b> The Australian ISM recommends a minimum password length of 13 characters for passwords consisting of only letters,
both uppercase and lowercase. For passwords that contain at least 3 of the the following: lowercase letters, uppercase letters, numbers,
special characters, the minimum length must be 10 characters. Enable this control to enforce this minimum length policy.</p1>

<p1><b>Minimum Simple Complexity Length:</b> This box allows for input of an integer value to be the minimum number of characters allowed in simple passwords, consisting of
only letters, both uppercase and lowercase. The Australian ISM recommends this length be 13, which is the default value.</p1>

<p1><b>Minimum Complex Complexity Length:</b> This box allows for input of an integer value to be the minimum number of characters allowed in complex passwords, consisting
of a combination of lowercase letters, uppercase letters, numbers and/or special characters. The Australian USM recommend this length be 10, which is the default value.</p1>

<p1><b>Enforce Letters and Characters in Password:</b> The Australian ISM recommends that passwords are not allowed to be constructed of just
numbers and special characters, and must contain some letters. Enable this control to enforce that passwords are not constructed of only numeric characters or combinations
of both numbers and special characters, and must contain letters </p1>

<p1><b>Maximum Sequential Digits Input:</b> The Australian ISM recommends that passwords may not contain sequences of numbers, which may be a date or other significant number. This box allows for input of an integer value to be the maximum number of sequential digits enforced in the control above. This defaults to 2. It is recommended to not allow this control to be higher, as people may include dates in their password, e.g. DDM or YYY. Set this to 0 to disable this control. </p1>

<p1><b>Maximum Repeated Characters:</b>  </p1>

<p1><b>Maximum Repeated Characters Input:</b> This control stops users from constructing passwords that contain repeated series of the same character, such as 'TTTTTTTTTTTTT', which satisfies length requirements but is very easy to guess. This box allows for input of an integer value to be the maximum number of the same character sequentially inside the password.
This defaults to 2, which allows for words that contain double letters, but excludes longer series of characters. Set this to 0 to disable this control.</p1>

<p1><b>Personal Information Checker:</b> The Australian ISM recommends that passwords do not contain any identifying information about the user.
This control checks the password for any known information about the user: Firstname, Lastname, City, Username. If any of the supplied information
is found within a password, case insensitive, the password is rejected. Enable this control to check for personal information in the password. </p1>

<p1><b>Enforce Phrase Blacklisting:</b> This control is based on NIST recommendations that passwords not be able to allow the service name inside of passwords.
This control allows for admins to specify a list of bad words of phrases to disallow in passwords, such as the platform or service name.
Enable this control to blacklist phrases from passwords.</p1>

<p1><b>Phrase Blacklist Entry:</b> This box allows for the input of phrases to blacklist from passwords. The default phrase is moodle here. Additional phrases
should be entered on a new line each. Phrases are case-insensitive, e.g. moodle will match to MOOdle. Multiple word phrases should be broken to seperate lines. </p1>

<p1><b>Password Change Lockout Period: </b>  Enable control
to enforce a lockout period, during which users are unable to change their passwords.</p1>

<p1><b>Password Change Lockout Period Input: </b>The Australian ISM recommends that users are not able to change their passwords more often than once every 24 hours. This box allows users to specify a lockout period from the time the password was last changed. Entering a positive number in seconds will enforce a period of that long from the time of last change. Set this to 0 to disable this control. </p1>

<p1><b>Check Password Against Blacklist:</b> NIST recommends that passwords are checked against a blacklist of known bad passwords from data breaches. Enabling this control
checks the hash of the password against the HaveIBeenPwned breached passwords API, and disallows passwords that have been found in any of the
catalogued breaches </p1>

<h2>Moodle Configuration Checker</h2>
<p1>This will automatically check the moodle configuration for settings that are either relied on by the plugin, or conflict with the plugin. It checks the password policy enforced by Moodle, which should be disabled and the plugin used instead, as well as the configuration settings of the policy if the policy is enabled. It is not recommended to enforce a minimum number of specific types of characters, such as uppercase letters, lowercase letters, special characters, and non-alphanumberic characters. </p1>

<h2> Password Tester </h2>
<p1> The password tester allows admins to enter a password. Upon saving changes to the settings, the password will be validated against the above configuration, and the user will be alerted
as to the status of the password. For the purposes of password lockout testing and information testing, the password will be checked against the current user account accessing the settings,
typically the administration account. </p1>

<h2> Installation </h2>
<p1>To install the plugin simply drop it into the /path/to/moodle/admin/tool/password directory. When moodle is accessed it will prompt for installation of the plugin. Press upgrade database now,
and the plugin will be installed. </p1>
<p1>This plugin relies on the moodle core security setting "Password Rotation Limit" This must be set to at least 1, so that moodle stores the time that a password was last changed.
If this setting is not enabled, the settings page for this plugin will alert you, and the time lockout functionality of the plugin will not work. </p1>

<h2> Templates </h2>
<p1> This plugin allows for the use of templates, which force the configuration to match the specification inside of the template file. To enforce a template, edit the settings.php file. Edit the $forcedconfig variable to false:<br>
  
```php
$forcedconfig = true;
```
Then set the $template variable to be the name of the template, placed inside the /templates directory. <br>

```php
$template = 'NIST_ISM_2019.php';
```
Save the file. Then visit the plugin settings at Site Administration-> Plugins-> Admin tools-> Password Policy Validator, and the updated template settings will be loaded and applied.

<h2> Unit Testing </h2>
<p1> All of the password validation functionality has accompanying unit tests, that validate that the program is operating correctly. These tests can be executed via PHPUnit from the Moodle installation if it is installed.</p1>
