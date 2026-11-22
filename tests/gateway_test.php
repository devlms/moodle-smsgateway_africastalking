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

use core_sms\message_status;
use GuzzleHttp\Psr7\Response;

/**
 * Tests for the Africa's Talking SMS gateway.
 *
 * @package    smsgateway_africastalking
 * @copyright  2026 DevLMS
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \smsgateway_africastalking\gateway
 */
final class gateway_test extends \advanced_testcase {
    /**
     * Create an enabled gateway with this config.
     *
     * @param array $config
     * @return \core_sms\manager
     */
    private function manager(array $config): \core_sms\manager {
        $manager = \core\di::get(\core_sms\manager::class);
        $manager->create_gateway_instance(
            classname: gateway::class,
            name: 'africastalking',
            enabled: true,
            config: (object)($config + ['at_username' => 'school', 'at_apikey' => 'k', 'at_from' => '', 'countrycode' => '254']),
        );
        return $manager;
    }

    /**
     * Send one message through the manager.
     *
     * @param \core_sms\manager $manager
     * @param string $number
     * @return \core_sms\message
     */
    private function send(\core_sms\manager $manager, string $number = '0712345678'): \core_sms\message {
        return $manager->send(
            recipientnumber: $number,
            content: 'Your exam is tomorrow at 9:00.',
            component: 'core',
            messagetype: 'test',
            recipientuserid: null,
            async: false
        );
    }

    /**
     * A JSON answer like the API's.
     *
     * @param string $status recipient status
     * @param int $code recipient statusCode
     * @return Response
     */
    private function answer(string $status, int $code): Response {
        return new Response(201, ['Content-Type' => 'application/json'], json_encode(['SMSMessageData' => [
            'Message' => 'Sent to 1/1',
            'Recipients' => [['number' => '+254712345678', 'status' => $status, 'statusCode' => $code,
                'messageId' => 'ATXid_1', 'cost' => 'KES 0.8000']],
        ]]));
    }

    public function test_success_and_the_request_sent(): void {
        $this->resetAfterTest();
        $history = [];
        ['mock' => $mock] = $this->get_mocked_http_client($history);
        $mock->append($this->answer('Success', 101));
        $message = $this->send($this->manager(['at_from' => 'SCHOOL']));
        $this->assertEquals(message_status::GATEWAY_SENT, $message->status);

        $request = $history[0]['request'];
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('https://api.africastalking.com/version1/messaging', (string)$request->getUri());
        $this->assertSame('k', $request->getHeaderLine('apikey'));
        parse_str((string)$request->getBody(), $form);
        $this->assertSame('school', $form['username']);
        $this->assertSame('+254712345678', $form['to'], 'Local number gets the country code');
        $this->assertSame('SCHOOL', $form['from']);
        $this->assertSame('Your exam is tomorrow at 9:00.', $form['message']);
    }

    public function test_refused_recipient_is_a_failure(): void {
        $this->resetAfterTest();
        ['mock' => $mock] = $this->get_mocked_http_client();
        $mock->append($this->answer('InvalidPhoneNumber', 403));
        $this->assertEquals(message_status::GATEWAY_FAILED, $this->send($this->manager([]))->status);
    }

    public function test_http_error_is_a_failure(): void {
        $this->resetAfterTest();
        ['mock' => $mock] = $this->get_mocked_http_client();
        $mock->append(new Response(401, [], 'The supplied authentication is invalid'));
        $this->assertEquals(message_status::GATEWAY_FAILED, $this->send($this->manager([]))->status);
    }

    public function test_sandbox_username_uses_the_sandbox_host_and_no_sender(): void {
        $this->resetAfterTest();
        $history = [];
        ['mock' => $mock] = $this->get_mocked_http_client($history);
        $mock->append($this->answer('Success', 101));
        $this->send($this->manager(['at_username' => 'sandbox']));
        $this->assertSame('https://api.sandbox.africastalking.com/version1/messaging', (string)$history[0]['request']->getUri());
        parse_str((string)$history[0]['request']->getBody(), $form);
        $this->assertArrayNotHasKey('from', $form);
    }
}
