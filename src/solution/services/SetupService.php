<?php 

namespace Solution\Services;
use Doctrine\ORM\EntityManager;
use Solution\Entities\NewsArticle;
use Doctrine\DBAL\DBALException;

class SetupService
{

/**
 * @var Doctrine\DBAL\Connection
 */
private $em;

    /**
    * @param Doctrine\ORM\EntityManager; $em 
    */
	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}

	/**
	* Check news table exists
	**/
	public function checkExists($tableName) {
		$sql = "SELECT count(name) as count FROM sqlite_master WHERE type='table' AND name = :tableName";
		$stmt = $this->em->getConnection()->prepare($sql);
		$stmt->bindValue("tableName", $tableName);
        $tableExists['count'] = $stmt->fetch();
        if ($tableExists) {
        	return 'Y';
        } else {
        	return 'N';
        };
	}

	/**
	* Create news database table
	**/
	public function createNewsTable() {
		try {
			$sql = "CREATE TABLE IF NOT EXISTS news (id INTEGER NOT NULL, title CLOB NOT NULL, short_desc CLOB NOT NULL, body CLOB NOT NULL, posted DATETIME NOT NULL, listing_image_url VARCHAR(255) NOT NULL, PRIMARY KEY(id))";
			$this->em->getConnection()->exec($sql);
			return "success";
		} catch (Exception $e) {
			throw new DBALException("Unable to create the news table : " . $e.getMessage());
		}
	}  

	/**
	* Update news schema from v1.0 to v1.1
	*/
	public function updateNewsSchema() {
		try {
			//Add posted date
			$sql = "ALTER TABLE news ADD COLUMN posted DATETIME NULL";
			$this->em->getConnection()->exec($sql);
			
			//Set previous posted dates to today
			$fakePosted = strtotime('now');
			$sql = "UPDATE news SET posted = :fakePosted where posted IS NULL";
			$stmt = $this->em->getConnection()->prepare($sql);
			$stmt->bindValue("fakePosted", $fakePosted);
			$stmt->execute();

			//A
			$sql = "ALTER TABLE news ADD COLUMN listing_image_url VARCHAR(255) NOT NULL DEFAULT '/img/news-img.png'";
			$this->em->getConnection()->exec($sql);
			return "success";
		} catch(Exception $e) {
			throw new DBALException("There was a problem updating your news table to version 1.1 : " . $e.getMessage());
		}

	}

	/**
	* Drop the news table for a fresh setup
	*/ 
	public function dropNewsTable() {
		try {
			$sql = "DROP TABLE IF EXISTS news";
			$this->em->getConnection()->exec($sql);
			return "success";
		} catch(Exception $e) {
			throw new DBALException("Unable to drop news table : " . $e.getMessage());
		}

	}

	/**
	* Populate news table with test news articles
	* @param int noArticles - Number of news articles to create
	*/
	public function createDummyNewsArticles($noArticles) {
		try {
			for ($count = 0; $count < $noArticles; $count++) {
				$news = new NewsArticle();
				$news->setTitle("A Test news article " . ($count + 1));
				$news->setShortDesc("This is my test articles short description " . ($count + 1));
				$news->setBody("Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
								Ut vel purus vestibulum est ullamcorper tristique eget vel turpis. 
								Nam placerat, diam vitae interdum euismod, velit mauris hendrerit leo, in rhoncus 
								libero arcu sit amet urna. Pellentesque in massa in orci faucibus accumsan ac 
								mollis sem. Nunc eget odio massa. Sed commodo elementum quam, vitae pulvinar mi tincidunt quis. 
								Ut at nulla eu arcu dignissim lobortis. Proin imperdiet justo vitae augue varius sed rutrum 
								urna blandit. Nunc convallis iaculis eros, eu suscipit ligula malesuada a. Proin pharetra 
								euismod massa, vel hendrerit purus mollis ac. Cum sociis natoque penatibus et magnis dis 
								parturient montes, nascetur ridiculus mus. Sed vestibulum egestas tristique. Integer felis 
								enim, pulvinar in adipiscing sed, interdum nec metus. Curabitur condimentum lacinia augue 
								vitae hendrerit. Proin ornare hendrerit tincidunt. Fusce mollis placerat ante in dictum.

								Cras aliquam nulla id lorem condimentum at dapibus urna pellentesque. Praesent nec ante enim. 
								Cras egestas, lorem sed egestas porta, orci leo mollis turpis, eu fringilla arcu quam sit 
								amet massa. Maecenas ut neque lectus, et eleifend turpis. Maecenas lacinia viverra malesuada. 
								Proin pretium tortor quis sem gravida porttitor. Proin ut nisi mauris, nec mollis arcu. Donec 
								eget urna non neque cursus lacinia. Vivamus iaculis vestibulum justo, eget euismod tellus 
								fringilla et. Maecenas dictum erat ut risus volutpat egestas. Suspendisse augue tortor, varius 
								in pulvinar eget, mollis eget mauris. Cras a diam arcu. Nam condimentum rhoncus sem in fringilla.");
				$postdate = strtotime("now - " . $count . " days");
				$news->setPosted($postdate);
				$news->setListingImageUrl("/img/news-image.png");
				$this->em->persist($news);
				$this->em->flush();

			}
			return "success";
		} catch(Exception $e) {
			throw new DBALException("There was an error while adding dummy news articles : " . $e.getMessage());
		}
	}

} 