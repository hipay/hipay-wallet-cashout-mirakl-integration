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
use Guzzle\Http\Exception\ClientErrorResponseException;

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
        $this->twig = $twig;
        $this->translator = $translator;
        $this->parameters = $parameters;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Display Settings page
     * @return type
     */
    public function indexAction()
    {

        $params = $this->getDefaultSettingsParams();

        return $this->twig->render(
            'pages/settings.twig',
            $params
        );
    }

    /**
     * Handle batch Form
     * @param Request $request
     * @return type
     */
    public function reRunAction(Request $request)
    {
        $reRunForm = $this->generateReRunForm();

        $settingsForm = $this->generateSettingsForm();

        $reRunForm->handleRequest($request);

        $settingsForm->handleRequest($request);

        $success = false;

        if ($reRunForm->isValid()) {
            $successRerun = true;
            $data = $reRunForm->getData();
            foreach ($data["batch"] as $command) {

                $date = \DateTime::createFromFormat("d/m/Y", $data["date"]);

                if ($command == "vendor:process" && $date) {
                    // vendor:process 
                    shell_exec("php ../bin/console $command {$date->format('Y-m-d')} >/dev/null 2>&1 &");
                } elseif ($command == "cashout:generate" && $date) {
                    $date = \DateTime::createFromFormat("d/m/Y", $data["date"]);
                    $dateInterval = $date->diff(new \DateTime());
                    shell_exec(
                        "php ../bin/console $command {$date->format('Y-m-d')}" .
                        " -a=\"{$dateInterval->format('%a days')}\" -b=\"0 days\" >/dev/null 2>&1 &"
                    );
                } else {
                    shell_exec("php ../bin/console $command >/dev/null 2>&1 &");
                }
            }
        }

        if ($settingsForm->isValid()) {
            $successSettings = true;
            $data = $settingsForm->getData();

            $this->parameters->offsetSet('github.token', $data['token']);
            $this->parameters->offsetSet('email.logger.alert.level', $data['email_log_level']);
            $this->parameters->saveAll();
        }

        $params = $this->getDefaultSettingsParams();
        $params['successReRun'] = $successRerun;
        $params['successSettings'] = $successSettings;


        return $this->twig->render(
            'pages/settings.twig',
            $params
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

        try {
            system('cd ' . __DIR__ . '/../.. && php bin/console app:update 2>&1', $status);

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

        try {
            putenv('COMPOSER_HOME=' . __DIR__ . '/../../vendor/bin/composer');
            system(
                'cd ' . __DIR__ . '/../.. && composer update hipay/hipay-wallet-cashout-mirakl-library 2>&1',
                $status
            );
        } catch (Exception $ex) {
            return '<div class="alert alert-dismissible alert-danger">Error</div>';
        }
        echo '<div class="alert alert-dismissible alert-success">Success</div>';

        return new RedirectResponse($this->urlGenerator->generate("settings"), 302);
    }

    /**
     * set default twig variables for settings page
     * @return Array
     */
    private function getDefaultSettingsParams()
    {
        $reRunForm = $this->generateReRunForm();

        $settingsForm = $this->generateSettingsForm();

        $versions = $this->getVersions();

        $isWritable = $this->is_writable_r(__DIR__ . '/../../');

        $githubRateLimit = false;

        $githubTokenIsSet = ($this->parameters->offsetExists('github.token') &&
            !empty($this->parameters->offsetGet('github.token')));

        try {
            $updateLibrary = $this->updateAvailable(
                '/repos/hipay/hipay-wallet-cashout-mirakl-library/releases/latest',
                dirname(__FILE__) . '/../../vendor/hipay/hipay-wallet-cashout-mirakl-library/composer.json'
            );

            $updateIntegration = $this->updateAvailable(
                '/repos/hipay/hipay-wallet-cashout-mirakl-integration/releases/latest',
                dirname(__FILE__) . '/../../composer.json'
            );

        } catch (ClientErrorResponseException $e) {
            $updateLibrary = false;
            $updateIntegration = false;
            $githubRateLimit = true;
        }
        return array(
            'reRunForm' => $reRunForm->createView(),
            'settingsForm' => $settingsForm->createView(),
            'version' => $versions,
            'isWritable' => $isWritable,
            'updateLibrary' => $updateLibrary,
            'githubRateLimit' => $githubRateLimit,
            'githubTokenIsSet' => $githubTokenIsSet,
            'updateIntegration' => $updateIntegration,
            'dbms' => $this->parameters['db.driver']
        );
    }

    /**
     * Return Library and Application versions
     * @return type
     */
    private function getVersions()
    {

        $integration = $this->getComposerFile(dirname(__FILE__) . '/../../composer.json');

        $library = $this->getComposerFile(
            dirname(__FILE__) . '/../../vendor/hipay/hipay-wallet-cashout-mirakl-library/composer.json'
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
    private function generateReRunForm()
    {

        $default = array(
            'batch' => array(),
            'send' => true
        );

        $form = $this->formBuilder->createBuilder('form', $default)
            ->add(
                'batch',
                'choice',
                array(
                    'choices' => array(
                        'vendor:process' => $this->translator->trans('wallet.account.creation'),
                        'cashout:generate' => $this->translator->trans('generate.operation'),
                        'cashout:transfer' => $this->translator->trans('transfer'),
                        'cashout:withdraw' => $this->translator->trans('withdraw'),
                        'cashout:withdraw' => $this->translator->trans('withdraw'),
                    ),
                    'attr' => array('class' => 'form-control'),
                    'multiple' => true,
                    'label' => ''
                )
            )
            ->add(
                'date',
                'text',
                array(
                    'attr' => array('class' => 'form-control')
                )
            )
            ->add(
                'send',
                'submit',
                array(
                    'attr' => array('class' => 'btn btn-default btn-lg btn-block btn-hipay'),
                    'label' => $this->translator->trans('rerun')
                )
            )
            ->getForm();

        return $form;
    }

    /**
     *
     * @return type
     */
    private function generateSettingsForm()
    {

        $default = array(
            'token' => $this->parameters->offsetGet('github.token'),
            'email_log_level' => $this->parameters->offsetGet('email.logger.alert.level'),
            'send' => false
        );

        $form = $this->formBuilder->createBuilder('form', $default)
            ->add(
                'token',
                'text',
                array(
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Github token',
                    'required' => false
                )
            )
            ->add(
                'email_log_level',
                'choice',
                array(
                    'choices' => array(
                        100 => $this->translator->trans('Debug'),
                        200 => $this->translator->trans('Info'),
                        250 => $this->translator->trans('Notice'),
                        300 => $this->translator->trans('Warning'),
                        400 => $this->translator->trans('Error'),
                        500 => $this->translator->trans('Critical'),
                        550 => $this->translator->trans('Alert'),
                        600 => $this->translator->trans('Emergency'),
                        9999 => $this->translator->trans('none')
                    ),
                    'attr' => array('class' => 'form-control'),
                    'label' => $this->translator->trans('email.log.level')
                )
            )
            ->add(
                'send',
                'submit',
                array(
                    'attr' => array('class' => 'btn btn-default btn-lg btn-block btn-hipay'),
                    'label' => $this->translator->trans('save')
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
                        if (!$this->is_writable_r($dir . "/" . $object)) {
                            return false;
                        } else continue;
                    }
                }
                return true;
            } else {
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

        $request = $client->get($url . '?access_token=' . $this->parameters->offsetGet('github.token'));

        $response = $request->send();


        $latestVersion = $response->json();

        $installedVersion = $this->getComposerFile($composerPath);

        if (version_compare($installedVersion['version'], $latestVersion['tag_name'], '<')) {
            return true;
        }

        return false;
    }
}
