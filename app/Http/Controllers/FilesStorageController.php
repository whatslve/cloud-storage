<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use App\Models\Files;
use App\Models\FilesMarkup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class FilesStorageController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $folders = Auth::user()
            ->folders()
            ->where('folder_name', '!=', 'main')
            ->get();

        $mainFolder = FilesMarkup::select('*')
            ->where('user_id', '=', Auth::user()->id)
            ->where('folder_name', '=', 'main')
            ->first();

        $files = Auth::user()
            ->files()
            ->where('folder_id', '=', $mainFolder->id)
            ->get();


        $totalFilesSize = Utils::getStorageFilesSize();

        return view('filesStorage.cloudList')->with([
            'folders' => $folders,
            'files' => $files,
            'totalFilesSize' => Utils::convert_bytes($totalFilesSize),
        ]);
    }



    /**
     * Show the form for upload new file.
     *
     *
     */
    public function create()
    {
        $folders = Auth::user()
            ->folders()
            ->get();
        return view('filesStorage.uploadFile')->with([
            'folders' => $folders
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function storeFile(Request $request)
    {
        if ($request->isMethod('post')) {
            if ($request->hasFile('file')) {
                $file = $request->file('file');

                if ($file->getSize() >= env('ALLOWED_FILE_SIZE')) {
                    return Redirect::back()->withErrors(['msg' => 'Max file size is 20mb']);
                }

                $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);

                if($file->getSize() + Utils::getStorageFilesSize() > env('MAX_DISK_SPACE')) {
                    return Redirect::back()->withErrors(['msg' => 'Disk space limit is 100mb']);
                }

                if ($extension === 'php') {
                    return Redirect::back()->withErrors(['msg' => 'Php files is disallowed']);
                }

                $userId = Auth::user()->id;
                $folder = FilesMarkup::select('*')
                    ->when($request->has('folder_id'), function ($query) use ($request) {
                        return $query->where('id', '=', $request->folder_id);
                    })
                    ->when(!$request->has('folder_id'), function ($query) use ($request, $userId) {
                        return $query->where('user_id', '=', $userId)->where('folder_name', '=', 'main');
                    })
                    ->first();

                $fileName = time() . '_' . $file->getClientOriginalName();

                $filePath = 'user_storage_' . $userId . '/' . $folder->folder_name;

                $fileFullPath = $filePath . '/' . $fileName;

                Storage::disk('local')->putFileAs(
                    $filePath,
                    $file,
                    $fileName,
                );

                $filesModel = new Files();
                $filesModel->file_name = $fileName;
                $filesModel->folder_id = $folder->id;
                $filesModel->user_id = $userId;
                $filesModel->file_path = $fileFullPath;
                $filesModel->save();

                return Redirect::back()->with('status', 'File uploaded!');
            } else {
                return Redirect::back()->withErrors(['msg' => 'File not selected']);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     */
    public function showFile($id)
    {
        $file = Files::select('*')->where('id', '=', $id)->first();
        if ($file->user_id !== Auth::user()->id) {
            return Redirect::back()->withErrors(['msg' => 'Access deny']);
        }

        return view('filesStorage.showFile')->with([
            'file' => $file,
        ]);
    }

    public function downloadFile($id, Files $files)
    {
        $file = $files->where('id', '=', $id)->first();

        if ($file->user_id !== Auth::user()->id) {
            return Redirect::back()->withErrors(['msg' => 'Access deny']);
        }

        return Storage::download($file->file_path);
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit($id)
    {
        $file = Files::findOrFail($id);

        if ($file->user_id !== Auth::user()->id) {
            return Redirect::back()->withErrors(['msg' => 'Access deny']);
        }

        return view('filesStorage.renameFile')->with([
            'file' => $file,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, Files $files)
    {
       $file = $files->findOrfail($request->file_id);
       $newName = $request->new_name;
       $fileOriginalName = $file->file_path;

        if ($file->user_id !== Auth::user()->id) {
            return Redirect::back()->withErrors(['msg' => 'Access deny']);
        }

       $extension = pathinfo($fileOriginalName, PATHINFO_EXTENSION);
       $newFilePath= dirname($fileOriginalName) .'/'. $newName . '.' . $extension;
       Storage::move($fileOriginalName, $newFilePath);
       $file->file_name = $newName.'.' . $extension;
       $file->file_path = $newFilePath;
       $file->save();

       return Redirect::back()->with('status', 'File renamed!');
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        $file = Files::findOrFail($id);
        $file->delete();

        Storage::disk('local')->delete($file->file_path);

        return \redirect('storage')->with('status', 'File ' . $file->file_name . ' удален!');
    }
}
