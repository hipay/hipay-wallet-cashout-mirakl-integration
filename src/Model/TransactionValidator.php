<?php
namespace HiPay\Wallet\Mirakl\Integration\Model;
use HiPay\Wallet\Mirakl\Cashout\Model\Transaction\ValidatorInterface;

/**
 * File TransactionValidator.php
 *
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */
class TransactionValidator implements ValidatorInterface
{

    /**
     * Validate a transaction
     *
     * @param array $transaction
     *
     * @return boolean
     */
    public function isValid(array $transaction)
    {
        return true;
    }
}