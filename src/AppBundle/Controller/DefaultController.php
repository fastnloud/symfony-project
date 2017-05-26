<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use AppBundle\Entity\ViewVariable;
use AppBundle\Repository\SocialMediaRepository;
use AppBundle\Repository\WorkExperienceRepository;
use AppBundle\Repository\EducationRepository;
use AppBundle\Repository\SkillRepository;
use AppBundle\Form\ContactType;
use ReCaptcha\ReCaptcha;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swift_Message;
use Symfony\Component\Form\Form;
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
        $vars = $this->getViewVariables();

        // contact form
        $message = new Message();
        $form = $this->createForm(ContactType::class, $message);
        $form->handleRequest($request);

        // validate form
        if ($this->validateForm($form, $request)) {
            // redirect on success!
            return $this->redirect($this->generateUrl('homepage', ['success' => true]));
        }

        $vars['form']               = $form->createView();
        $vars['recaptcha_site_key'] = $this->container->getParameter('recaptcha_site_key');
        $vars['ga_tracking_id']     = $this->container->getParameter('ga_tracking_id');

        return $this->render('default/index.html.twig', $vars);
    }

    /**
     * @return array
     */
    protected function getViewVariables()
    {
        $vars = [];

        // fetch global view variables
        $text = $this->getDoctrine()->getRepository('AppBundle:ViewVariable')->findAll();

        /* @var $obj ViewVariable */
        foreach ($text as $obj) {
            $vars[$obj->getName()] = $obj->getValue();
        }

        /** @var SocialMediaRepository $repository */
        $repository = $this->getDoctrine()->getRepository('AppBundle:SocialMedia');
        $vars['social_media_list'] = $repository->findAllOrderedByPriority();

        /** @var WorkExperienceRepository $repository */
        $repository = $this->getDoctrine()->getRepository('AppBundle:WorkExperience');
        $vars['work_experience_list'] = $repository->findAllOrderedByPriority();

        /** @var EducationRepository $repository */
        $repository = $this->getDoctrine()->getRepository('AppBundle:Education');
        $vars['education_list'] = $repository->findAllOrderedByPriority();

        /** @var SkillRepository $repository */
        $repository = $this->getDoctrine()->getRepository('AppBundle:Skill');
        $vars['skill_list'] = $repository->findAllOrderedByRand();

        return $vars;
    }

    /**
     * @param Form $form
     * @return bool
     */
    protected function validateForm(Form $form, Request $request)
    {
        $isValid            = false;
        $gRecaptchaResponse = $request->get('g-recaptcha-response');
        $remoteIp           = $request->getClientIp();

        // recaptcha validation
        $recaptcha = new ReCaptcha($this->container->getParameter('recaptcha_secret_key'));
        $resp      = $recaptcha->verify($gRecaptchaResponse, $remoteIp);

        if ($form->isSubmitted() && $form->isValid() && $resp->isSuccess()) {
            $data    = $form->getData();
            $isValid = 0 < $this->sendMessage($data) ? true : false;
        }

        return $isValid;
    }

    /**
     * @param Message $message
     * @return mixed
     */
    protected function sendMessage(Message $message)
    {
        $vars = [
            'email'   => $message->getEmail(),
            'content' => $message->getContent()
        ];

        $message = Swift_Message::newInstance()
                 ->setSubject($this->container->getParameter('email_subject'))
                 ->setFrom($this->container->getParameter('email_from'))
                 ->setTo($this->container->getParameter('email_to'))
                 ->setBody($this->renderView('mail/contact.html.twig', $vars), 'text/html');

        return $this->get('mailer')->send($message);
    }
}
