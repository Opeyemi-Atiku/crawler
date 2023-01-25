<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CrawlerController extends Controller
{   

    public $entry_point = "https://agencyanalytics.com/";
    public $domain_name = "agencyanalytics.com";
    public $numberOfPages = 6;

    public $pagesToCrawl = array();
    public $allPagesLinks = array();
    public $internalLinks = array();
    public $externalLinks = array();
    public $allPagesImages = array();
    public $pageLoadTime = array();
    public $pageWordCount = array();
    public $pageTitleLength = array();
    public $pageStatusCodes = array();


    public function select_pages($linklist) {

        for($i = 0; $i < $linklist->length; $i++) {

            //Limit to six pages
            if(count($this->pagesToCrawl) == $this->numberOfPages) {
                break;
            }
            $l =  $linklist[$i]->getAttribute("href");

            // Process all of the links we find.
            if (substr($l, 0, 1) == "/" && substr($l, 0, 2) != "//") {
                $l = parse_url($this->entry_point)["scheme"]."://".parse_url($this->entry_point)["host"].$l;
            } else if (substr($l, 0, 2) == "//") {
                $l = parse_url($this->entry_point)["scheme"].":".$l;
            } else if (substr($l, 0, 2) == "./") {
                $l = parse_url($this->entry_point)["scheme"]."://".parse_url($this->entry_point)["host"].dirname(parse_url($this->entry_point)["path"]).substr($l, 1);
            } else if (substr($l, 0, 1) == "#") {
                continue;
            } else if (substr($l, 0, 3) == "../") {
                $l = parse_url($this->entry_point)["scheme"]."://".parse_url($this->entry_point)["host"]."/".$l;
            } else if (substr($l, 0, 11) == "javascript:") {
                continue;
            } else if (substr($l, 0, 5) != "https" && substr($l, 0, 4) != "http") {
                $l = parse_url($this->entry_point)["scheme"]."://".parse_url($this->entry_point)["host"]."/".$l;
            }
            
            // If the link isn't already in our crawl array add it, otherwise ignore it.
            if(!in_array($l, $this->pagesToCrawl)) {
                $this->pagesToCrawl[] = $l;
            }

        }
    }


    public function get_page_data($url) {

        // The array that we pass to stream_context_create() to modify the User Agent.
        $options = array('http'=>array('method'=>"GET", 'headers'=>"User-Agent: YusufCrawler/0.1\n"));
        // Create the stream context.
        $context = stream_context_create($options);
        $doc = new \DOMDocument();
    
        $start = microtime(true);
    
        $getContent = file_get_contents($url, false, $context);
    
        $statusCode = explode(" ", $http_response_header[0])[1];
        
        $this->pageStatusCodes[] = $statusCode;
    
        $this->pageWordCount[] = str_word_count(strip_tags(strtolower($getContent)));
    
        $this->pageLoadTime[] = microtime(true)-$start;
    
        
        if(!empty(@$getContent)) {
            @$doc->loadHTML(@file_get_contents($url, false, $context));
        }
    
        $title = $doc->getElementsByTagName("title");

        // There should only be one <title> on each page, so the array should have only 1 element.
        $title = $title->item(0) === NULL? "" : $title->item(0)->nodeValue;
    
        $this->pageTitleLength[] = strlen($title);
        
      
    
        $pageLinks = $doc->getElementsByTagName("a");
        $pageImages = $doc->getElementsByTagName("img");
    
        foreach($pageLinks as $link) {
            $href = $link->getAttribute("href");
            $this->allPagesLinks[] = $href;
        }
    
        foreach($pageImages as $image) {
            $i = $image->getAttribute("data-src");
            $this->allPagesImages[] = $i;
        }
    
       
    
    
    
    }


    public function crawl_pages() {
        
        // The array that we pass to stream_context_create() to modify our User Agent.
        $options = array('http'=>array('method'=>"GET", 'headers'=>"User-Agent: howBot/0.1\n"));

        // Create the stream context.
        $context = stream_context_create($options);

        // Create a new instance of PHP's DOMDocument class.
        $doc = new \DOMDocument();

        // Use file_get_contents() to download the page, pass the output of file_get_contents()
        // to PHP's DOMDocument class.
        @$doc->loadHTML(@file_get_contents($this->entry_point, false, $context));

         
        // Create an array of all of the links to the pages to be selected for crawling.
        $linklist = $doc->getElementsByTagName("a");

        $this->select_pages($linklist);

        //Get page data
        foreach ($this->pagesToCrawl as $page) {
            $this->get_page_data($page);
        }

        //check if a link is an external or internal by checking for the domain name
        foreach($this->allPagesLinks as $link) {
            if(strpos($link, 'http') !== false && strpos($link, $this->domain_name) == false) {
                $this->externalLinks[] = $link;
            }
            else {
                $this->internalLinks[] = $link;
            }
        }

        

        //Average Load time
        $averageLoadTime = 0;
        $totalTime = 0;
        foreach($this->pageLoadTime as $loadTime) {
            $totalTime += $loadTime;
        }

        $averageLoadTime = $totalTime / 6;


        //Average word couunt
        $averageWordCount = 0;
        $totalWordCount = 0;

        foreach($this->pageWordCount as $wordCount) {
            $totalWordCount += $wordCount;
        }

        $averageWordCount = $totalWordCount / 6;

        //Average title length
        $averageTitleLength = 0;
        $totalTitleLength = 0;
        
        foreach($this->pageTitleLength as $titleLength) {
            $totalTitleLength += $titleLength;
        }

        $averageTitleLength = $totalTitleLength / 6;
        $allStatusCodes = $this->pageStatusCodes;
        $uniqueInternalLinks = count(array_unique($this->internalLinks));
        $uniqueExternalLinks = count(array_unique($this->externalLinks));
        
        return view('welcome', [
            'pages' => $this->pagesToCrawl,
            'pagesCrawled' => count($this->pagesToCrawl), 
            'uniqueImages' => count(array_unique($this->allPagesImages)),
            'uniqueExternalLinks' => $uniqueExternalLinks,
            'uniqueInternalLinks' => $uniqueInternalLinks,
            'averagePageLoadTime' => $averageLoadTime,
            'averageWordCount' => $averageWordCount,
            'averageTitleLength' => $averageTitleLength,
            'statusCodes' => $this->pageStatusCodes
        ]);
    }

    
}
