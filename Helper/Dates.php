<?php
declare(strict_types=1);

namespace Tarknaiev\BestBefore\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

/**
 * Class Dates
 * @package Tarknaiev\BestBefore\Helper
 */
class Dates extends AbstractHelper
{
    /**
     * @var TimezoneInterface
     */
    protected $timeZone;

    /**
     * Dates constructor.
     * @param Context $context
     * @param TimezoneInterface $timeZone
     */
    public function __construct (
        Context $context,
        TimezoneInterface $timeZone
    ) {
        parent::__construct($context);
        $this->timeZone = $timeZone;
    }

    /**
     * @param $borderDate
     * @return int
     */
    public function getDaysForNextDate($borderDate): int
    {
        $currentDate = $this->timeZone->date();
        $borderDate = $this->timeZone->date($borderDate);
        return (int) $currentDate->diff($borderDate)->format("%r%d");
    }

    /**
     * @param $daysLeft
     * @return string
     */
    public function getColour($daysLeft): string
    {
        switch ($daysLeft) {
            case $daysLeft >= 14:
                $result = "green";
                break;
            case ($daysLeft >= 5 and $daysLeft < 14):
                $result = 'orange';
                break;
            case $daysLeft < 5:
                $result = "red";
                break;
            case $daysLeft < 0:
                $result = "black";
                break;
            default:
                $result = '';
        }
        return $result;
    }
}
