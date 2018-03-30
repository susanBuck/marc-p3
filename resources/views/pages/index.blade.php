@extends('layouts.master')

@section('content')

    Hi! I'm Katy Perry. You probably don't recognize me without my blue wig. When I'm not singing on tour, making music videos or brushing Nugget's cute curls, I split bills! It's definitely a fun hobby and a great way for me to practice my math skills. Make sure to fill in the above fields. If you don't want any change, just check the "Round Up" selection and I'll round your payment to the next whole dollar. Thanks!

    <form method='GET' action='/calculation'>
    <div class="row">

        <div class="col-sm-6">
            <label>*Split:

                <input type="text"
                       name="split"
                       class="splitTextBox"
                       value="{{ old('split', $split)  }}"
                       placeholder="4"
                       required>
                @include('modules.error-field', ['field' => 'split'])
            </label>

            <br>

            <label>*Bill: $
                <input type="text"
                       name="bill"
                       class="billTextBox"
                       value="{{ old('bill', $bill) }}"
                       placeholder='62.51'
                       required>
                @include('modules.error-field', ['field' => 'bill'])
            </label>

            <p>
                <small><em>*Required Inputs</em></small>
            </p>

        </div>

        <div class="col-sm-3">
            <label class="tipLabel">Tip:
                <select name="tip" class="tipDropdown">
                    <option value="1" {{ isset($tip) and $tip == '1' ? 'selected' : '' }}>No Tip</option>
                    <option value="1.10" {{ isset($tip) and $tip == '1.10' ? 'selected' : '' }}>10% Tip</option>
                    <option value="1.15" {{ isset($tip) and $tip == '1.15' ? 'selected' : '' }}>15% Tip</option>
                    <option value="1.20" {{ isset($tip) and $tip == '1.20' ? 'selected' : '' }}>20% Tip</option>
                </select>
            </label>
        </div>

        <div class="col-sm-3">
            <label>Round Up:
                <input type="checkbox" name="roundUp" value="1"  {{ (isset($roundUp) and $roundUp) ? 'checked' : '' }}>
            </label>
        </div>
    </div>


    <input type="submit" value="Split It Girl!" class="splitButton" name="submit">

    </form>

    @if($calcError)
        <div class="alert alert-danger">
            Unable to make calculation as the split bill would be less than $0.01.
        </div>
    @elseif($results)
        <div class="alert alert-success">
            {{ $results }}
        </div>
    @endif

    @include('modules.error-form')

@endsection