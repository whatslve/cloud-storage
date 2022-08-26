<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Models\FilesMarkup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class FoldersController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showFolder(Request $request, FilesMarkup $filesMarkup)
    {
        $request->validate([
            'id' => 'required|int'
        ]);

        $response = [];

        $id = $request->id;

        $files = $filesMarkup->findOrFail($id)
            ->files()
            ->get();

        $folder = FilesMarkup::select('*')
            ->where('id', '=', $id)
            ->first();

        if ($folder->user_id !== Auth::user()->id) {
            return response()->json(['Access deny']);
        }

        $response[] = [
            'files' => $files,
            'folder' => $folder
        ];

        if ($request->has('get_folder_size')) {
            $response[] = ['folder_size' => Utils::getFolderFilesSize($folder->folder_name)];
        }

        return response()->json($response, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function storeFolder(Request $request)
    {
        $request->validate([
            'folder_name' => 'required|string|max:255|not_in:main'
        ]);

        if ($request->has('folder_name')) {
            $filesMarkupModel = new FilesMarkup();
            $filesMarkupModel->user_id = Auth::user()->id;
            $filesMarkupModel->folder_name = $request->folder_name;
            $filesMarkupModel->save();

            return response()->json('Folder created', 201);
        }
    }
}
