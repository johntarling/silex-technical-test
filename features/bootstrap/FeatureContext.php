<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Silex\Application;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends MinkContext
{

    /**
     * @var Silex\Application
     */
    private $app;

    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        //Bootstrap app so that we can use it for tests
        require __DIR__.'/../../src/bootstrap.php';
        $this->app = $app;
    }

    /**
     * @Given /^there are (\d+) or more articles in the database$/
     */
    public function thereAreOrMoreArticlesInTheDatabase($count)
    {
        $sql = "SELECT COUNT(id) as count FROM news";
        $dbcount = $this->app['db']->fetchAssoc($sql);
        assertGreaterThanOrEqual($count,$dbcount["count"]);
    }

    /**
     * @Given /^there is an article with an id of (\d+)$/
     */
    public function thereIsAnArticleWithAnIdOf($id)
    {
        $sql = "SELECT id FROM news Where id = ?";
        $articleFound = $this->app['db']->fetchAssoc($sql, array($id));
        assertNotNull($articleFound['id'], "No article with id:" . $id . " found");
    }
}