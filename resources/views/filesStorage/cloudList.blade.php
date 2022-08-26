@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Storage') }}</div>
                    <div class="card-body">
                        <h2>Файлы</h2>
                        @foreach($files as $file)
                            <div><a href="/file/{{$file->id}}">{{$file->file_name}}</a></div>
                        @endforeach
                        <br>
                        <hr>
                        <br>
                        <h2>Каталоги</h2>
                        @foreach($folders as $folder)
                            <div><a href="/folder/{{$folder->id}}">{{$folder->folder_name}}</a></div>
                        @endforeach
                        <br>
                        <hr>
                        <p>Размер всех файлов на диске {{$totalFilesSize}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
