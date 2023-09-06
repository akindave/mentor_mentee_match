<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Mentor;
use App\Models\Mentee;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show(Request $request, User $user): Response
    {
        ($user->mentor) ? $user->mentor->load(['skills']) : '';

        // if the user is the logged in user, and they are a mentor, load the mentor's sessions
        if ($request->user()?->is($user) && $user->mentor) {
            $user->mentor->load(['sessions.mentee.user']);
        }

        // if the user is the logged in user, and they are a mentee, load the mentee's sessions
        if ($request->user()?->is($user) && $user->mentee) {
            $user->mentee->load(['sessions.mentor.user']);
        }

        if (!$request->user()?->is($user) && $user->mentee) {
            $user->mentee->with(['sessions.mentee.user']);
        }

        $checkIfMenteeAlready = Mentee::where('user_id',$request->user()->id)
        ->where('mentor_id',$user->id)->first();

        if($checkIfMenteeAlready){
            $is_mentor = $checkIfMenteeAlready->status;
        }else{
            $is_mentor = 2;
        }

        $getMenteeList = Mentee::where('status',0)
        ->where('mentor_id',$user->id)->with(['user'])->get();



        return Inertia::render('Profile/Show', [
            'user' => $user,
            'session' => ($request->user()->mentee) ? $request->user()->mentee->load(['sessions.mentor.user']) : [],
            'mentor' => $user->load(['mentor','mentor.skills']),
            'is_mentor' => $is_mentor,
            'mentee_list' => $getMenteeList
        ]);
    }

    public function userChoosenSkill(Request $request)
    {

        // dd($request->user()->with('skills')->first());
        $getSkillID = \DB::table('skill_user')->where('user_id',$request->user()->id)->pluck('skill_id');
        return response()->json([
            'userskill' =>  Skill::whereIn('id',$getSkillID)->get(),
        ]);
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->safe()->only(['name', 'email','bio']));

        $values = $request->validated();

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();
        $request->user()->skills()->sync(collect($values['skills'])->pluck('id'));

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Update the user's timezone.
     */
    public function updateTimezone(Request $request): RedirectResponse
    {
        $request->validate([
            'timezone' => ['required', 'timezone'],
        ]);

        $request->user()->fill($request->only('timezone'));
        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Update the user's avatar.
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar_url' => ['required', 'url'],
        ]);

        $request->user()->fill($request->only('avatar_url'));
        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Update user mentor status
     */
    public function updateMentorStatus(Request $request): RedirectResponse
    {
        $request->validate([
            'is_mentor' => ['required', 'boolean'],
        ]);

        Auth::user()->mentor()->save(new Mentor);

        return Redirect::route('profile.edit');
    }

    /**
     * Show mentor information
     */
    public function showMentorInformation(Request $request)
    {
        return response()->json([
            'mentor' => $request->user()->mentor()->with('skills')->first(),
        ]);
    }

    /**
     * Update user mentor information
     */
    public function updateMentorInformation(Request $request): RedirectResponse
    {
        $request->validate([
            'job_title' => ['nullable', 'string'],
            'company' => ['nullable', 'string'],
            'hourly_rate' => ['nullable', 'numeric'],
            'skills' => ['nullable', 'array'],
        ]);

        $mentor = $request->user()->mentor;
        $mentor->job_title = $request->job_title;
        $mentor->company = $request->company;
        $mentor->hourly_rate = $request->hourly_rate;
        $mentor->save();

        $mentor->skills()->sync(collect($request->skills)->pluck('id'));

        return Redirect::route('profile.edit');
    }

    /**
     * Show skills
     */
    public function showSkills(Request $request)
    {
        return response()->json([
            'skills' => Skill::all(),
        ]);
    }

    public function becomeMentee(Request $request){

        $success = Mentee::create([
            'user_id' => $request->input('id'),
            'mentor_id' => $request->input('mentor')
        ]);

        if(!$success){
            return response()->json(['success' => false]);
        }
        return response()->json(['success' => true]);

    }
}
