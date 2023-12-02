@extends('layouts.wrapper')

@section('content')
@foreach ($methods as $method)
public function {{ $method->name }}({{ $method->args }})
{{ '{' }}
    $args = array_map(function ($arg) {
        if ($arg instanceof \UnitEnum) {
            return $arg->name;
        }
        return $arg;
    }, func_get_args());
    $args_combine = implode(' . ', $args);
    $key = '{{ $original->full_qualifier }}||{{ $method->attribute->getArguments()['name'] }}||' . $args_combine;
    $cache = Cache::get($key);
    if (isset($cache)) {
        return $cache;
    }
    $result = $this->original->{{ $method->name }}({{ $method->args }});
    Cache::put($key, $result, {{ $method->attribute->getArguments()['ttl_seconds'] }});
    return $result;
{{ '}' }}
@endforeach
@endsection
