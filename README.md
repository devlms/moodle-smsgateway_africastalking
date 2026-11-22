# Africa's Talking SMS gateway (smsgateway_africastalking)

Send Moodle's SMS messages (multi-factor authentication codes and any plugin that uses Moodle's SMS API) through Africa's Talking, which delivers SMS in Kenya, Nigeria, Uganda, Tanzania, Rwanda, Malawi and other African countries.

- Site administration > Plugins > SMS > Manage SMS gateways > Create new SMS gateway > Africa's Talking.
- Enter your Africa's Talking username and API key, and optionally a registered sender ID or short code.
- Set Moodle's country code (for example 254 for Kenya) so numbers stored without + are sent correctly.
- To test without real messages, use the username `sandbox` with a sandbox API key: messages go to the Africa's Talking simulator only.
- A message counts as sent when Africa's Talking answers that recipient with status "Success"; anything else is marked failed in Moodle.

Privacy: the plugin stores nothing itself. The phone number and message text are sent to Africa's Talking (declared in the privacy API).

Moodle 4.5 to 5.3. Built by DevLMS: https://devlms.com
