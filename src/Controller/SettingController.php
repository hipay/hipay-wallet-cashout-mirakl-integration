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
use Guzzle\Http\Client;

class SettingController
{
    protected $formBuilder;
    protected $twig;
    protected $translator;
    protected $parameters;
    protected $urlGenerator;

    /**
     *
     * @param type $formBuilder
     * @param \Twig_Environment $twig
     * @param type $translator
     * @param type $parameters
     * @param type $urlGenerator
     */
    public function __construct($formBuilder, \Twig_Environment $twig, $translator, $parameters, $urlGenerator)
    {
        $this->formBuilder = $formBuilder;
        $this->twig        = $twig;
        $this->translator  = $translator;
        $this->parameters  = $parameters;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Display Settings page
     * @return type
     */
    public function indexAction()
    {
        $form = $this->generateForm();

        $versions = $this->getVersions();

        $isWritable = $this->is_writable_r(__DIR__.'/../../');

        $updateLibrary = $this->updateAvailable(
            '/repos/hipay/hipay-wallet-cashout-mirakl-library/releases/latest',
            dirname(__FILE__).'/../../vendor/hipay/hipay-wallet-cashout-mirakl-library/composer.json'
        );

        $updateIntegration = $this->updateAvailable(
            '/repos/hipay/hipay-wallet-cashout-mirakl-integration/releases/latest',
            dirname(__FILE__).'/../../composer.json'
        );

        return $this->twig->render(
                'pages/settings.twig',
                array(
                'form' => $form->createView(),
                'version' => $versions,
                'isWritable' => $isWritable,
                'updateLibrary' => $updateLibrary,
                'updateIntegration' => $updateIntegration,
                'dbms' => $this->parameters['db.driver']
                )
        );
    }

    /**
     * Handle batch Form 
     * @param Request $request
     * @return type
     */
    public function reRunAction(Request $request)
    {
        $form = $this->generateForm();

        $versions = $this->getVersions();

        $isWritable = $this->is_writable_r(__DIR__.'/../../');

        $form->handleRequest($request);

        $updateLibrary = $this->updateAvailable(
            '/repos/hipay/hipay-wallet-cashout-mirakl-library/releases/latest',
            dirname(__FILE__).'/../../vendor/hipay/hipay-wallet-cashout-mirakl-library/composer.json'
        );

        $updateIntegration = $this->updateAvailable(
            '/repos/hipay/hipay-wallet-cashout-mirakl-integration/releases/latest',
            dirname(__FILE__).'/../../composer.json'
        );

        $success = false;

        if ($form->isValid()) {
            $success = true;
            $data    = $form->getData();
            foreach ($data["batch"] as $command) {
                shell_exec("php ../bin/console $command >/dev/null 2>&1 &");
            }
        }

        return $this->twig->render(
                'pages/settings.twig',
                array(
                'form' => $form->createView(),
                'success' => $success,
                'version' => $versions,
                'isWritable' => $isWritable,
                'updateLibrary' => $updateLibrary,
                'updateIntegration' => $updateIntegration
                )
        );
    }

    /**
     * Display update page
     * @param type $choice
     * @return type
     */
    public function updateAction($choice)
    {
        return $this->twig->render('pages/update.twig', array('choice' => $choice));
    }

    /**
     * Run Application update process
     * @return string|RedirectResponse
     */
    public function updateIntegrationAjaxAction()
    {

        try{
            system('cd '.__DIR__.'/../.. && php bin/console app:update 2>&1', $status);

        } catch (Exception $ex) {
            return '<div class="alert alert-dismissible alert-danger">Error</div>';
        }

        echo '<div class="alert alert-dismissible alert-success">Success</div>';

        return new RedirectResponse($this->urlGenerator->generate("settings"), 302);

    }

    /**
     * Run Library update process
     * @return string|RedirectResponse
     */
    public function updateLibraryAjaxAction()
    {

        echo '<div class="alert alert-dismissible alert-info">updating library, this may take a while </div>';

        try{
            putenv('COMPOSER_HOME='.__DIR__.'/../../vendor/bin/composer');
            system('cd '.__DIR__.'/../.. && composer update hipay/hipay-wallet-cashout-mirakl-library 2>&1', $status);
        } catch (Exception $ex) {
            return '<div class="alert alert-dismissible alert-danger">Error</div>';
        }
        echo '<div class="alert alert-dismissible alert-success">Success</div>';

        return new RedirectResponse($this->urlGenerator->generate("settings"), 302);
    }

    /**
     * Return Library and Application versions
     * @return type
     */
    private function getVersions()
    {

        $integration = $this->getComposerFile(dirname(__FILE__).'/../../composer.json');

        $library = $this->getComposerFile(
            dirname(__FILE__).'/../../vendor/hipay/hipay-wallet-cashout-mirakl-library/composer.json'
        );

        return array(
            "integration" => $integration['version'],
            "library" => $library['version']
        );
    }

    /**
     *
     * @param type $path
     * @return string
     */
    private function getComposerFile($path)
    {

        $composer = array();

        if (file_exists($path)) {
            $contents = file_get_contents($path);
            $contents = utf8_encode($contents);

            $composer = json_decode($contents, true);
        } else {
            $composer["version"] = "N/A";
        }

        return $composer;
    }

    /**
     *
     * @return type
     */
    private function generateForm()
    {

        $default = array(
            'batch' => array(),
            'send' => true
        );

        $form = $this->formBuilder->createBuilder('form', $default)
            ->add(
                'batch', 'choice',
                array(
                'choices' => array(
                    'vendor:process' => $this->translator->trans('wallet.account.creation'),
                    'cashout:generate' => $this->translator->trans('generate.operation'),
                    'cashout:transfer' => $this->translator->trans('transfer'),
                    'cashout:withdraw' => $this->translator->trans('withdraw')
                ),
                'attr' => array('class' => 'form-control'),
                'multiple' => true,
                'label' => ''
                )
            )
            ->add(
                'send', 'submit',
                array(
                'attr' => array('class' => 'btn btn-default btn-lg btn-block'),
                'label' => $this->translator->trans('rerun')
                )
            )
            ->getForm();

        return $form;
    }

    /**
     * Checks if folder is writable recursively
     * @param type $dir
     * @return boolean
     */
    private function is_writable_r($dir)
    {
        if (is_dir($dir)) {
            if (is_writable($dir)) {
                $objects = scandir($dir);
                foreach ($objects as $object) {
                    if ($object != "." && $object != "..") {
                        if (!$this->is_writable_r($dir."/".$object)) {
                            return false;
                        } else continue;
                    }
                }
                return true;
            }else {
                return false;
            }
        } else if (file_exists($dir)) {
            return (is_writable($dir));
        }
    }

    /**
     * Check if update available for package
     * @param type $url
     * @param type $composerPath
     * @return boolean
     */
    private function updateAvailable($url, $composerPath)
    {
        $client = new Client('https://api.github.com');

        $request = $client->get($url);

        $response = $request->send();

        $latestVersion = $response->json();

        $installedVersion = $this->getComposerFile($composerPath);

        if ($latestVersion['tag_name'] !== $installedVersion['version']) {
            return true;
        }

        return false;
    }
}