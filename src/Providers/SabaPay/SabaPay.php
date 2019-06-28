<?php

namespace Sarmad\Gateway\Providers\SabaPay;

use Exception;
use Illuminate\Http\Request;
use Sarmad\Gateway\AbstractProvider;
use Sarmad\Gateway\Exceptions\TransactionException;
use Sarmad\Gateway\GatewayManager;
use Sarmad\Gateway\Transactions\AuthorizedTransaction;
use Sarmad\Gateway\Transactions\SettledTransaction;
use Sarmad\Gateway\Transactions\UnAuthorizedTransaction;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SabaPay extends AbstractProvider
{

    /**
     * Address of main CURL server
     *
     * @var string
     */
    const SERVER_URL = 'http://pay.sabanovin.com/invoice/request';

    /**
     * Address of CURL server for verify payment
     *
     * @var string
     */
    const SERVER_VERIFY_URL = 'http://pay.sabanovin.com/invoice/check/';

    /**
     * Address of gate for redirect
     *
     * @var string
     */
    const URL_GATE = 'http://pay.sabanovin.com/invoice/pay/';


    /**
     * Get this provider name to save on transaction table.
     * and later use that to verify and settle
     * callback request (from transaction)
     *
     * @return string
     */
    protected function getProviderName()
    {
        return GatewayManager::SABAPAY;
    }

    /**
     * Authorize payment request from provider's server and return
     * authorization response as AuthorizedTransaction
     * or throw an Error (most probably SoapFault)
     *
     * @param UnAuthorizedTransaction $transaction
     * @return AuthorizedTransaction
     * @throws Exception
     */
    protected function authorizeTransaction(UnAuthorizedTransaction $transaction)
    {
        $fields = [
            'api_key' => $this->config['api'],
            'amount' => $transaction->getAmount()->getToman(),
            'return_url' => $this->getCallback($transaction, true),
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::SERVER_URL);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $response = json_decode($response, true);
        curl_close($ch);

        if ($response['status'] == 1) {
            return AuthorizedTransaction::make($transaction, $response['invoice_key']);
        }

        throw new SabaPayException($response['errorCode']);
    }

    /**
     * Redirect the user of the application to the provider's payment screen.
     *
     * @param \Sarmad\Gateway\Transactions\AuthorizedTransaction $transaction
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Illuminate\Contracts\View\View
     */
    protected function redirectToGateway(AuthorizedTransaction $transaction)
    {
        return new RedirectResponse(self::URL_GATE . $transaction->getReferenceId());
    }

    /**
     * Validate the settlement request to see if it has all necessary fields
     *
     * @param Request $request
     * @return bool
     * @throws TransactionException
     */
    protected function validateSettlementRequest(Request $request)
    {
        $status = $request->input('status');

        if ($status == 0) {
            return true;
        }

        throw new SabaPayException($status);
    }

    /**
     * Verify and Settle the transaction and return
     * settlement response as SettledTransaction
     * or throw a TransactionException
     *
     * @param Request $request
     * @param AuthorizedTransaction $transaction
     * @return SettledTransaction
     * @throws TransactionException
     * @throws Exception
     */
    protected function settleTransaction(Request $request, AuthorizedTransaction $transaction)
    {
        $trackingCode = $request->input('bank_code');
        $cardNumber = $request->input('card_number');

        $fields = [
            'api_key' => $this->config['api'],
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::SERVER_VERIFY_URL . $request->input('invoice_key'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $response = json_decode($response, true);
        curl_close($ch);

        if ($response['status'] == 1) {
            return new SettledTransaction($transaction, $trackingCode, $cardNumber);
        }

        throw new SabaPayException($response['errorCode']);
    }
}
