<?php

namespace Sarmad\Gateway\Providers\MabnaOld;

use Sarmad\Gateway\Exceptions\TransactionException;

class MabnaOldException extends TransactionException
{

    /**
     * returns an associative array of `code` => `message`
     *
     * @return array
     */
    protected function getErrors()
    {
        return [
            '1' => 'وجود خطا در فرمت اطلاعات ارسالی',
            '2' => 'عدم وجود پذیرنده و ترمینال مورد درخواست در سیستم',
            '3' => 'رد درخواست به علت دریافت درخواست توسط آدرس آی‌پی نامعتبر',
            '4' => 'پذیرنده مورد نظر امکان استفاده از سیستم را ندارد.',
            '5' => 'برخورد با مشکل در انجام درخواست مورد نظر',
            '6' => 'خطا در پردازش درخواست',
            '7' => 'بروز خطا در تشخیص اصالت اطلاعات (امضای دیجیتالی نامعتبر است)',
            '8' => 'شماره خرید ارائه شده توسط پذیرنده (CRN) تکراری است.',

            '102' => 'تراکنش مورد نظر برگشت خورده است.',
            '103' => 'تایید انجام نشد.',
            '106' => 'پیامی از سوئیچ پرداخت دریافت نشد.',
            '107' => 'تراکنش درخواستی موجود نیست.',
            '111' => 'مشکل در ارتباط با سوئیچ',
            '112' => 'مقادیر ارسالی در درخواست معتبر نیستند.',
            '113' => 'خطای نامشخص',
            '200' => 'کاربر از انجام تراکنش منصرف شده است.',

            'gateway-faild-signature-verify' => 'خطا در بررسی صحت امضای دیجیتال دریافتی',
        ];
    }
}
