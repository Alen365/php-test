<?php

namespace App\Services;

use App\Collections\ArticleCollection;
use DOMDocument;
use DOMElement;

class ArticleContentParser
{
    public function parse(ArticleCollection $articles)
    {
        foreach ($articles as &$article) {

            // Parse the HTML using DOMDocument
            $dom = new DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML($article->content);

            // Initialize variables to track nested blocks
            $blocks = array();
            $currentBlock = null;
            $nestedBlockTypes = array();

            // Traverse the parsed DOM tree
            foreach ($dom->getElementsByTagName('body')->item(0)->childNodes as $node) {

                // Handle paragraph and header tags
                if ($node instanceof DOMElement && in_array($node->tagName, ['p','div','img','ul'])) {

                    // Add a new block for this element
                    $block = array(
                        'type' => $node->tagName,
                        'content' => $dom->saveHTML($node),
                    );

                    // If there are nested blocks, add this block to the current parent block
                    if (!empty($nestedBlockTypes)) {
                        $currentBlock['blocks'][] = $block;
                    }

                    // Otherwise, add this block to the top-level blocks array
                    else {
                        $blocks[] = $block;
                    }

                    // Set the current block to this block
                    $currentBlock = &$block;
                }

                // Handle other tags (such as images)
                else {

                    // If there are nested blocks, add this node to the current parent block as a raw string
                    if (!empty($nestedBlockTypes)) {
                        $currentBlock['blocks'][] = $dom->saveHTML($node);
                    }

                    // Otherwise, add this node to the top-level blocks array as a raw string
                    else {

                        $blocks[] = array(
                            'type' => 'raw',
                            'content' => $dom->saveHTML($node),
                        );
                    }
                }
            }

            // Add the resulting blocks to the article resource object
            $article->content = $blocks;
        }

        return $articles;
    }
}