<?php

namespace Sarmad\Gateway\Providers\Payir;

use Sarmad\Gateway\Exceptions\TransactionException;

class PayirSendException extends TransactionException
{

    /**
     * returns an associative array of `code` => `message`
     *
     * @return array
     */
    protected function getErrors()
    {
        return [
            -1 => 'ارسال api الزامی می باشد',
            -2 => 'ارسال amount ( مبلغ تراکنش ) الزامی می باشد',
            -3 => 'amount ( مبلغ تراکنش )باید به صورت عددی باشد',
            -4 => 'amount نباید کمتر از 1000 باشد',
            -5 => 'ارسال redirect الزامی می باشد',
            -6 => 'درگاه پرداختی با api ارسالی یافت نشد و یا غیر فعال می باشد',
            -7 => 'فروشنده غیر فعال می باشد',
            -8 => 'آدرس بازگشتی با آدرس درگاه پرداخت ثبت شده همخوانی ندارد',
            'failed' => 'تراکنش با خطا مواجه شد',
        ];
    }
}
