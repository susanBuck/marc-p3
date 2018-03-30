@if($errors->get($field))
    <ul class='error'>
        @foreach($errors->get($field) as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif