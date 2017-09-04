<?php

namespace HiPay\Wallet\Mirakl\Integration\Controller;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;

class SettingController
{
    protected $formBuilder;

    public function __construct($formBuilder, $twig)
    {
        $this->formBuilder = $formBuilder;
        $this->twig        = $twig;
    }

    public function indexAction()
    {
        $form = $this->generateForm();

        return $this->twig->render('pages/settings.twig', array('form' => $form->createView()));
    }

    public function reRunAction(Request $request)
    {
        $form = $this->generateForm();

        $form->handleRequest($request);

        $success = false;

        if ($form->isValid()) {
            $success = true;
            $data = $form->getData();
            foreach ($data["batch"] as $command) {
                shell_exec("php ../bin/console $command >/dev/null 2>&1 &");
            }
        }

        return $this->twig->render('pages/settings.twig', array('form' => $form->createView(), 'success' => $success));
    }

    private function generateForm()
    {

        $default = array(
            'batch' => array(),
            'send' => true
        );

        $form = $this->formBuilder->createBuilder('form', $default)
            ->add('batch', 'choice',
                  array(
                'choices' => array('vendor:process' => 'Création de compte Wallet', 'cashout:process' => 'Transfert et retrait'),
                'attr' => array('class' => 'form-control'),
                'multiple' => true,
                'label' => ''
                )
            )
            ->add('send', 'submit',
                  array(
                'attr' => array('class' => 'btn btn-default btn-lg btn-block'),
                'label' => 'Relancer'
            ))
            ->getForm();

        return $form;
    }
}