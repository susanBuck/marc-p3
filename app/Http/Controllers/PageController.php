<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rules\MoneyFormat;
use Validator;

class PageController extends Controller
{
    /*
     * Show the form
     */
    public function index(Request $request)
    {
        // Get the data from the session if it exists
        $results = $request->session()->get('results');
        $calcError = $request->session()->get('calcError');
        $bill = $request->session()->get('bill');
        $split = $request->session()->get('split');

        // Show the form
        return view('pages.index')->with([
            'results' => $results,
            'calcError' => $calcError,
            'bill' => $bill,
            'split' => $split,
        ]);
    }

    /*
     * Process the form
     */
    public function calculation(Request $request)
    {
        // Validate the data
        $this->validate($request, [
            "bill" => ["required", new MoneyFormat],
            "split" => "integer|min:1|max:100|required",
        ]);

        // Note: If validation fails, the user is automatically sent back to `/`

        // Run the calculation with the data
        $splitter = new SplitterController(
            $request->split,
            $request->bill,
            $request->tip,
            $request->round
        );

        $billWithTip = $splitter->getBillWithTip();
        $calcS = $splitter->calculatedSplit($billWithTip);
        $splitBetween = $splitter->splitWays($billWithTip, $calcS);

        if ($request->round == true) {
            $splitBetween = $splitter->roundWhole($splitBetween);
        }

        $calcError = $calcS < 0.01;

        $results = $splitter->resultMaker($splitBetween);

        $request->session()->put('bill', $request->bill);
        $request->session()->put('split', $request->split);
        $request->session()->put('tip', $request->tip);
        $request->session()->put('roundUp', $request->roundUp);

        // Redirect back to the page to show the form, and include the results
        return redirect('/')->with([
            'results' => $results,
            'calcError' => $calcError
        ]);
    }
}

