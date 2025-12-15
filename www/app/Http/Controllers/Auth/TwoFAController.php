<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Persona;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;

class TwoFAController
{
    /** Usuario “pendiente” (pre-auth) guardado por el Login/Registro */
    private function pendingUser(Request $request): ?Persona
    {
        $id = $request->session()->get('2fa:pending:id');
        return $id ? Persona::find($id) : null;
    }

    public function show(Request $request, Google2FA $g2fa)
    {
        $user = $this->pendingUser($request);
        if (!$user) {
            return redirect()->route('login');
        }

        $showQr = false;
        $qrSvg  = null;
        $secret = null;

        if (!$user->SecretKey) {
            $tempSecret = $request->session()->get('2fa:pending:secret');
            if (!$tempSecret) {
                $tempSecret = $g2fa->generateSecretKey();
                $request->session()->put('2fa:pending:secret', $tempSecret);
            }

            $otpUrl = $g2fa->getQRCodeUrl(config('app.name'), $user->Mail, $tempSecret);
            $qrSvg  = $this->qrSvgInline($otpUrl);
            $secret = $tempSecret;
            $showQr = true;
        }

        return response()
            ->view('auth.2fa', compact('showQr', 'qrSvg', 'secret'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    public function verify(Request $request, Google2FA $g2fa)
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $user = $this->pendingUser($request);
        if (!$user) {
            return redirect()->route('login');
        }

        // Setup inicial (cuando aún no hay SecretKey en DB)
        if (!$user->SecretKey) {
            $tempSecret = $request->session()->get('2fa:pending:secret');
            if (!$tempSecret) {
                return redirect()->route('2fa.show')
                    ->withErrors(['code' => 'La sesión de configuración expiró. Vuelve a intentarlo.']);
            }

            if (!$g2fa->verifyKey($tempSecret, $request->input('code'), 30)) {
                return back()->withErrors(['code' => 'Código inválido.']);
            }

            $user->SecretKey = $tempSecret;
            $user->save();

            $request->session()->forget('2fa:pending:secret');

            return $this->finalizeLogin($request, $user);
        }

        // Challenge normal (ya hay SecretKey en DB)
        if (!$g2fa->verifyKey($user->SecretKey, $request->input('code'), 30)) {
            return back()->withErrors(['code' => 'Código inválido.']);
        }

        return $this->finalizeLogin($request, $user);
    }

    private function finalizeLogin(Request $request, Persona $user)
    {
        $remember = (bool) $request->session()->pull('2fa:remember', false);

        Auth::login($user, $remember);
        $request->session()->forget('2fa:pending:id');
        $request->session()->regenerate();

        $persona = Auth::user();

        if ($persona->admin) {
            Auth::guard('admin')->login($persona, $remember);
            return redirect()->route('home');
        }

        if ($persona->usuario) {
            return redirect()->route('home');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->withErrors(['Mail' => 'Tu cuenta no tiene rol válido.']);
    }

    private function qrSvgInline(string $otpauthUrl): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle(300),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);

        return $writer->writeString($otpauthUrl);
    }
}
