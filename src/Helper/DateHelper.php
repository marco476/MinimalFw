<?php
namespace Helper;

class DateHelper
{
    public static function isDateInFuture(\DateTime $date)
    {
        return $date->getTimestamp() > date_create()->getTimestamp();
    }
}
