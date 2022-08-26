
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ $file->file_name }}</div>
                       <div class="card-body">
                    <form method="post"
                          action="/file/update"
                          id="renameFileForm"
                          enctype="multipart/form-data">
                        @csrf
                        <input type="text" name="new_name" placeholder="Новое название файла">
                        <input type="hidden" name="file_id" value="{{$file->id}}">
                        <button>Переименовать</button>
                    </form>
                       </div>
                </div>
            </div>
        </div>
    </div>
@endsection
