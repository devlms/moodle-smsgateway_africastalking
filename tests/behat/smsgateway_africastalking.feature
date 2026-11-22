@smsgateway @smsgateway_africastalking @javascript
Feature: Set up the Africa's Talking SMS gateway
  In order to send SMS to learners in Africa
  As an admin
  I need to add an Africa's Talking gateway

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
