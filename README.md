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

<p1><b>Enforce Letters and Characters in Password:</b> The Australian ISM recommends that passwords are not allowed to be constructed of just
numbers, and must contain some letters and/or special characters. Enable this control to enforce that passwords are not constructed of only numeric characters </p1>

<p1><b>Maximum Sequential Digits:</b> The Australian ISM recommends that passwords may not contain sequences of numbers, which may be a date or other significant number.
Enable this control to enforce a maximum number of sequential numberic characters.

<p1><b>Maximum Sequential Digits Input:</b> This box allows for input of an integer value to be the maximum number of sequential digits enforced in the control
above. This defaults to 2. It is recommended to not allow this control to be higher, as people may include dates in their password, e.g. DDM or YYY. </p1>

<p1><b>Maximum Repeated Characters:</b> This control stops users from constructing passwords that contain repeated series of the same character, such as 
'TTTTTTTTTTTTT', which satisfies length requirements but is very easy to guess. Enable this control to limit the amount of repeated characters in a password. </p1>

<p1><b>Maximum Repeated Characters Input:</b> This box allows for input of an integer value to be the maximum number of the same character sequentially inside the password.
This defaults to 2, which allows for words that contain double letters, but excludes longer series of characters.</p1>

<p1><b>Personal Information Checker:</b> The Australian ISM recommends that passwords do not contain any identifying information about the user.
This control checks the password for any known information about the user: Firstname, Lastname, City, Username. If any of the supplied information
is found within a password, case insensitive, the password is rejected. Enable this control to check for personal information in the password. </p1>

<p1><b>Enforce Phrase Blacklisting:</b> This control is based on NIST recommendations that passwords not be able to allow the service name inside of passwords.
This control allows for admins to specify a list of bad words of phrases to disallow in passwords, such as the platform or service name.
Enable this control to blacklist phrases from passwords.</p1>

<p1><b>Phrase Blacklist Entry:</b> This box allows for the input of phrases to blacklist from passwords. The default phrase is moodle here. Additional phrases
should be entered on a new line each. Phrases are case-insensitive, e.g. moodle will match to MOOdle. Multiple word phrases should be broken to seperate lines. </p1>

<p1><b>Password Change Lockout Period: </b> The Australian ISM recommends that users are not able to change their passwords more often than once every 24 hours. Enable control
to enforce a lockout period, during which users are unable to change their passwords.</p1>

<p1><b>Password Change Lockout Period Input: </b> This box allows users to specify a lockout period from the time the password was last changed. Entering a positive number
in seconds will enforce a period of that long from the time of last change. Entering 0, or a negative number, defaults this period to 24 hours from the time of last change. </p1>

<p1><b>Check Password Against Blacklist:</b> NIST recommends that passwords are checked against a blacklist of known bad passwords from data breaches. Enabling this control
checks the hash of the password against the HaveIBeenPwned breached passwords API, and disallows passwords that have been found in any of the
catalogued breaches </p1>

<h2> Password Tester </h2>
<p1> The password tester allows admins to enter a password. Upon saving changes to the settings, the password will be validated against the above configuration, and the user will be alerted
as to the status of the password. For the purposes of password lockout testing and information testing, the password will be checked against the current user account accessing the settings,
typically the administration account. </p1>

<h2> Installation </h2>
<p1>To install the plugin simply drop it into the /path/to/moodle/admin/tool/password directory. When moodle is accessed it will prompt for installation of the plugin. Press upgrade database now,
and the plugin will be installed. </p1>
