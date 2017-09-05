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

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;

class SettingController
{
    protected $formBuilder;
    protected $twig;
    protected $translator;

    public function __construct($formBuilder, $twig, $translator)
    {
        $this->formBuilder = $formBuilder;
        $this->twig        = $twig;
        $this->translator        = $translator;
    }

    public function indexAction()
    {
        $form = $this->generateForm();

        $versions = $this->getVersions();

        return $this->twig->render('pages/settings.twig', array('form' => $form->createView(), 'version' => $versions));
    }

    public function reRunAction(Request $request)
    {
        $form = $this->generateForm();

        $form->handleRequest($request);

        $success = false;

        if ($form->isValid()) {
            $success = true;
            $data    = $form->getData();
            foreach ($data["batch"] as $command) {
                shell_exec("php ../bin/console $command >/dev/null 2>&1 &");
            }
        }

        return $this->twig->render('pages/settings.twig', array('form' => $form->createView(), 'success' => $success));
    }

    private function getVersions()
    {

        $integration = $this->getComposerFile(dirname(__FILE__).'/../../composer.json');

        $library = $this->getComposerFile(dirname(__FILE__).'/../../vendor/hipay/hipay-wallet-cashout-mirakl-library/composer.json');

        return array(
            "integration" => $integration['version'],
            "library" => $library['version']
        );
    }

    private function getComposerFile($path){

        $composer = array();

        if (file_exists($path)) {
            $contents = file_get_contents($path);
            $contents = utf8_encode($contents);

            $composer = json_decode($contents, true);
        }else{
            $composer["version"] = "N/A";
        }

        return $composer;
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
                'choices' => array(
                    'vendor:process' => $this->translator->trans('wallet.account.creation'),
                    'cashout:process' => $this->translator->trans('transfer.withdraw')
                    ),
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