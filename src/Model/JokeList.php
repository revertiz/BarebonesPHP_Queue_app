<?php

namespace Code\Model;

class JokeList implements Pagination
{
    /* database connection */
    private $pdo;
    /* sort method */
    private $sort;
    /* search keywork if set */
    private $keyword;

    private $page;

    public function __construct(\PDO $pdo, string $sort = 'newest', string $keyword = '', $page = '1')
    {
        $this->pdo = $pdo;
        $this->sort = $sort;
        $this->keyword = $keyword;
        $this->page = $page;
    }

    public function sort($sort): self
    {
        return new self($this->pdo, $sort, $this->keyword, $this->page);
    }

    public function page($page): self
    {
        return new self($this->pdo, $this->sort, $this->keyword, $page);
    }

    public function search($keyword): self
    {

        return new self($this->pdo, $this->sort, $keyword, $this->page);
    }

    public function getKeyword(): string
    {
        return $this->keyword;
    }

    public function getSort(): string
    {
        return $this->sort;
    }

    public function getPage(): string
    {
        return $this->page;
    }

    public function delete($id): self
    {
        $stmt = $this->pdo->prepare('DELETE FROM joke WHERE id = :id');
        $stmt->execute(['id' => $id]);

        return $this;
    }

    public function getJokes(): array
    {
        $parameters = [];

        if ($this->sort == 'newest') {
            $order = ' ORDER BY id DESC';
        } else if ($this->sort == 'oldest') {
            $order = ' ORDER BY id ASC';
        } else {
            $order = '';
        }

        if ($this->keyword) {
            $where = ' WHERE text LIKE :text';
            $parameters['text'] = '%' . $this->keyword . '%';
        } else {
            $where = '';
        }

        $limit = 5;
        $offset = ' LIMIT ' . ($this->page - 1) * 5 . ' , ' . $limit;

        //zodziu cia if else su $page ,
        //limit yra 10 o offset yra $page-1 * limit
//        $count = $this->getTotalResults($where, $order, $offset);


        $stmt = $this->pdo->prepare('SELECT * FROM joke ' . $where . $order . $offset);
        $stmt->execute($parameters);

        return $stmt->fetchAll();
    }

    public function getRecordsPerPage(): int
    {
        return 5;
    }


    public function getTotalResults()
    {



        $parameters = [];
        if ($this->keyword) {
            $where = ' WHERE text LIKE :text';
            $parameters['text'] = '%' . $this->keyword . '%';
        } else {
            $where = '';
        }
//        return $this->pdo->query('SELECT COUNT(*) FROM joke')->fetchColumn();
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM joke ' . $where);
        $stmt->execute($parameters);

        return $stmt->fetchColumn();
    }
}

