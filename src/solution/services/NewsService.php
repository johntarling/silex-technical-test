<?php 

namespace Solution\Services;

use Doctrine\DBAL\Connection;

class NewsService
{


/**
 * @var Doctrine\DBAL\Connection
 */
private $db;

    /**
    * @param Doctrine\DBAL\Connection $db 
    */
	public function __construct(Connection $db)
	{
		$this->db = $db;
	}

	/**
	* @param int $count Number of most recent articles to select
	*/
	public function getLatestNewsArticles($count)
	{
		//Treat the higher rowid as newest article for now as we don't have a date
		$sql = "SELECT * FROM news order by rowid desc LIMIT :count";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue("count", $count);
		$stmt->execute();
		$articles = $stmt->fetchAll();
		return $articles;
	}

	/**
	* @param int $id Article id to find
	*/
	public function getArticleById($id)
	{
		$sql = "SELECT * FROM news where id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue("id", $id);
		$stmt->execute();
		$article = $stmt->fetch();
		return $article;
	}

}