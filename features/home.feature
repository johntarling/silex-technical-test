# features/home.feature
Feature: Home
  As a website visitor
  I want to visit the homepage
  So that I can see the latest 10 news articles

  Scenario: Visiting the homepage
    Given there are 10 or more articles in the database
    When I go to "http://33.33.33.3"
    Then I should see 10 "li .article" elements

  Scenario: Viewing a news article from the homepage
    When I go to "http://33.33.33.3"
    And I follow ".article:first-child a.btn-primary"
    Then I should see an "article p.article-body" element