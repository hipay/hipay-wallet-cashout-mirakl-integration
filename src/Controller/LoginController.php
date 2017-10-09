<?php
/**
 * 2017 HiPay
 *
 * NOTICE OF LICENSE
 *
 * @author    HiPay <support.wallet@hipay.com>
 * @copyright 2017 HiPay
 * @license   https://github.com/hipay/hipay-wallet-cashout-mirakl-integration/blob/master/LICENSE.md
 */

namespace HiPay\Wallet\Mirakl\Integration\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use HiPay\Wallet\Mirakl\Api\HiPay;
use HiPay\Wallet\Mirakl\Integration\Parameter\Accessor;
use Symfony\Component\Validator\Constraints as Assert;

class LoginController
{
    protected $formBuilder;
    protected $parameters;
    protected $twig;
    protected $urlGenerator;
    protected $sessionManager;

    public function __construct($formBuilder, Accessor $parameters, $twig, $urlGenerator, $sessionManager)
    {
        $this->formBuilder = $formBuilder;
        $this->parameters = $parameters;
        $this->twig        = $twig;
        $this->urlGenerator = $urlGenerator;
        $this->sessionManager = $sessionManager;
    }

    public function indexAction(Request $request)
    {
        $form = $this->generateForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            if ($data['ws_login'] === $this->parameters['hipay.wsLogin'] && $data['ws_password'] === $this->parameters['hipay.wsPassword']) {
                $this->sessionManager->set('user', array('username' => $this->parameters['hipay.wsLogin']));
                
                return new RedirectResponse($this->urlGenerator->generate("vendors"), 302);
            }

        }

        return $this->twig->render('pages/login.twig', array('form' => $form->createView()));
    }

    public function logOutAction(){
        $this->sessionManager->set('user', null);
        return new RedirectResponse($this->urlGenerator->generate("login"), 302);
    }

    private function generateForm()
    {
        $default = array(
            'ws_login' => '',
            'ws_password' => '',
        );

        $form = $this->formBuilder->createBuilder('form', $default)
            ->add(
                'ws_login', 'text',
                array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(
                        array('min' => 3)
                    )),
                'attr' => array('class' => 'form-control', 'placeholder' => 'Your API Login')
                )
            )
            ->add(
                'ws_password', 'password',
                array(
                'constraints' => new Assert\NotBlank(),
                'attr' => array('class' => 'form-control', 'placeholder' => 'You API Password')
                )
            )
            ->add(
                'send', 'submit', array(
                'attr' => array('class' => 'btn btn-default')
                )
            )
            ->getForm();
        return $form;
    }
}