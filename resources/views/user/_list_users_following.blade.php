@foreach($users as $user)
    @include('user._a_user_detail', ['user' => $user->followed])
@endforeach