@extends('layouts.app')
@section('header', 'Edit Resource')
@section('content')
    @include('resources.create', ['resource' => $resource, 'types' => $types, 'subjects' => $subjects, 'gradeLevels' => $gradeLevels])
@endsection
