<?php
/**
 * Created by PhpStorm.
 * User: mkaplenko
 * Date: 09.07.14
 * Time: 17:33
 */

namespace PTuristic\TestBundle\Form;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class ContactForm extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('first_name', 'text');
        $builder->add('last_name', 'text');
        $builder->add('company', 'text');
        $builder->add('email', 'email');
        $builder->add('message', 'textarea');
    }

    public function getName()
    {
        return 'Contact';
    }

    public function sendMail($args, $mailer, $logger)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Test email')
            ->setFrom('test@example.com')
            ->setTo($args['email'])
            ->setBody(
                "Hello, {$args['first_name']}! Your message is successfully sended to our server. Thanks!"
            );
        try
        {
            $mailer->send($message);
        }
        catch (Exception $e)
        {
            $logger->error($e->getMessage());
        }
    }
}