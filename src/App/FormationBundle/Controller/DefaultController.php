<?php

namespace App\FormationBundle\Controller;

use App\FormationBundle\Entity\Media;
use App\FormationBundle\Entity\Vote;
use App\FormationBundle\Form\VoteType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        return $this->render('AppFormationBundle:Default:index.html.twig',
            [
                'name' => "Eleve",
                'days' => ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi'],
                'html' => "<b>ce texte n'est pas en gras!</b>",
            ]);
    }


    /**
     * @Route("/show-random", name="show_random")
     */
    public function showRandomMediaAction()
    {
        //$em = $this->getDoctrine()->getManager();
        //$media = $em->getRepository(Media::class)->getRandomMedia();
        $media = $this->getMediaManager()->getNextMedia();

        return $this->redirect($this->generateUrl('show_media', ['id' => $media->getId()]));
    }
    
    /**
     * @Route("/show/{id}", name="show_media")
     */
    public function showMediaAction($id)
    {
        //$em = $this->getDoctrine()->getManager();
        //$media = $em->getRepository(Media::class)->getHydratedMediaById($id);

        $media = $this->getMediaManager()->getMedia($id);
        
        if (null === $media){
            throw $this->createNotFoundException();
        }

        $vote = $this->getVoteManager()->getNewVote($media);

        if ($vote instanceof Vote) {
            $form = $this->createForm(VoteType::class, $vote);
        }

        return $this->render('@AppFormation/Default/show.html.twig', [
            'media' => $media,
            'form'  => isset($form) ? $form->createView() : null,
        ]);
    }

    /**
     * @Route("/tops", name="show_tops")
     */
    public function showTopsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tops = $em->getRepository(Media::class)->getTopsMedia();
        
        return $this->render('@AppFormation/Default/tops.html.twig', [
            'tops' => $tops,
        ]);
    }

    /**
     * @Route("/flops", name="show_flops")
     */
    public function showFlopsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $flops = $em->getRepository(Media::class)->getFlopsMedia();

        return $this->render('@AppFormation/Default/flops.html.twig', [
            'flops' => $flops,
        ]);
    }

    /**
     * @Route("/vote/{id}", name="vote_media")
     * @Method({"POST"})
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function voteMediaAction(Request $request, $id)
    {
        $media = $this->getMediaManager()->getMedia($id);

        if (null === $media){
            throw $this->createNotFoundException();
        }

        $vote = $this->getVoteManager()->getNewVote($media);
        $form = $this->createForm(VoteType::class, $vote);
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $this->getVoteManager()->saveVote($vote);
            $this->get('session')->getFlashBag()->add('success', 'Votre vote est enregistré');

            return $this->redirectToRoute('show_media', ['id' => $media->getId()]);
        }

        $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');

        return $this->forward('AppFormationBundle:Default:showMedia', ['id' => $media->getId()]);
    }

    public function getMediaManager()
    {
        return $this->get('app_formation.media_manager');
    }

    public function getVoteManager()
    {
        return $this->get('app_formation.vote_manager');
    }
}
