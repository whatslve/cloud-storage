<?php

namespace App\Http\Controllers;

use App\Models\FilesMarkup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class FoldersController extends Controller
{
    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showFolder($id)
    {
        $files = FilesMarkup::find($id)
            ->files()
            ->get();

        $folder = FilesMarkup::select('*')->where('id', '=', $id)
            ->first();

        if ($folder->user_id !== Auth::user()->id) {
            return Redirect::back()->withErrors(['msg' => 'Access deny']);
        }

        return view('filesStorage.insideFolder')->with([
            'files' => $files,
            'folder' => $folder,
        ]);

    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function createFolder()
    {
        return view('filesStorage.createFolder');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|void
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
            return Redirect::back()->with('status', 'Folder created!');
        }
    }
}
