<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Information - Academix</title>
    @vite('resources/css/app.css')
    <style>
        body{
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f9fafb 0%, #eef2f7 40%, #e5e7eb 100%);
            padding: 2rem;
            color: #111827;
        }
        .wrap{ max-width: 920px; margin: 0 auto; }
        .card{
            background: rgba(255,255,255,0.92);
            border: 1px solid rgba(255,255,255,0.6);
            border-radius: 18px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06);
            padding: 2rem;
        }
        h1{ font-size: 1.75rem; margin-bottom: .35rem; }
        .sub{ color:#6b7280; margin-bottom: 1.5rem; }
        .grid{
            display:grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }
        .field{ display:flex; flex-direction:column; gap:.4rem; }
        label{ font-size:.95rem; color:#374151; }
        input, select{
            border: 1px solid #d1d5db;
            border-radius: 12px;
            padding: .8rem .9rem;
            background: #fff;
            outline: none;
        }
        input:focus, select:focus{ border-color:#b91c1c; box-shadow: 0 0 0 4px rgba(185,28,28,.12); }
        .error{ color:#b91c1c; font-size:.9rem; }
        .actions{ margin-top: 1.25rem; display:flex; gap:.8rem; }
        .btn{
            border: none;
            border-radius: 12px;
            padding: .9rem 1.1rem;
            font-weight: 700;
            cursor:pointer;
        }
        .btn-primary{
            background: #b91c1c;
            color: #fff;
            border-radius: 10px;
            padding: 0.6rem 1.1rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .btn-primary:hover{
            background: #991b1b;
            transform: translateY(-1px);
        }
        .btn-secondary{
            background: #6b7280;
            color:#fff;
            text-decoration:none;
            display:inline-flex;
            align-items:center;
            border-radius: 10px;
            padding: 0.6rem 1.1rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .btn-secondary:hover{
            background:#4b5563;
            transform: translateY(-1px);
        }
        .btn-outline{
            background: #fff;
            color: #b91c1c;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 0.6rem 1.1rem;
            font-weight: 700;
            transition: all 0.2s ease;
        }
        .btn-outline:hover{
            background: #fff5f5;
            transform: translateY(-1px);
        }
        .readonly input[disabled], .readonly select[disabled]{
            background: #f9fafb;
            color: #6b7280;
            cursor: not-allowed;
        }
        @media(max-width: 860px){
            body{ padding: 1rem; }
            .grid{ grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <div style="display:flex; align-items:center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                <div>
                    <h1>Personal Information Form</h1>
                    <div class="sub" style="margin-bottom:0;">Review your details. Click Edit to make changes.</div>
                </div>
                @php
                    $backUrl = old('redirect_to', $redirectTo ?? '');
                    if (!is_string($backUrl) || $backUrl === '') $backUrl = route('enroll');
                @endphp
                <a class="btn btn-secondary" href="{{ $backUrl }}">← Back</a>
            </div>

            @if(session('error'))
                <div class="error" style="margin-bottom: 1rem;">{{ session('error') }}</div>
            @endif

            @php $isExisting = !empty($registration); @endphp
            <form method="POST" action="{{ route('registration.save') }}" id="regForm" class="{{ $isExisting ? 'readonly' : '' }}">
                @csrf
                <input type="hidden" name="redirect_to" value="{{ $redirectTo ?? '' }}">

                <div class="grid">
                    <div class="field">
                        <label for="first_name">First Name</label>
                        <input id="first_name" name="first_name" value="{{ old('first_name', $registration->first_name ?? '') }}" required {{ $isExisting ? 'disabled' : '' }}>
                        @error('first_name') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="middle_name">Middle Name (Optional)</label>
                        <input id="middle_name" name="middle_name" value="{{ old('middle_name', $registration->middle_name ?? '') }}" {{ $isExisting ? 'disabled' : '' }}>
                        @error('middle_name') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="last_name">Last Name</label>
                        <input id="last_name" name="last_name" value="{{ old('last_name', $registration->last_name ?? '') }}" required {{ $isExisting ? 'disabled' : '' }}>
                        @error('last_name') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="suffix">Suffix (Optional)</label>
                        <input id="suffix" name="suffix" value="{{ old('suffix', $registration->suffix ?? '') }}" placeholder="Jr., Sr., III" {{ $isExisting ? 'disabled' : '' }}>
                        @error('suffix') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="age">Age</label>
                        <input id="age" name="age" type="number" min="1" max="120" value="{{ old('age', $registration->age ?? '') }}" required {{ $isExisting ? 'disabled' : '' }}>
                        @error('age') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="nationality">Nationality</label>
                        <input id="nationality" name="nationality" value="{{ old('nationality', $registration->nationality ?? '') }}" required {{ $isExisting ? 'disabled' : '' }}>
                        @error('nationality') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender" required {{ $isExisting ? 'disabled' : '' }}>
                            @php $g = old('gender', $registration->gender ?? ''); @endphp
                            <option value="" {{ $g===''?'selected':'' }} disabled>Select</option>
                            <option value="Male" {{ $g==='Male'?'selected':'' }}>Male</option>
                            <option value="Female" {{ $g==='Female'?'selected':'' }}>Female</option>
                            <option value="Other" {{ $g==='Other'?'selected':'' }}>Other</option>
                            <option value="Prefer not to say" {{ $g==='Prefer not to say'?'selected':'' }}>Prefer not to say</option>
                        </select>
                        @error('gender') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="contact_number">Contact Number</label>
                        <input id="contact_number" name="contact_number" value="{{ old('contact_number', $registration->contact_number ?? '') }}" required placeholder="09xxxxxxxxx" {{ $isExisting ? 'disabled' : '' }}>
                        @error('contact_number') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field" style="grid-column: 1 / -1;">
                        <label for="address_line">Address</label>
                        <input id="address_line" name="address_line" value="{{ old('address_line', $registration->address_line ?? '') }}" required placeholder="House/Unit, Street, Barangay" {{ $isExisting ? 'disabled' : '' }}>
                        @error('address_line') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="city">City / Municipality</label>
                        <input id="city" name="city" value="{{ old('city', $registration->city ?? '') }}" required {{ $isExisting ? 'disabled' : '' }}>
                        @error('city') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="province">Province</label>
                        <input id="province" name="province" value="{{ old('province', $registration->province ?? '') }}" required {{ $isExisting ? 'disabled' : '' }}>
                        @error('province') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="zip_code">Zip Code (Optional)</label>
                        <input id="zip_code" name="zip_code" value="{{ old('zip_code', $registration->zip_code ?? '') }}" {{ $isExisting ? 'disabled' : '' }}>
                        @error('zip_code') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="guardian_name">Guardian Name (Optional)</label>
                        <input id="guardian_name" name="guardian_name" value="{{ old('guardian_name', $registration->guardian_name ?? '') }}" {{ $isExisting ? 'disabled' : '' }}>
                        @error('guardian_name') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="guardian_contact_number">Guardian Contact No. (Optional)</label>
                        <input id="guardian_contact_number" name="guardian_contact_number" value="{{ old('guardian_contact_number', $registration->guardian_contact_number ?? '') }}" {{ $isExisting ? 'disabled' : '' }}>
                        @error('guardian_contact_number') <div class="error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="actions">
                    <button class="btn btn-outline" type="button" id="editBtn" style="{{ $isExisting ? '' : 'display:none;' }}">Edit</button>
                    <button class="btn btn-primary" type="submit" id="saveBtn" style="{{ $isExisting ? 'display:none;' : '' }}">Save Changes</button>
                    <button class="btn btn-secondary" type="button" id="cancelBtn" style="display:none;">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            const form = document.getElementById('regForm');
            const editBtn = document.getElementById('editBtn');
            const saveBtn = document.getElementById('saveBtn');
            const cancelBtn = document.getElementById('cancelBtn');

            if (!form || !editBtn || !saveBtn || !cancelBtn) return;

            const fields = Array.from(form.querySelectorAll('input, select')).filter(el => el.name && el.name !== 'redirect_to' && el.type !== 'hidden');
            const initial = new Map(fields.map(el => [el.name, el.value]));

            function setReadOnly(isReadOnly) {
                fields.forEach(el => {
                    if (isReadOnly) el.setAttribute('disabled', 'disabled');
                    else el.removeAttribute('disabled');
                });
                if (isReadOnly) form.classList.add('readonly');
                else form.classList.remove('readonly');
            }

            editBtn.addEventListener('click', function () {
                setReadOnly(false);
                editBtn.style.display = 'none';
                saveBtn.style.display = 'inline-flex';
                cancelBtn.style.display = 'inline-flex';
            });

            cancelBtn.addEventListener('click', function () {
                fields.forEach(el => {
                    const v = initial.get(el.name);
                    if (typeof v !== 'undefined') el.value = v;
                });
                setReadOnly(true);
                editBtn.style.display = 'inline-flex';
                saveBtn.style.display = 'none';
                cancelBtn.style.display = 'none';
            });
        })();
    </script>
</body>
</html>

