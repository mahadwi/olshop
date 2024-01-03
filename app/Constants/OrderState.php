<?php

namespace App\Constants;

class OrderState extends AbstractAppConstant
{
    public const ONPROCESS = 'On Process';
    public const ONGOING = 'On Going';
    public const COMPLETED = 'Selesai';
    public const CANCEL = 'Cancel';
    public const RETURN = 'Return';
}