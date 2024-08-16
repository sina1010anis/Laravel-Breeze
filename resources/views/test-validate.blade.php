<form action="{{route('validate-test')}}" method="post">

    @csrf

    <input type="text" name="name">

    <button type="submit">send</button>

</form>

<ul>
@foreach ($errors->FormTestBag->all() as $error)

    <li>{{$error}}</li>

@endforeach
</ul>
