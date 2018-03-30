<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MoneyFormat implements Rule
{
    /**
     * Create a new rule instance.
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!$this->initialBillValidity($value)) {
            return false;
        } else {
            if (!$this->secondaryBillValidity($value)) {
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * Method used by the moneyFormat method.  Checks if the bill user input in question
     * is numeric and is not a negative number.
     * @param $test : User input bill in question.  If passes the first test, $test goes
     * to nested secondaryBillValidity method to see if input is totally correct.
     * @return bool: Returns false if the bill in question is non numeric or a negative value.
     */
    private function initialBillValidity($test)
    {
        if (!is_numeric($test)) {
            return false;
        } else if (floatval($test) <= 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Nested method to see if the bill input in question is valid.  Valid inputs include
     * whole numbers/ints (ex. 2) and floats with maximum two places after the decimal (ex. 0.1 which is interpreted
     * by the program as 0.10, 1. which is interpreted as $1.00.  Invalid input includes $0.001 and $4.321.)  Note
     * that the "1." is considered valued input as user could have by accident put a decimal after an intended whole
     * number.
     * @param $test
     * @return bool
     */
    private function secondaryBillValidity($test)
    {
        if (!strpos($test, ".")) {
            return true;
        } else {
            $splicedTest = explode(".", $test);
            $checkDecimal = $splicedTest[1];

            if (strlen($checkDecimal) <= 2) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Get the validation error message.
     * @return string
     */
    public function message()
    {
        return 'The Bill must be in USD Currency Format.';
    }
}
