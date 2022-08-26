
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{$file->file_name}}</div>

                    <div class="card-body">

                        <a href="/file/download/{{$file->id}}">Скачать</a>
                        <a href="/file/rename/{{$file->id}}">Переименовать</a>
                        <a href="/file/delete/{{$file->id}}">Удалить</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
