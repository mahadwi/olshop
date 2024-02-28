<?php

namespace App\Constants;

class OrderState extends AbstractAppConstant
{
    public const UNPAID = 'Unpaid';
    public const ONPROCESS = 'On Process';
    public const ONGOING = 'On Going';
    public const COMPLETED = 'Completed';
    public const CANCEL = 'Cancel';
}