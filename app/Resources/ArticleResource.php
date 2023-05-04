<?php

namespace App\Resources;


use App\Collections\Collection;

class ArticleResource
{
    public static function make($article): array
    {
        return [
            'id' => $article->id,
            'title' => $article->title,
            'slug' => $article->slug,
            'content' => $article->content,
            'publish_date' => $article->publish_date
        ];
    }

    public static function collect(Collection $data)
    {
        $items = [];

        foreach($data as $item){
            $items[]=self::make($item);
        }

        return $items;
    }
}