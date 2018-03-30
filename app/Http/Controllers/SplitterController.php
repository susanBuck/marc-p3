<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SplitterController extends Controller
{
    private $split; #How many people to split amongst.  Value should be of type int().
    private $bill; #The total bill.  Value should be of type float().
    private $tip; #How much tip is being offered.  Value should be of type float().
    private $roundUp; #If the bill should be rounded up.  Value should be of type bool().

    /**
     * Splitter Constructor
     * @param $s : How many ways to split the bill.
     * @param $b : The bill.
     * @param $t : The tip.
     * @param $r : Boolean value whether to round up or not.
     */
    public function __construct($s, $b, $t, $r)
    {
        $this->split = $s;
        $this->bill = $b;
        $this->tip = $t;
        $this->roundUp = $r;
    }

    /**
     * Method that rounds split payments to the next dollar.
     * @param $a : The string array ( ex. ["2", "2.32", "1", "3.00"] )
     * that contains the values for the splits.
     * @return $a: The string array with the rounded values (ex. ["2", "3.00", "1", "3.00"] ).
     */
    public function roundWhole($a)
    {
        $a[1] = number_format(ceil(floatval($a[1])), 2, ".", "");
        $a[3] = number_format(ceil(floatval($a[3])), 2, ".", "");

        return $a;
    }

    /**
     * Method that splits the bill and indicates how many people pay for the regular amount and
     * how many people pay for the $0.01 extra.  Example shown in the @return array below.
     * @param $b : The calculated total bill with tip or not tip.
     * @param $cS : The calculated unmodified split ( ex. 16/3 = 5.3333 )
     * @return array: The string array with values of how many people pay for
     * how much ( ex. ["2", "2.32", "1", "3.00"] = 2 people pay for $2.32 and 1 person pays
     * for $3.00 )
     */
    public function splitWays($b, $cS)
    {
        $s = $this->split;
        $regularSplit = intval(($cS * 100)) / 100;
        $calculatedTotal = $regularSplit * $s;

        //If the payment
        if ($b == $calculatedTotal) {
            $cSString = number_format((float)$cS, 2, ".", "");

            return [(string)$s, $cSString, "0", "0.00"];
        } else {
            $difference = $b - $calculatedTotal;
            $payExtra = round($difference / 0.01);  //How many people will pay extra 1 cent
            $payNormal = $s - $payExtra; //How many people will pay the normal payment
            $extraSplit = $regularSplit + 0.01;

            $regularSplitString = number_format((float)$regularSplit, 2, ".", "");
            $extraSplitString = number_format((float)$extraSplit, 2, ".", "");

            return [(string)$payNormal, $regularSplitString, (string)$payExtra, $extraSplitString];
        }
    }

    /**
     * Calculates the unmodified split payment.
     * @param $b : The total bill, with tip or not.
     * @return float:  Returns the unmodified payment float value ( ex. 16/3 = 5.333333 )
     */
    public function calculatedSplit($b)
    {
        $s = $this->split;

        return floatval($b / $s);
    }

    /**
     * Getter method that returns the value of the bill with tip.
     * @return float: The value of the bill with tip.
     */
    public function getBillWithTip()
    {
        return round($this->bill * $this->tip, 2);
    }

    /**
     * Method that returns a string of the results of the split bill payments.  Uses
     * if else statements for different case such as "One person owes..." or "Three people owe..."
     * @param $a : The array of the split bill payments ( ex. ["2", "2.32", "1", "3.00"] =
     * 2 people pay for $2.32 and 1 person pays for $3.00 )
     * @return string: String of a user-friendly result which will go to the display file.
     */
    public function resultMaker($a)
    {
        if (floatVal($a[1]) == floatVal($a[3])) {
            return "Everyone owes $" . $a[1] . ".";
        } else if (floatVal($a[0]) == 1 && floatVal($a[2]) == 0) {
            return "1 person owes $" . $a[1] . ".";
        } else if (floatVal($a[0]) > 1 && floatVal($a[2]) == 0) {
            return "Everyone owes $" . $a[1] . ".";
        } else if (floatVal($a[0]) > 1 && floatVal($a[2]) == 0) {
            return "Everyone owes $" . $a[1] . ".";
        } else if (floatVal($a[0]) == 1 && floatVal($a[2]) == 1) {
            return $a[0] . " person owes $" . $a[1] . " and " . $a[2] . " person owes $" . $a[3] . ".";
        } else if (floatVal($a[0]) > 1 && floatVal($a[2]) == 1) {
            return $a[0] . " people owe $" . $a[1] . " and " . $a[2] . " person owes $" . $a[3] . ".";
        } else if (floatVal($a[0]) == 1 && floatVal($a[2]) > 1) {
            return $a[0] . " person owes $" . $a[1] . " and " . $a[2] . " people $" . $a[3] . ".";
        } else {
            return $a[0] . " people owe $" . $a[1] . " and " . $a[2] . " people owe $" . $a[3] . ".";
        }
    }
}
