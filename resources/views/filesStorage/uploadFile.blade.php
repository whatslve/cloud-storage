
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('File upload') }}</div>

                    <div class="card-body">
                        <form method="post"
                              action="/upload_file"
                              id="uploadFileForm"
                              onsubmit="return false;"
                              enctype="multipart/form-data">
                            @csrf
                            <input type="file" id="fileInput" name="file">
                            <select name="folder_id">
                                @foreach($folders as $folder)
                                    <option value="{{$folder->id}}">
                                        {{$folder->folder_name}}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit"  class="btn btn-default" id="loadFile">Загрузить</button>
                        </form>
                        <script>
                            document.getElementById('loadFile').addEventListener('click', validateUploadFile)
                            function validateUploadFile()
                            {
                                console.log('click');
                                if(document.getElementById('fileInput').files[0].size <= {{env('ALLOWED_FILE_SIZE')}}) {
                                    return document.getElementById('uploadFileForm').submit();
                                } else {
                                    return alert('Allowed file size is 20mb');
                                }
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
