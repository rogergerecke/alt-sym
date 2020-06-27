<?php


namespace App\Service;

/**
 * Class CalendarService
 * generate a simply calendar dates array
 * @package App\Service
 */
class CalendarService
{
    private $today;

    private $month = [
        1  => '01-01-',
        2  => '01-02-',
        3  => '01-03-',
        4  => '01-04-',
        5  => '01-05-',
        6  => '01-06-',
        7  => '01-07-',
        8  => '01-08-',
        9  => '01-09-',
        10 => '01-10-',
        11 => '01-11-',
        12 => '01-12-',
    ];

    private $calendar;
    /**
     * @var false|string
     */
    private $firstMonth;
    /**
     * @var false|string
     */
    private $year;


    public function __construct()
    {
        $this->today = time();

        $this->firstMonth = date('m', $this->today);

        $this->year = date('Y', $this->today);
    }

    private function make12Dates()
    {
        // exp.. 6 start month to build 12 month array
        $monthNumber = date('n', $this->today);
        $firstMonth = $monthNumber;
        $dates = null;

        for ($i = 1; $i != 13; $i++) {
            if ($monthNumber == 13) {
                $monthNumber = 1;
            }

            $rest = 12 - $monthNumber;

            if ($rest <= $firstMonth) {
                $dates[] = $this->month[$monthNumber].$this->year;
            } else {
                $dates[] = $this->month[$monthNumber].($this->year + 1);
            }

            $monthNumber++;
        }

        $this->month = $dates;
    }

    /**
     * Build the month for a year 1...12
     * but start with this month from today now
     * @param $date
     * @return array
     */
    private function buildMonth($date): array
    {

        $date = strtotime($date);

        return [
            'month'             => date('F', $date),
            'year'              => date('Y', $date),
            'first_day_of_week' => date('w', $date),
            'empty_day_fields'  => date('w', $date) - 1,
            'weeks'              => $this->createDaysArray($date),

        ];
    }


    /**
     * Create a array with the weeks for
     * a month group in 7seven days pieces
     * @param $date
     * @return array
     */
    private function createDaysArray($date): array
    {
        $days = null;
        $empty_day_fields = date('w', $date) - 1;
        $days_in_month = date('t', $date);

        $number = 1;
        for ($i = 1; $i <= 35; $i++) {
            if ($i <= $empty_day_fields) {
                $days[] = false;
            } else {
                if ($number <= $days_in_month) {
                    $days[] = $number;
                } else {
                    $days[] = false;
                }
                $number++;
            }
        }

        // group to weeks
        $week = null;
        $start = 0;
        $end = 7;
        for ($i=1; $i<=5; $i++) {
            $week[] = array_slice($days, $start, $end, true);
            $start = $start + 7 ;
        }

        return $week;
    }


    /**
     * Run the calendar creation and save it to
     * the and add the month to the array
     */
    private function makeCalendar(): void
    {
        $this->make12Dates();

        foreach ($this->month as $date) {
            $this->setCalendar($this->buildMonth($date));
        }
    }

    /**
     * @return int[]
     */
    public function getMonth(): array
    {
        return $this->month;
    }

    /**
     * @param int[] $month
     * @return CalendarService
     */
    public function setMonth(array $month): CalendarService
    {
        $this->month = $month;

        return $this;
    }


    /**
     * @return array
     */
    public function getCalendar(): array
    {
        $this->makeCalendar();

        return $this->calendar;
    }

    /**
     * Marge and add array to calendar
     * @param array $calendar
     * @return CalendarService
     */
    public function setCalendar(array $calendar): CalendarService
    {

        $this->calendar[] = $calendar;

        return $this;
    }
}
