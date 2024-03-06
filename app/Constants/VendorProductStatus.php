<?php

namespace App\Constants;

class VendorProductStatus extends AbstractAppConstant
{
    public const REVIEW         = 'Review';
    public const VALID          = 'Valid';
    public const SENT           = 'Sent';
    public const DELIVERED      = 'Delivered';
    public const APPROVED       = 'Approved';
    public const NOT_APPROVED   = 'Not Approved';
    public const COMPLETED      = 'Completed';
    public const CANCELED       = 'Canceled';
}