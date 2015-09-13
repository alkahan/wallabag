<?php

namespace Wallabag\CoreBundle\Helper;

use Wallabag\CoreBundle\Entity\Entry;
use Wallabag\CoreBundle\Entity\Tag;
use PHPePub\Core\EPub;
use PHPePub\Core\EPubChapterSplitter;
use PHPePub\Core\Structure\OPF\DublinCore;
use PHPePub\Core\Logger;
use PHPZip\Zip\File\Zip;

class Ebook
{
	private $format;
	private $title;
	private $entries;
	private $authors = array("wallabag");
	private $tags;
	private $generatedDate;
	private $method;

	public function __construct($entries, $format = "epub", $method) 
	{
		$this->entries = $entries;
		$this->format = $format;
		$this->method = $method;
		$this->generatedDate = new \DateTime('now');
		foreach ($entries as $entry) {
			//$this->authors[] = $entry->author;
			$this->tags[] = $entry->getTags();
		}

		switch ($this->method) {
			case 'all':
				$this->title = "All Articles";
				break;
			case 'unread':
				$this->title = "Unread articles";
				break;
			case 'starred':
				$this->title = "Starred articles";
				break;
			case 'archived':
				$this->title = "Archived articles";
				break;
			case 'entry':
				$this->title = $this->entries[0]->getTitle();
				break;
	
			default:
				# code...
				break;
		}

		switch ($this->format) {
			case 'epub':
        		try {

		        $content_start =
		            "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
		            . "<html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:epub=\"http://www.idpf.org/2007/ops\">\n"
		            . "<head>"
		            . "<meta http-equiv=\"Default-Style\" content=\"text/html; charset=utf-8\" />\n"
		            . "<title>" . _("wallabag articles book") . "</title>\n"
		            . "</head>\n"
		            . "<body>\n";

		        $bookEnd = "</body>\n</html>\n";

		        $log = new Logger("wallabag", TRUE);

		        $book = new EPub(EPub::BOOK_VERSION_EPUB3);
		        $log->logLine("new EPub()");
		        $log->logLine("EPub class version: " . EPub::VERSION);
		        //$log->logLine("EPub Req. Zip version: " . EPub::REQ_ZIP_VERSION);
		        $log->logLine("Zip version: " . Zip::VERSION);
		        $log->logLine("getCurrentServerURL: " . $book->getCurrentServerURL());
		        $log->logLine("getCurrentPageURL..: " . $book->getCurrentPageURL());

		        $book->setTitle($this->title);
		        $book->setIdentifier("http://$_SERVER[HTTP_HOST]", EPub::IDENTIFIER_URI); // Could also be the ISBN number, prefered for published books, or a UUID.
		        //$book->setLanguage("en"); // Not needed, but included for the example, Language is mandatory, but EPub defaults to "en". Use RFC3066 Language codes, such as "en", "da", "fr" etc.
		        $book->setDescription(_("Some articles saved on my wallabag"));
		        foreach ($this->authors as $author) {
		        	$book->setAuthor($author,$author);
		        }
		        $book->setPublisher("wallabag", "wallabag"); // I hope this is a non existant address :)
		        $book->setDate(time()); // Strictly not needed as the book date defaults to time().
		        //$book->setRights("Copyright and licence information specific for the book."); // As this is generated, this _could_ contain the name or licence information of the user who purchased the book, if needed. If this is used that way, the identifier must also be made unique for the book.
		        $book->setSourceURL("http://$_SERVER[HTTP_HOST]");

		        $book->addDublinCoreMetadata(DublinCore::CONTRIBUTOR, "PHP");
		        $book->addDublinCoreMetadata(DublinCore::CONTRIBUTOR, "wallabag");
			
		        $log->logLine("Add Cover");

		        $fullTitle = "<h1> " . $this->title . "</h1>\n";

		        $book->setCoverImage("Cover.png", file_get_contents("themes/_global/img/appicon/apple-touch-icon-152.png"), "image/png", $fullTitle);

		        $cover = $content_start . '<div style="text-align:center;"><p>' . _('Produced by wallabag with PHPePub') . '</p><p>'. _('Please open <a href="https://github.com/wallabag/wallabag/issues" >an issue</a> if you have trouble with the display of this E-Book on your device.') . '</p></div>' . $bookEnd;

		        //$book->addChapter("Table of Contents", "TOC.xhtml", NULL, false, EPub::EXTERNAL_REF_IGNORE);
		        $book->addChapter("Notices", "Cover2.html", $cover);

		        $book->buildTOC();

		        foreach ($this->entries as $entry) { //set tags as subjects
		            foreach ($this->tags as $tag) {
		                $book->setSubject($tag['value']);
		            }

		            $log->logLine("Set up parameters");

		            $chapter = $content_start . $entry->getContent() . $bookEnd;
		            $book->addChapter($entry->getTitle(), htmlspecialchars($entry->getTitle()) . ".html", $chapter, true, EPub::EXTERNAL_REF_ADD);
		            $log->logLine("Added chapter " . $entry->getTitle());
		        }

		        /*if (DEBUG_POCHE) {
		            $book->addChapter("Log", "Log.html", $content_start . $log->getLog() . "\n</pre>" . $bookEnd); // log generation
		        }*/
		        $book->finalize();
		        $zipData = $book->sendBook($this->title);
		    	}
		        catch (Exception $e) {

		        }
				break;
			case 'mobi':

				break;
			case 'pdf':

				break;			
			default:
				# code...
				break;
		}

	}
	
}