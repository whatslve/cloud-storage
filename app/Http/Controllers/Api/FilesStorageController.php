<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Models\Files;
use App\Models\FilesMarkup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

/**
 *
 */
class FilesStorageController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
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

        return response()->json([
            'folders' => $folders,
            'files' => $files,
            'totalFilesSize' => $totalFilesSize,
        ], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|max:' . env('ALLOWED_FILE_SIZE') / 1024,
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);

            if ($file->getSize() + Utils::getStorageFilesSize() > env('MAX_DISK_SPACE')) {
                return response()->json([
                    'Max disk space is 100mb'
                ], 400);
            }

            if ($extension === 'php') {
                return response()->json([
                    'Php files disallowed'
                ], 400);
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

            return response()->json([
                'File uploaded!'
            ], 201);
        } else {
            return response()->json([
                'File not selected!'
            ], 404);
        }
    }

    /**
     * @param Request $request
     * @param Files $files
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadFile(Request $request, Files $files)
    {
        $request->validate([
            'id' => 'required|int'
        ]);

        $id = $request->id;

        $file = $files->findOrFail($id);

        if ($file->user_id !== Auth::user()->id) {
            return response()->json([
                'access deny'
            ], 403);
        }

        return Storage::download($file->file_path);
    }

    /**
     * @param Request $request
     * @param Files $files
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Files $files)
    {
        $request->validate([
            'file_id' => 'required|int',
            'new_name' => 'required|string|max:255'
        ]);

        $file = $files->findOrfail($request->file_id);
        $newName = $request->new_name;
        $fileOriginalName = $file->file_path;

        if ($file->user_id !== Auth::user()->id) {
            return response()->json([
                'Access deny'
            ], 403);
        }

        $extension = pathinfo($fileOriginalName, PATHINFO_EXTENSION);

        $newFilePath = dirname($fileOriginalName) . '/' . $newName . '.' . $extension;

        Storage::move($fileOriginalName, $newFilePath);

        $file->file_name = $newName . '.' . $extension;
        $file->file_path = $newFilePath;
        $file->save();

        return response()->json([
            'File: ' . $fileOriginalName . ' renamed to: ' . $newName
        ], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|int'
        ]);

        $id = $request->id;

        $file = Files::findOrFail($id);

        if ($file->user_id !== Auth::user()->id) {
            return response()->json([
                'Access deny'
            ], 403);
        }

        $file->delete();

        Storage::disk('local')->delete($file->file_path);

        return response()->json([
            'status', 'File ' . $file->file_name . ' deleted!'
        ], 200);
    }
}
