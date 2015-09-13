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
     * @Route("/all/ebook/{format}", name="ebook_all")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAllAction(Request $request, $format)
    {
    	$repository = $this->getDoctrine()->getRepository('WallabagCoreBundle:Entry');
    	$qb = $repository->getBuilderForAllByUser($this->getUser()->getId());
    	$entries = $qb->getQuery()->getResult();


        new Ebook($entries, $format, 'all');
    }

    /**
     * Gets unread entries for current user.
     *
     * @param Request $request
     *
     * @Route("/unread/ebook/{format}", name="ebook_unread")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getUnreadAction(Request $request, $format)
    {
    	$repository = $this->getDoctrine()->getRepository('WallabagCoreBundle:Entry');
    	$qb = $repository->getBuilderForUnreadByUser($this->getUser()->getId());
    	$entries = $qb->getQuery()->getResult();


        new Ebook($entries, $format, 'unread');
    }

    /**
     * Gets read entries for current user.
     *
     * @param Request $request
     *
     * @Route("/archive/ebook/{format}", name="ebook_archive")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getArchiveAction(Request $request, $format)
    {
    	$repository = $this->getDoctrine()->getRepository('WallabagCoreBundle:Entry');
    	$qb = $repository->getBuilderForArchiveByUser($this->getUser()->getId());
    	$entries = $qb->getQuery()->getResult();


        new Ebook($entries, $format, 'archive');
    }

    /**
     * Gets starred entries for current user.
     *
     * @param Request $request
     *
     * @Route("/starred/ebook/{format}", name="ebook_starred")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getstarredAction(Request $request, $format)
    {
    	$repository = $this->getDoctrine()->getRepository('WallabagCoreBundle:Entry');
    	$qb = $repository->getBuilderForStarredByUser($this->getUser()->getId());
    	$entries = $qb->getQuery()->getResult();


        new Ebook($entries, $format, 'starred');
    }

    /**
     * Gets one entry content
     *
     * @param Entry $entry
     *
     * @Route("/ebook/{id}/{format}", requirements={"id" = "\d+"}, name="ebook_entry")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getEntryAction(Entry $entry, $format)
    {
    	new Ebook(array($entry), $format, 'entry');
    }
}