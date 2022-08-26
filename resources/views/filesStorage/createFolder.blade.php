

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Create Folder') }}</div>
                    <div class="card-body">
                    <form method="post" action="/store_folder" enctype="multipart/form-data">
                        @csrf
                        <input type="text" name="folder_name" placeholder="Название папки">
                        <button type="submit">Создать папку</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
