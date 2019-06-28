<?php

namespace Sarmad\Gateway\Contracts;

interface Provider
{

    /**
     * Set the callback url. (Optional)
     * if it doesn't get called fallback to config file
     *
     * @param string $url
     * @return self
     */
    public function callbackUrl($url);

    /**
     * Authorize the transaction before send the user to the payment page.
     *
     * @param \Sarmad\Gateway\Transactions\RequestTransaction $transaction
     * @return \Sarmad\Gateway\Transactions\AuthorizedTransaction
     * @throws \Exception
     */
    public function authorize($transaction);

    /**
     * Redirect the user of the application to the provider's payment screen.
     *
     * @param \Sarmad\Gateway\Transactions\AuthorizedTransaction $transaction
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Illuminate\Contracts\View\View
     */
    public function redirect($transaction);

    /**
     * Verify and Settle the transaction and get the settled transaction instance.
     *
     * @return \Sarmad\Gateway\Transactions\SettledTransaction
     * @throws \Sarmad\Gateway\Exceptions\InvalidRequestException
     * @throws \Sarmad\Gateway\Exceptions\TransactionException
     * @throws \Exception
     */
    public function settle();
}
