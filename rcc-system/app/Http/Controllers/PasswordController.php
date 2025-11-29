<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function requestReset()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email'=>'required|email']);
        $ip = $request->ip();
        $key = 'reset_req_'.$ip;
        if (cache()->get($key, 0) >= 5) {
            return back()->withErrors(['email'=>'Muitas tentativas. Tente novamente mais tarde.'])->withInput();
        }
        $user = User::where('email', $request->email)->first();
        if (!$user) return back()->with('status','Se existir, enviaremos o link de redefinição');

        $token = Str::random(64);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email'=>$user->email],
            ['token'=>Hash::make($token), 'created_at'=>now()]
        );

        Mail::send('emails.password_reset', ['token'=>$token,'email'=>$user->email], function($m) use ($user){
            $cfg = \App\Models\Setting::where('key','email')->first();
            $fromEmail = $cfg?->value['from_email'] ?? config('mail.from.address');
            $fromName = $cfg?->value['from_name'] ?? config('mail.from.name');
            $subject = $cfg?->value['subject'] ?? 'Redefinição de senha';
            $m->from($fromEmail, $fromName)->to($user->email)->subject($subject);
        });
        cache()->increment($key);
        cache()->put($key, cache()->get($key), now()->addMinutes(10));

        return back()->with('status','Enviamos um link para redefinir sua senha');
    }

    public function showResetForm(Request $request, string $token)
    {
        return view('auth.passwords.reset', ['token'=>$token, 'email'=>$request->query('email')]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'token'=>'required',
            'password'=>'required|min:8|regex:/[A-Z]/|regex:/[a-z]/|regex:/[0-9]/|regex:/[^A-Za-z0-9]/|confirmed',
        ]);
        $record = DB::table('password_reset_tokens')->where('email',$request->email)->first();
        if (!$record) return back()->withErrors(['email'=>'Token inválido'])->withInput();
        if (now()->diffInMinutes($record->created_at) > 60*24) return back()->withErrors(['email'=>'Token expirado'])->withInput();
        if (!Hash::check($request->token, $record->token)) return back()->withErrors(['email'=>'Token inválido'])->withInput();

        User::where('email',$request->email)->update(['password'=>Hash::make($request->password)]);
        DB::table('password_reset_tokens')->where('email',$request->email)->delete();

        return redirect('/login')->with('status','Senha redefinida com sucesso');
    }

    public function showChangeForm()
    {
        return view('auth.passwords.change');
    }

    public function change(Request $request)
    {
        $request->validate([
            'current_password'=>'required',
            'password'=>'required|min:8|regex:/[A-Z]/|regex:/[a-z]/|regex:/[0-9]/|regex:/[^A-Za-z0-9]/|confirmed',
        ]);
        $user = $request->user();
        if (!Hash::check($request->current_password, $user->password)) return back()->withErrors(['current_password'=>'Senha atual incorreta']);
        $user->forceFill(['password'=>Hash::make($request->password)])->save();
        \Log::info('Password changed', ['user_id'=>$user->id, 'ip'=>$request->ip()]);
        \Log::info('User password changed', ['user_id'=>$user->id]);
        return back()->with('status','Senha alterada com sucesso');
    }
}
