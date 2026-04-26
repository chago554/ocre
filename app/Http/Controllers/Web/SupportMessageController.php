<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use Illuminate\Http\Request;

class SupportMessageController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $query = SupportMessage::with('user');

            $allowed = ['subject', 'is_resolved', 'created_at'];
            foreach ((array) $request->input('sort', []) as $s) {
                if (in_array($s['field'] ?? '', $allowed)) {
                    $query->orderBy($s['field'], ($s['dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc');
                }
            }
            if (!$request->has('sort')) {
                $query->latest();
            }

            foreach ((array) $request->input('filter', []) as $f) {
                if (($f['field'] ?? '') === 'subject' && ($f['value'] ?? '') !== '') {
                    $query->where('subject', 'like', "%{$f['value']}%");
                }
            }

            $size = max(1, min((int) $request->input('size', 15), 100));
            return response()->json($query->paginate($size));
        }

        return view('buzon.index');
    }

    public function show(SupportMessage $message)
    {
        $message->load('user');
        return view('buzon.show', compact('message'));
    }

    public function toggleResolve(Request $request, SupportMessage $message)
    {
        $message->update(['is_resolved' => !$message->is_resolved]);
        if ($request->wantsJson()) {
            return response()->json([
                'message'     => 'Estado del mensaje actualizado.',
                'is_resolved' => $message->is_resolved,
            ]);
        }
        return back()->with('success', 'Estado del mensaje actualizado.');
    }

    public function destroy(SupportMessage $message)
    {
        $message->delete();
        if (request()->wantsJson()) {
            return response()->json(['message' => 'Mensaje eliminado correctamente.']);
        }
        return redirect()->route('buzon.index')
            ->with('success', 'Mensaje eliminado correctamente.');
    }
}
