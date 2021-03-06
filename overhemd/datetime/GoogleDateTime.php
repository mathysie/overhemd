<?php

declare(strict_types=1);

namespace overhemd\datetime;

use DateTimeZone;
use Google_Service_Calendar_EventDateTime;

class GoogleDateTime extends OverhemdDateTime
{
    public function __construct(Google_Service_Calendar_EventDateTime $datetime)
    {
        parent::__construct();

        if (!empty($datetime->date)) {
            $this->modify($datetime->date);
        } elseif (!empty($datetime->dateTime)) {
            $this->modify($datetime->dateTime);
            $timeZone = $datetime->timeZone ?? date_default_timezone_get();
            $this->setTimezone(new DateTimeZone($timeZone));
        }
    }
}
