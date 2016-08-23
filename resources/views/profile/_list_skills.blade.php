@foreach ($skills as $skill)
    @include('profile._a_skill', ['skill' => $skill])
@endforeach