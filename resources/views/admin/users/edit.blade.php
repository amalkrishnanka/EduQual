@extends('layouts.app')
@section('header', 'Edit User')
@section('content')
    @include('admin.users.create', ['user' => $user])
@endsection
