<?php

namespace App\Database;

use App\Collections\ArticleCollection;
use App\Services\ArticleContentParser;
use App\Services\ArticleFilter;

class ArticleData implements DataInterface
{
    public string $path = '../assets/articles.json';

    public ArticleCollection $data;

    public function __construct()
    {
        $this->data = new ArticleCollection(json_decode(file_get_contents($this->path)));
        $this->articleFilter = new ArticleFilter();
        $this->contentParser = new ArticleContentParser();
    }

    private function filter()
    {
        $this->data = $this->articleFilter->execute($this->data);
    }

    private function parse()
    {
        $this->data = $this->contentParser->parse($this->data);
    }

    public function get(): ArticleCollection
    {
        $this->filter();
        $this->parse();
        return $this->data;
    }

    public function find($id): ArticleCollection
    {
        $this->data = $this->data->find('id', $id);

        $this->filter();

        $this->parse();

        return $this->data;
    }
}