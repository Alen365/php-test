<?php

namespace App\Services;

use App\Collections\ArticleCollection;
use DOMDocument;

class ArticleFilter
{
    public function execute(ArticleCollection $data): ArticleCollection
    {

        /** @var ArticleCollection $filtereData */
        $filtereData = $data->filter(function ($article) {

            if ($article->content == null || $article->content == "") {
                return false;
            }
            if ($this->isHTMLWellFormed($article->content) == false) {
                return false;
            }
            if (!$article->id || !(int)$article->id) {
                return false;
            }
            if (!$article->title) {
                return false;
            }
            if (!$article->slug) {
                return false;
            }

            if (!$article->publish_date) {
                return false;
            }
            return true;
        });

        return $filtereData;
    }

    public function isHTMLWellFormed($html): bool
    {

        $doc = new DOMDocument();

        libxml_use_internal_errors(true);
        $doc->loadHTML($html);

        $errors = libxml_get_errors();
        libxml_clear_errors();
        foreach ($errors as $error) {
            if ($error->level === LIBXML_ERR_FATAL || $error->level === LIBXML_ERR_ERROR) {
                return false;
            }
        }

        return empty($errors);
    }
}