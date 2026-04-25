@extends('backend.layouts.app')

@section('content')
    <div class="dash-section active" id="sec-profile">
        <div class="section-toolbar">
            <h2 class="section-h">Profile Settings</h2>
        </div>

        <div class="profile-settings-grid">
            <div class="dash-card">
                <div class="card-header-d">
                    <div>
                        <h5>Profile Information</h5>
                        <p class="profile-settings-subtext">Update your name and email address.</p>
                    </div>
                </div>

                <form method="post" action="{{ route('profile.update') }}" class="profile-form-card">
                    @csrf
                    @method('patch')

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label for="name">Name</label>
                            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                            @error('name')
                                <p class="profile-form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group-d">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
                            @error('email')
                                <p class="profile-form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="profile-settings-note">
                            <p>Your email address is unverified.</p>

                            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                                @csrf
                            </form>

                            <button form="send-verification" class="btn-primary-d profile-inline-btn" type="submit">
                                Resend Verification
                            </button>

                            @if (session('status') === 'verification-link-sent')
                                <p class="profile-form-success">A new verification link has been sent to your email address.</p>
                            @endif
                        </div>
                    @endif

                    <div class="profile-actions">
                        <button class="btn-primary-d" type="submit">
                            Save Profile <i class="fas fa-save ms-2"></i>
                        </button>

                        @if (session('status') === 'profile-updated')
                            <p class="profile-form-success">Profile updated successfully.</p>
                        @endif
                    </div>
                </form>
            </div>

            <div class="dash-card">
                <div class="card-header-d">
                    <div>
                        <h5>Change Password</h5>
                        <p class="profile-settings-subtext">Use a strong password to keep your account secure.</p>
                    </div>
                </div>

                <form method="post" action="{{ route('password.update') }}" class="profile-form-card">
                    @csrf
                    @method('put')

                    <div class="form-group-d">
                        <label for="current_password">Current Password</label>
                        <input id="current_password" name="current_password" type="password" autocomplete="current-password">
                        @error('current_password', 'updatePassword')
                            <p class="profile-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label for="password">New Password</label>
                            <input id="password" name="password" type="password" autocomplete="new-password">
                            @error('password', 'updatePassword')
                                <p class="profile-form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group-d">
                            <label for="password_confirmation">Confirm Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
                            @error('password_confirmation', 'updatePassword')
                                <p class="profile-form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="profile-actions">
                        <button class="btn-primary-d" type="submit">
                            Update Password <i class="fas fa-key ms-2"></i>
                        </button>

                        @if (session('status') === 'password-updated')
                            <p class="profile-form-success">Password updated successfully.</p>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .profile-settings-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .profile-settings-subtext {
            color: var(--text-muted);
            font-size: 12px;
            margin-top: 4px;
        }

        .profile-settings-note {
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 18px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .profile-inline-btn {
            padding: 10px 16px;
            min-height: auto;
        }

        .profile-actions {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
            margin-top: 6px;
        }

        .profile-form-error {
            color: #ff7b8f;
            font-size: 12px;
            margin-top: 2px;
        }

        .profile-form-success {
            color: var(--green);
            font-size: 12px;
            margin: 0;
        }

        @media (min-width: 992px) {
            .profile-settings-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
@endsection
