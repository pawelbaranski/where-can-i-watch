<?php

namespace WhereCanIWatch\AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use WhereCanIWatch\AppBundle\Form\Type\BroadcastSearchType;

class SearchController extends Controller
{
    /**
     * @Route("/search", name="search_broadcasts")
     * @Method({"POST", "GET"})
     */
    public function searchAction(Request $request)
    {
        $form = $this->createForm(BroadcastSearchType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $query = $form->get('query')->getData();

            $broadcasts = $this->get('app.broadcast_repository')->findNotFinished($query, new \DateTime());
        } else {
            $broadcasts = [];
        }

        return $this->render('search/search.html.twig', [
            'form' => $form->createView(),
            'broadcasts' => $broadcasts
        ]);
    }
}