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

use Symfony\Component\Serializer\Serializer;
use HiPay\Wallet\Mirakl\Integration\Entity\LogGeneralRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Silex\Translator;

class LogGeneralController extends AbstractTableController
{

    public function __construct(
        LogGeneralRepository $repo,
        Serializer $serializer,
        Translator $translator,
        \Twig_Environment $twig
    ) {
        parent::__construct($repo, $serializer, $translator, $twig);
    }

    public function indexAction()
    {
        return $this->twig->render('pages/logs.twig', array());
    }

    /**
     * Export filtered logs in a csv file
     * @param Request $request
     * @return Response
     */
    public function exportCSVAction(Request $request)
    {

        $params = $request->query->all();

        $logs = $this->repo->findFilteredForExport($params);

        $rows = array();
        $rows[] = "Date,Level,Action,Mirakl ID,Message";
        foreach ($logs as $log) {
            $data = array($log['createdAt']->format('Y-m-d H:i:s'), $log['levelName'], $log['action'], $log['miraklId'],
                '"' . $log['message'] . '"');

            $rows[] = implode(',', $data);
        }

        $content = implode("\n", $rows);

        $response = new Response($content);
        $response->headers->set('Content-Type', "text/csv");

        setcookie("export", "true");

        return $response;
    }

    protected function prepareAjaxData($data)
    {
        foreach ($data as $key => $logRow) {
            $data[$key]['createdAt'] = $logRow['createdAt']->format('Y-m-d H:i:s');
        }

        return $data;
    }
}