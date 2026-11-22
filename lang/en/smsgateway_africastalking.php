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

/**
 * Language strings.
 *
 * @package    smsgateway_africastalking
 * @copyright  2026 DevLMS
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['at_apikey'] = 'API key';
$string['at_from'] = 'Sender ID or short code';
$string['at_from_help'] = 'Optional. A sender ID or short code registered with Africa\'s Talking. Leave empty to use the default.';
$string['at_information'] = 'Use the username and API key from your Africa\'s Talking dashboard. To test without sending real messages, use the username "sandbox" with a sandbox API key; messages then appear in the Africa\'s Talking simulator only.';
$string['at_username'] = 'Username';
$string['pluginname'] = 'Africa\'s Talking';
$string['privacy:metadata'] = 'The Africa\'s Talking SMS gateway stores no personal data itself. It sends the phone number and message text to Africa\'s Talking.';
$string['privacy:metadata:africastalking'] = 'Phone numbers and message text are sent to Africa\'s Talking to deliver SMS messages.';
$string['privacy:metadata:africastalking:message'] = 'The text of the message';
$string['privacy:metadata:africastalking:to'] = 'The recipient phone number';
