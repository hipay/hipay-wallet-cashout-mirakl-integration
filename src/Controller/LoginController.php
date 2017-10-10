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
use HiPay\Wallet\Mirakl\Integration\Parameter\Accessor;
use Symfony\Component\Validator\Constraints as Assert;

class LoginController
{
    protected $formBuilder;
    protected $parameters;
    protected $twig;
    protected $urlGenerator;
    protected $sessionManager;
    protected $securityLastError;

    public function __construct(
        $formBuilder,
        Accessor $parameters,
        $twig,
        $urlGenerator,
        $sessionManager,
        $securityLastError
    ) {
        $this->formBuilder = $formBuilder;
        $this->parameters = $parameters;
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
        $this->sessionManager = $sessionManager;
        $this->securityLastError = $securityLastError;
    }

    public function indexAction(Request $request)
    {
        $form = $this->generateForm();

        $lastError = $this->securityLastError;

        return $this->twig->render(
            'pages/login.twig',
            array('form' => $form->createView(), 'error' => $lastError($request))
        );
    }


    private function generateForm()
    {
        $default = array(
            '_username' => '',
            '_password' => '',
        );

        $form = $this->formBuilder->createNamedBuilder(null, 'form', $default)
                                  ->add(
                                      '_username',
                                      'text',
                                      array(
                                          'constraints' => array(new Assert\NotBlank(), new Assert\Length(
                                              array('min' => 3)
                                          )),
                                          'attr' => array('class' => 'form-control', 'placeholder' => 'Your API Login')
                                      )
                                  )
                                  ->add(
                                      '_password',
                                      'password',
                                      array(
                                          'constraints' => new Assert\NotBlank(),
                                          'attr' => array('class' => 'form-control', 'placeholder' => 'You API Password')
                                      )
                                  )
                                  ->add(
                                      'send',
                                      'submit',
                                      array(
                                          'attr' => array('class' => 'btn btn-default')
                                      )
                                  )
                                  ->getForm();
        return $form;
    }
}