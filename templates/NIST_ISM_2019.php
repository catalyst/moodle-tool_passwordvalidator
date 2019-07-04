<?php
// Combination of the strongest standards from NIST and the Australian ISM
// File used to force configurations for the plugin

set_config('irap_complexity', 1, 'tool_password');
set_config('simple_length_input', 13, 'tool_password');
set_config('complex_length_input', 10, 'tool_password');
set_config('irap_numbers', 1, 'tool_password');
set_config('sequential_digits_input', 2, 'tool_password');
set_config('repeated_chars_input', 2, 'tool_password');
set_config('personal_info', 1, 'tool_password');
set_config('phrase_blacklist', 1, 'tool_password');

// Phrases should be added 'phrase1<br>phrase2<br>phrase3;
set_config('phrase_blacklist_input', 'moodle', 'tool_password');

set_config('time_lockout_input', 86400, 'tool_password');
set_config('password_blacklist', 1, 'tool_password');