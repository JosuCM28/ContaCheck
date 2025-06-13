<?php

namespace App\Http\Controllers;

use File;
use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Client;
use Illuminate\Support\Facades\Storage;
use Response;

class FileController extends Controller
{
    public function store(Request $request, Client $client)
    {
        $request->validate([
            'title' => 'required',
            'file_path' => 'required',
        ]);

        $path = $request->file('file_path')->store('files','public');
        $clientId = $client->id;
        $document = new Document();
        $document->client_id = $clientId;
        $document->title = $request->input('title');
        $document->file_path = $path;
        
        $document->save();
        return redirect()->back();
    }

    public function destroy(Document $document)
    {
        $document = Document::findOrFail($document->id);
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path); // Eliminar archivo
        }
        $document->delete();
        return redirect()->back()->with('success', 'Documento Borrado Exitosamente');
    }

    public function download(Document $document)
    {
        $document = Document::findOrFail($document->id);
        $path = public_path() . '/storage/' . $document->file_path;
        $name = $document->title . '.pdf';
        return Response::download($path, $name );
    }
}