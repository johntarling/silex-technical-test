<?php 

namespace Solution\Services;

use Doctrine\ORM\EntityManager;
use Solution\Entities\NewsArticle;

class NewsService
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
	* @param int $count Number of most recent articles to select
	*/
	public function getLatestNewsArticles($count)
	{
		$query = $this->em->createQuery('SELECT a FROM Solution\Entities\NewsArticle a ORDER BY a.id');
		$query->setMaxResults();
		$articles = $query->getResult();
		return $articles;
	}

	/**
	* @param int $id Article id to find
	*/
	public function getArticleById($id)
	{
		$query = $this->em->createQuery('SELECT a FROM Solution\Entities\NewsArticle a WHERE a.id = :id');
		$query->setParameter('id', $id);
		$article = $query->getResult();
		return $article;
	}

}