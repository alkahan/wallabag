<?php

namespace Wallabag\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Wallabag\CoreBundle\Entity\Entry;
use Wallabag\CoreBundle\Helper\Ebook;

class EbookController extends Controller
{
	/**
     * Gets all entries for current user.
     *
     * @param Request $request
     *
     * @Route("/export/{category}.{_format}", name="ebook")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getEntriesAction(Request $request, $_format, $category)
    {
    	$repository = $this->getDoctrine()->getRepository('WallabagCoreBundle:Entry');
        switch ($category) {
            case 'all':
                $qb = $repository->getBuilderForAllByUser($this->getUser()->getId());
                $entries = $qb->getQuery()->getResult();
                new Ebook($entries, $_format, 'all');
                break;

            case 'unread':
                $repository = $this->getDoctrine()->getRepository('WallabagCoreBundle:Entry');
                $qb = $repository->getBuilderForUnreadByUser($this->getUser()->getId());
                $entries = $qb->getQuery()->getResult();
                new Ebook($entries, $_format, 'unread');
                break;

            case 'starred':
                $repository = $this->getDoctrine()->getRepository('WallabagCoreBundle:Entry');
                $qb = $repository->getBuilderForStarredByUser($this->getUser()->getId());
                $entries = $qb->getQuery()->getResult();
                new Ebook($entries, $_format, 'starred');
                break;

            case 'archive':
                $repository = $this->getDoctrine()->getRepository('WallabagCoreBundle:Entry');
                $qb = $repository->getBuilderForArchiveByUser($this->getUser()->getId());
                $entries = $qb->getQuery()->getResult();
                new Ebook($entries, $_format, 'archive');
                break;

            default:
                # code...
                break;
        }
    }

    /**
     * Gets one entry content
     *
     * @param Entry $entry
     *
     * @Route("/export/{category}/{id}.{_format}", requirements={"id" = "\d+"}, name="ebook_entry")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getEntryAction(Entry $entry, $_format, $category)
    {
    	new Ebook(array($entry), $_format, 'entry');
    }
}
