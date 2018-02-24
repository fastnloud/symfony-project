<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use AppBundle\Form\ContactType;
use AppBundle\Service\SanityService;
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
        /** @var SanityService $sanityService */
        $sanityService = $this->container->get('sanity');
        $vars = $sanityService->fetchAll();

        if (isset($vars['Tag'])) {
            shuffle($vars['Tag']); // shuffle tags
        }

        // contact form
        $message = new Message();
        $form = $this->createForm(ContactType::class, $message, ['action' => $this->generateUrl('homepage') . '#form']);
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
     * @param Form $form
     * @param Request $request
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
