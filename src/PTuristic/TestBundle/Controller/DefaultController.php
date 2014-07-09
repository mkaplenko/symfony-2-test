<?php

namespace PTuristic\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use PTuristic\TestBundle\Form\ContactForm;
use Symfony\Component\Validator\Constraints\Email;


class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PTuristicTestBundle:Default:index.html.twig');
    }

    public function contactAction(Request $request)
    {
        $form_controller = new ContactForm();
        $form = $this->createForm($form_controller);
        $errors=[];

        if ($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            $form_data = $form->getData();
            $emailConstraint = new Email();
            $emailConstraint->message = 'Invalid email address';
            $email_error = $this->get('validator')->validateValue($form_data['email'], $emailConstraint);
            if(count($email_error)){
                $errors[] = $email_error[0]->getMessage();
            }
            if ($form->isValid() and !count($errors)){

                $form_controller->sendMail($form_data, $this->get('mailer'), $this->get('logger'));
                return $this->render('PTuristicTestBundle:Default:success_submit.html.twig');
            }
        }
        return $this->render('PTuristicTestBundle:Default:contact_form.html.twig', array('form' => $form->createView(), 'errors' => $errors));
    }
}
