<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use AppBundle\Entity\ViewVariable;
use AppBundle\Repository\WorkExperienceRepository;
use AppBundle\Repository\EducationRepository;
use AppBundle\Repository\SkillRepository;
use AppBundle\Form\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $vars = [];

        // fetch global view variables
        $text = $this->getDoctrine()->getRepository('AppBundle:ViewVariable')->findAll();

        /* @var $obj ViewVariable */
        foreach ($text as $obj) {
            $vars[$obj->getName()] = $obj->getValue();
        }

        /** @var WorkExperienceRepository $repository */
        $repository = $this->getDoctrine()->getRepository('AppBundle:WorkExperience');
        $vars['work_experience_list'] = $repository->findAllOrderedByPriority();

        /** @var EducationRepository $repository */
        $repository = $this->getDoctrine()->getRepository('AppBundle:Education');
        $vars['education_list'] = $repository->findAllOrderedByPriority();

        /** @var SkillRepository $repository */
        $repository = $this->getDoctrine()->getRepository('AppBundle:Skill');
        $vars['skill_list'] = $repository->findAllOrderedByRand();

        $message = new Message();

        $form = $this->createForm(ContactType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->getData();

            dump($message);die;
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $em = $this->getDoctrine()->getManager();
            // $em->persist($task);
            // $em->flush();

            return $this->redirectToRoute('task_success');
        }

        $vars['form'] = $form->createView();

        return $this->render('default/index.html.twig', $vars);
    }
}
