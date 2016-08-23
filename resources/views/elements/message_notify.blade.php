@if ($errors->has() || Session::has('message') || Session::has('message_error'))
    <div class="alert {{ Session::has('message') ? 'alert-success' : 'alert-danger' }}" id="{{ isset($id) ? $id : '' }}">
        @foreach($errors->all() as $message)
            <p>{{ $message }}</p>
        @endforeach
        @if (Session::has('message'))
            <p>{{ Session::get('message') }}</p>
        @elseif (Session::has('message_error'))
            <p>{{ Session::get('message_error') }}</p>
        @endif
    </div>
@endif