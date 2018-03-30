@if(count($errors) > 0)
    <ul class='alert alert-danger' id='validationError'>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif