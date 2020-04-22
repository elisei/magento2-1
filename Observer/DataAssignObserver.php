<?php
/**
 * Copyright © Fluxx. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Fluxx\Magento2\Observer;

use Magento\Framework\Event\Observer;
use Magento\Payment\Observer\AbstractDataAssignObserver;
use Magento\Quote\Api\Data\PaymentInterface;
use Fluxx\Magento2\Gateway\Request\DeviceRequest;

/**
 * Class DataAssignObserver
 */
class DataAssignObserver extends AbstractDataAssignObserver
{
    /**
     * @const transaction_result
     */
    const TRANSACTION_RESULT = 'transaction_result';

    /**
     * @const rg
     */
    const PAYER_RG  = 'rg';

    /**
     * @const birth_city
     */
    const PAYER_BIRTH_CITY  = 'birth_city';

    /**
     * @const birth_region
     */
    const PAYER_BIRTH_REGION  = 'birth_region';

    /**
     * @const gender
     */
    const PAYER_GENDER = 'gender';

    /**
     * @const dob
     */
    const PAYER_DOB = 'dob';

    /**
     * @const financing
     */
    const PAYER_OFFERS = 'financing';

    /**
     * @const financing_name
     */
    const PAYER_OFFERS_NAME = "financing_name";
    
    /**
     * @var array
     */
    protected $additionalInformationList = [
        self::TRANSACTION_RESULT,
        self::PAYER_RG,
        self::PAYER_BIRTH_CITY,
        self::PAYER_BIRTH_REGION,
        self::PAYER_GENDER,
        self::PAYER_DOB,
        self::PAYER_OFFERS,
        self::PAYER_OFFERS_NAME
    ];

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $data = $this->readDataArgument($observer);

        $additionalData = $data->getData(PaymentInterface::KEY_ADDITIONAL_DATA);

        if (!is_array($additionalData)) {
            return;
        }

        $paymentInfo = $this->readPaymentModelArgument($observer);

        foreach ($this->additionalInformationList as $additionalInformationKey) {
            if (isset($additionalData[$additionalInformationKey])) {
                $paymentInfo->setAdditionalInformation(
                    $additionalInformationKey,
                    $additionalData[$additionalInformationKey]
                );
            }
        }
    }
}
