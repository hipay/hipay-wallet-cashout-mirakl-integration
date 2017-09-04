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

use HiPay\Wallet\Mirakl\Integration\Entity\BatchRepository;
use Symfony\Component\Serializer\Serializer;
use Silex\Translator;

class BatchController extends AbstractTableController
{
    protected $repo;
    protected $serializer;
    protected $translator;

    public function __construct(BatchRepository $repo, Serializer $serializer, Translator $translator)
    {
        $this->repo       = $repo;
        $this->serializer = $serializer;
        $this->translator = $translator;
    }

    protected function prepareAjaxData($data)
    {
        foreach ($data as $key => $logRow) {
            $data[$key]['startedAt'] = $logRow['startedAt']->format('Y-m-d H:i:s');

            if ($logRow['error'] == null && $logRow['endedAt'] == null) {
                $data[$key]['state'] = array(
                    'state' => 1,
                    'label' => $this->translator->trans('En cours'),
                    'button' => '',
                );
            } else if ($logRow['error'] == null) {
                $data[$key]['state'] = array(
                    'state' => 2,
                    'label' => $this->translator->trans('Terminé'),
                    'button' => '<button type="button" class="btn btn-success btn-xs vendor-notice" data-container="body" data-toggle="popover" data-placement="bottom" data-content="'.$logRow["endedAt"]->format('Y-m-d H:i:s').'" data-original-title="" title="" ><i class="glyphicon glyphicon-question-sign" ></i></button>',
                );
            }else{
                $data[$key]['state'] = array(
                    'state' => -1,
                    'label' => $this->translator->trans('Erreur'),
                    'button' => '<button type="button" class="btn btn-info btn-xs vendor-notice" data-container="body" data-toggle="popover" data-placement="bottom" data-content="'.htmlspecialchars($logRow["error"]).'" data-original-title="" title="" ><i class="glyphicon glyphicon-question-sign" ></i></button>',
                );
            }
        }

        return $data;
    }
}