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

use core\http_client;
use core_sms\manager;
use core_sms\message;
use core_sms\message_status;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Sends SMS through Africa's Talking (POST /version1/messaging, form encoded, apikey header).
 *
 * The username "sandbox" sends to the sandbox host, where messages only reach the simulator.
 * A message counts as sent when the recipient's status in the reply is "Success".
 *
 * @package    smsgateway_africastalking
 * @copyright  2026 DevLMS
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class gateway extends \core_sms\gateway {
    /** @var string Live messaging endpoint. */
    const API = 'https://api.africastalking.com/version1/messaging';
    /** @var string Sandbox messaging endpoint. */
    const SANDBOX_API = 'https://api.sandbox.africastalking.com/version1/messaging';

    #[\Override]
    public function send(message $message): message {
        $username = (string)($this->config->at_username ?? '');
        $countrycode = (string)($this->config->countrycode ?? '');
        $form = [
            'username' => $username,
            'to' => manager::format_number(phonenumber: $message->recipientnumber, countrycode: $countrycode ?: null),
            'message' => $message->content,
        ];
        $from = trim((string)($this->config->at_from ?? ''));
        if ($from !== '' && $username !== 'sandbox') {
            $form['from'] = $from;
        }

        $status = message_status::GATEWAY_FAILED;
        try {
            $response = \core\di::get(http_client::class)->post($username === 'sandbox' ? self::SANDBOX_API : self::API, [
                'headers' => ['apikey' => (string)($this->config->at_apikey ?? ''), 'Accept' => 'application/json'],
                'form_params' => $form,
                'http_errors' => false,
            ]);
            if ($response->getStatusCode() < 300) {
                $data = json_decode((string)$response->getBody(), true);
                $recipient = $data['SMSMessageData']['Recipients'][0] ?? null;
                if (($recipient['status'] ?? '') === 'Success') {
                    $status = message_status::GATEWAY_SENT;
                }
            }
        } catch (GuzzleException $e) {
            $status = message_status::GATEWAY_FAILED;
        }
        return $message->with(status: $status);
    }

    #[\Override]
    public function get_send_priority(message $message): int {
        return 50;
    }
}
