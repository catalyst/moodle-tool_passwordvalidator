<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

namespace tool_passwordvalidator\cache;

/**
 * Cache data source and lookup helper for the Have I Been Pwned (HIBP) API.
 *
 * It uses the k-anonymity model where only the first 5 characters of the SHA1 hash are sent to the API,
 * returning all matching suffixes and their breach counts.
 *
 * @package     tool_passwordvalidator
 * @author      Alexander Van der Bellen <alexandervanderbellen@catalyst-au.net>
 * @copyright   2026 Catalyst IT Australia Pty Ltd
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class haveibeenpwned implements \cache_data_source {
    /** @var string The HIBP range API endpoint. */
    private const API_URL = 'https://api.pwnedpasswords.com/range/';

    /** @var int Connection and response timeout in seconds. */
    private const TIMEOUT = 5;

    /** @var int The length of the SHA1 prefix used for k-anonymity lookups. */
    private const PREFIX_LENGTH = 5;

    /** @var self|null Singleton instance. */
    private static ?self $instance = null;

    /**
     * Check whether a password appears in the HIBP breach database.
     *
     * @param string $password The plaintext password to check.
     * @return bool|null True if breached, false if not, null if the API is unavailable.
     */
    public static function is_breached(string $password): ?bool {
        $hash = strtoupper(sha1($password));
        $prefix = substr($hash, 0, self::PREFIX_LENGTH);
        $suffix = substr($hash, self::PREFIX_LENGTH);

        $cache = \cache::make('tool_passwordvalidator', 'haveibeenpwned');
        $response = $cache->get($prefix);

        if ($response === false) {
            return null;
        }

        return stripos($response, $suffix) !== false;
    }

    /**
     * Returns an instance of this class for use with the cache.
     *
     * @param \cache_definition $definition The cache definition.
     * @return self
     */
    public static function get_instance_for_cache(\cache_definition $definition) {
        return self::$instance ??= new self();
    }

    /**
     * Fetch the HIBP API response for a single SHA1 prefix.
     * Returns the raw API response body on success, or false on failure.
     *
     * @param string $key The 5-character hex SHA1 prefix.
     * @return string|false The API response body, or false on failure.
     */
    public function load_for_cache($key) {
        global $CFG;
        require_once($CFG->libdir . '/filelib.php');
        return download_file_content(self::API_URL . $key, null, null, false, self::TIMEOUT, self::TIMEOUT);
    }

    /**
     * Fetch HIBP API responses for multiple SHA1 prefixes.
     *
     * @param array $keys An array of 5-character hex SHA1 prefixes.
     * @return array An associative array of key => response (or false on failure).
     */
    public function load_many_for_cache(array $keys): array {
        $results = [];
        foreach ($keys as $key) {
            $results[$key] = $this->load_for_cache($key);
        }
        return $results;
    }
}
