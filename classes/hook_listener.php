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

namespace smsgateway_africastalking;

use core_sms\hook\after_sms_gateway_form_hook;

/**
 * Adds the Africa's Talking settings to the SMS gateway form.
 *
 * @package    smsgateway_africastalking
 * @copyright  2026 DevLMS
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class hook_listener {
    /**
     * The gateway's fields.
     *
     * @param after_sms_gateway_form_hook $hook
     */
    public static function set_form_definition(after_sms_gateway_form_hook $hook): void {
        if ($hook->plugin !== 'smsgateway_africastalking') {
            return;
        }
        $mform = $hook->mform;
        $mform->addElement('static', 'at_information', get_string('at_information', 'smsgateway_africastalking'));

        $mform->addElement('text', 'at_username', get_string('at_username', 'smsgateway_africastalking'), ['size' => 30]);
        $mform->setType('at_username', PARAM_ALPHANUMEXT);
        $mform->addRule('at_username', null, 'required');

        $mform->addElement('passwordunmask', 'at_apikey', get_string('at_apikey', 'smsgateway_africastalking'), ['size' => 40]);
        $mform->setType('at_apikey', PARAM_RAW_TRIMMED);
        $mform->addRule('at_apikey', null, 'required');

        $mform->addElement('text', 'at_from', get_string('at_from', 'smsgateway_africastalking'), ['size' => 20]);
        $mform->setType('at_from', PARAM_TEXT);
        $mform->addHelpButton('at_from', 'at_from', 'smsgateway_africastalking');
    }
}
