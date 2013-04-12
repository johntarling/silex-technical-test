# features/setup.feature
@setup
Feature: Setup
  As a website visitor
  I want to be able to run a setup process
  So that I can see the homepage and news articles

  Scenario: Visiting the homepage and there is no news table setup
    Given there is no "news" table
    When I go to "http://33.33.33.3"
    Then I should see "Setup"

  Scenario: Running the setup routine
    When I go to "http://33.33.33.3/setup"
    And I follow "Ok, add some news please »"
    Then I should see "Setup successfully completed"