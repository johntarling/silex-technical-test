# features/article.feature
Feature: Article
  As a website visitor
  If I visit a news article URL
  I will see a news article

  Scenario: Viewing an article directly
  	Given there is an article with an id of 1
  	When I go to "http://33.33.33.3/1"
  	Then I should see an "article p.article-body" element