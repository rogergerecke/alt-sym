<?php


namespace App\Service;

use DateTime;

/**
 * Class CalendarService
 * generate a simply calendar dates array
 * @package App\Service
 */
class CalendarService
{

    /**
     * @var
     */
    private $calendar;

    /**
     * @var array
     */
    private $year;

    /**
     * @var DateTime
     */
    private $dateTime;
    /**
     * @var array
     */
    private $month;


    public function __construct()
    {
        $this->dateTime = new DateTime();
        $this->buildYear();
    }

    /**
     * Build one year with towels month
     * with weeks and additional data
     *
     */
    protected function buildYear(): void
    {
        // build the 12 month
        for ($i = 1; $i <= 12; $i++) {
            // add month
            $this->month[] = [
                'weeks'             => self::createMonth($this->dateTime),
                'year'              => $this->dateTime->format('Y'),
                'month_name'        => $this->dateTime->format('F'),
                'first_day_of_week' => $this->dateTime->format('w'),
                'empty_day_fields'  => $this->dateTime->format('w') - 1,
            ];

            // got to next month
            $this->dateTime = $this->dateTime->modify("+1 month");
        }

        // save the year
        $this->setCalendar($this->month);
    }


    /**
     * Build the month with week and days
     *
     * @param $date
     * @return array
     */
    private static function createMonth($date): array
    {
        $days = null;

        $empty_day_fields = $date->format('w') - 1;
        $days_in_month = $date->format('t');

        // the month start ever with 1
        $number = 1;
        $weeks = null;
        $start = 0;
        $end = 7;

        // week cells
        for ($i = 1; $i <= 35; $i++) {
            if ($i <= $empty_day_fields) {
                $days[] = false;
            } else {
                if ($number <= $days_in_month) {
                    $days[] = $date->format('Y-m').'-'.$number;
                } else {
                    $days[] = false;
                }
                $number++;
            }
        }

        // cut the days in rows
        for ($i = 1; $i <= 5; $i++) {
            $weeks[] = array_slice($days, $start, $end, true);
            $start = $start + 7;
        }

        return $weeks;
    }

    /**
     * @return mixed
     */
    public function getCalendar()
    {
        return $this->calendar;
    }

    /**
     * @param mixed $calendar
     * @return CalendarService
     */
    public function setCalendar($calendar)
    {
        $this->calendar = $calendar;

        return $this;
    }

}
