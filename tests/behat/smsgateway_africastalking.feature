@smsgateway @smsgateway_africastalking
Feature: Set up the Africa's Talking SMS gateway
  In order to send SMS to learners in Africa
  As an admin
  I need to add an Africa's Talking gateway

  @javascript
  Scenario: An admin creates the gateway and sees it listed
    Given I log in as "admin"
    And I navigate to "Plugins > SMS > Manage SMS gateways" in site administration
    And I follow "Create new SMS gateway"
    When I set the field "SMS gateway provider" to "Africa's Talking"
    And I set the following fields to these values:
      | Gateway name | Kenya campus |
      | Username     | sandbox      |
      | API key      | test-key     |
    And I press "Save changes"
    Then I should see "Kenya campus" in the "sms_gateways_table" "table"

  Scenario: A configured gateway is listed for admins
    Given the following "core_sms > sms_gateways" exist:
      | name         | classname                                | enabled | config                                                           |
      | Kenya campus | smsgateway_africastalking\gateway        | 1       | {"countrycode":"254","at_username":"sandbox","at_apikey":"test"} |
    When I log in as "admin"
    And I navigate to "Plugins > SMS > Manage SMS gateways" in site administration
    Then I should see "Kenya campus" in the "sms_gateways_table" "table"
