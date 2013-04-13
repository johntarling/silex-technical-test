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
     * @BeforeScenario @newssetup
     */
    public function dropNewsTable()
    {
        $sql = "DROP TABLE IF EXISTS news";
        $this->app['db']->query($sql);
    }

    /**
     * @BeforeScenario @update
     */
    public function revertNewsTableSchema()
    {
        //Drop current news table
        $this->dropNewsTable();
        //Create based on old schema
        $sql = "CREATE TABLE IF NOT EXISTS news (
                id  INT NOT NULL,
                title TEXT,
                short_desc TEXT,
                body TEXT,
                PRIMARY KEY(id)
                )";
        $this->app['db']->query($sql);
        //Populate it with original test entities
        for($i=1; $i<11; $i++) {
            $sql = "INSERT INTO news (id, title, short_desc, body) VALUES 
                ($i, 'Test $i', 'Test $i', 'Test $i')";
            $this->app['db']->query($sql);
        }

    }

    /**
     * @Given /^there are (\d+) or more articles in the database$/
     */
    public function thereAreOrMoreArticlesInTheDatabase($count)
    {
        $sql = "SELECT COUNT(id) as count FROM news";
        $dbCount = $this->app['db']->fetchAssoc($sql);
        assertGreaterThanOrEqual($count,$dbCount["count"]);
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

    /**
     * @Given /^there is not an article with an id of (\d+)$/
     */
    public function thereIsNotAnArticleWithAnIdOf($id)
    {
        $sql = "SELECT id FROM news Where id = ?";
        $articleFound = $this->app['db']->fetchAssoc($sql, array($id));
        assertNull($articleFound['id'], "Cannot complete test as article with id " . $id . " exists");
    }

    /**
     * @Given /^there is no "([^"]*)" table$/
     */
    public function thereIsNoTable($tableName)
    {
        $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name = ?";
        $tableExists = $this->app['db']->fetchAssoc($sql, array($tableName));
        assertFalse($tableExists, $tableName . " exists");
    }

    /**
     * @Given /^the news table is not updated$/
     */
    public function theNewsTableIsNotUpdated()
    {
        $pass = FALSE;
        $sql = "SELECT posted FROM news";
        try {
            $this->app['db']->fetchAssoc($sql);
        } catch (Exception $e) {
            $pass = TRUE;
        } 
        assertTrue($pass);
    }

}