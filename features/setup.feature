# features/setup.feature
@setup
Feature: Setup
  As a website visitor
  I want to be able to run a setup process
  So that I can see the homepage and news articles

  @newssetup
  Scenario: Visiting the homepage and there is no news table setup
    Given there is no "news" table
    When I go to "http://33.33.33.3"
    Then I should see "Setup"

  @newssetup
  Scenario: Running the setup routine
    Given there is no "news" table
    When I go to "http://33.33.33.3/setup"
    And I follow "Ok, add some news please »"
    Then I should see "Setup successfully completed"

  @update
  Scenario: Visiting the homepage and news table has not been updated
    Given the news table is not updated
    When I go to "http://33.33.33.3"
    Then I should see "Update needed"

  @update
  Scenario: Updating the news table schema
    Given the news table is not updated
    When I go to "http://33.33.33.3/update"
    And I follow "Ok, update please »"
    Then I should see "The news table was updated succesfully."