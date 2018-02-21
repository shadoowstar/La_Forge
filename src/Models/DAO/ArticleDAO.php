<?php

namespace Models\DAO;

use \PDO;
use Models\Domain\Article;
use Doctrine\DBAL\Connection;

class ArticleDAO
{
    protected $_db;

    public function __construct($db)
    {
        $this->setDb($db);
    }

    private function setDb(Connection $newDb){
        $this->_db = $newDb;
    }

    private function shorten_text($text, $max_length = 120, $cut_off = '...', $keep_word = false)
    {
        if(strlen($text) <= $max_length) {
            return $text;
        }

        if(strlen($text) > $max_length) {
            if($keep_word) {
                $text = substr($text, 0, $max_length + 1);

                if($last_space = strrpos($text, ' '))
                {
                    $text = substr($text, 0, $last_space);
                    $text = rtrim($text);
                    $text .=  $cut_off;
                }
            }
            else
            {
                $text = substr($text, 0, $max_length);
                $text = rtrim($text);
                $text .=  $cut_off;
            }
        }
        return $text;

    }

    public function findArticleByLimit(int $limit)
    {
        $response = $this->_db->prepare('SELECT * FROM articles ORDER BY id DESC LIMIT :limit');
        $response->bindvalue('limit', $limit, PDO::PARAM_INT);
        $response->execute();
        $articles = $response->fetchAll();

        $articlelist = array();
        if(!empty($articles))
        {
            foreach ($articles as $row)
            {
                $article = new Article();
                $article->setId($row['id']);
                $article->setTitle($row['title']);
                $article->setContent($this->shorten_text($row['content']));
                $article->setImgUrl($row['img_url']);
                //$article->setPostDate($row['post_date']);

                $articlelist[] = $article;
            }
        }

        return $articlelist;
    }

    public function getArticleById($id)
    {
        $response = $this->_db->prepare('SELECT * FROM articles WHERE id=:id');
        $response->bindvalue('id', $id, PDO::PARAM_INT);
        $response->execute();
        $article_db = $response->fetch();

        if(empty($article_db))
        {
            return false;
        }

        $article = new Article();
        $article->setId($article_db['id']);
        $article->setTitle($article_db['title']);
        $article->setContent($article_db['content']);
        $article->setImgUrl($article_db['img_url']);
        //$article->setPostDate($row['post_date']);

        return $article;
    }

    public function getArticlesByName($name)
    {
        $response = $this->_db->prepare('SELECT * FROM articles WHERE title LIKE :title ORDER BY id DESC');
        $response->bindvalue('title', '%'.$name.'%');
        $response->execute();
        $article_db = $response->fetchAll();

        if(empty($article_db))
        {
            return false;
        }
        $article_list = array();
        foreach($article_db as $row)
        {
            $article = new Article();
            $article->setId($row['id']);
            $article->setTitle($row['title']);
            $article->setContent($row['content']);
            $article->setImgUrl($row['img_url']);
            //$article->setPostDate($row['post_date']);
            $article_list[] = $article;

        }

        return $article_list;
    }
}
