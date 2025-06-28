<?php

namespace App\CustomConst;

class AllStatic
{
    const PURCHASE_STATUS_PENDING = 0;
    const PURCHASE_STATUS_APPROVED = 1;
    const PURCHASE_STATUS_REJECTED = 2;

    const PURCHASE_TYPE_CASH = 0;
    const PURCHASE_TYPE_CREDIT = 1;

    const DISCOUNT_TYPE_PERCENTAGE = 0;
    const DISCOUNT_TYPE_FIXED_AMOUNT = 1;

    const ALL_STATIC = [
        'PURCHASE_STATUS' => [
            'PENDING' => self::PURCHASE_STATUS_PENDING,
            'APPROVED' => self::PURCHASE_STATUS_APPROVED,
            'REJECTED' => self::PURCHASE_STATUS_REJECTED,
        ],
        'PURCHASE_TYPE' => [
            'CASH' => self::PURCHASE_TYPE_CASH,
            'CREDIT' => self::PURCHASE_TYPE_CREDIT,
        ],
        'DISCOUNT_TYPE' => [
            'PERCENTAGE' => self::DISCOUNT_TYPE_PERCENTAGE,
            'FIXED_AMOUNT' => self::DISCOUNT_TYPE_FIXED_AMOUNT,
        ],
        'IS_STOCK' => [
            'NOT_STOCK' => 0,
            'STOCK' => 1,
        ],
    ];
}