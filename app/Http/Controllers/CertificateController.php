<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\File;
use App\Models\Post;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    /**
     * Show the certificate generation form.
     * Only accessible to users with 25+ certified posts (Official contributor).
     */
    public function create()
    {
        $user = Auth::user();
        $certifiedCount = Post::where('user_id', $user->id)
            ->whereNotNull('certified_at')
            ->count();

        if ($certifiedCount < 25) {
            abort(403, __('You need at least 25 certified posts to generate a certificate.'));
        }

        return view('certificates.create');
    }

    /**
     * Generate and store the certificate, then stream the PDF.
     */
    public function store(Request $request, CertificateService $service)
    {
        $user = Auth::user();

        $certifiedCount = Post::where('user_id', $user->id)
            ->whereNotNull('certified_at')
            ->count();

        if ($certifiedCount < 25) {
            abort(403, __('You need at least 25 certified posts to generate a certificate.'));
        }

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:120'],
            'years' => ['required', 'array', 'min:1', 'max:10'],
            'years.*' => ['required', 'string', 'max:20'],
        ]);

        // Count total views for this user's posts
        $postIds    = Post::where('user_id', $user->id)->pluck('id')->toArray();
        $totalViews = File::whereIn('post_id', $postIds)->sum('download_count');

        $certificate = Certificate::create([
            'user_id'     => $user->id,
            'cert_id'     => Str::upper(Str::random(4)) . '-' . Str::upper(Str::random(4)) . '-' . Str::upper(Str::random(4)),
            'name'        => $validated['name'],
            'years'       => $validated['years'],
            'total_posts' => $certifiedCount,
            'total_views' => (int) $totalViews,
            'issued_at'   => now(),
        ]);

        $curriculum = $user->curriculum;
        if (!$curriculum) {
            $certificate->delete();
            return back()->withErrors(['general' => __('Unable to determine your curriculum.')]);
        }

        if (!config('features.latex_enabled')) {
            return redirect()->route('certificates.index')
                ->with('message', __('Certificate saved. PDF generation is currently disabled on this server.'));
        }

        try {
            $result = $service->generate($certificate, $user, $curriculum);

            return response()->download($result['pdfPath'], 'certificate-' . $certificate->cert_id . '.pdf')
                ->deleteFileAfterSend(false)
                ->withHeaders(['Content-Type' => 'application/pdf'])
                ->then(function () use ($service, $result) {
                    $service->cleanup($result['tmpDir']);
                });
        } catch (\RuntimeException $e) {
            return redirect()->route('certificates.index')
                ->with('message', __('Certificate saved but PDF generation failed: ') . $e->getMessage());
        }
    }

    /**
     * Download a previously-issued certificate PDF.
     */
    public function download(Certificate $certificate, CertificateService $service)
    {
        $user = Auth::user();

        if ($certificate->user_id !== $user->id) {
            abort(403);
        }

        if (!config('features.latex_enabled')) {
            abort(503, __('PDF generation is currently disabled on this server.'));
        }

        $curriculum = $user->curriculum;
        if (!$curriculum) {
            abort(503, __('Unable to determine your curriculum.'));
        }

        try {
            $result = $service->generate($certificate, $user, $curriculum);

            return response()->download($result['pdfPath'], 'certificate-' . $certificate->cert_id . '.pdf')
                ->deleteFileAfterSend(false)
                ->withHeaders(['Content-Type' => 'application/pdf'])
                ->then(function () use ($service, $result) {
                    $service->cleanup($result['tmpDir']);
                });
        } catch (\RuntimeException $e) {
            return back()->withErrors(['general' => $e->getMessage()]);
        }
    }

    /**
     * List the authenticated user's certificates.
     */
    public function index()
    {
        $user         = Auth::user();
        $certificates = Certificate::where('user_id', $user->id)->latest('issued_at')->get();

        return view('certificates.index', compact('certificates'));
    }

    /**
     * Public verification page for a certificate.
     */
    public function verify(string $certId)
    {
        $certificate = Certificate::where('cert_id', $certId)->with('user')->first();

        return view('certificates.verify', compact('certificate', 'certId'));
    }
}
