@extends('admin.layout')

@section('content')
    <div class="bg-secondary w-full rounded-s-[2rem] p-6 flex flex-col gap-6">
        <!-- Breadcrumb -->
        <nav>
            <ol class="flex gap-1">
                <li class="inline-flex items-center">
                    <a href="{{ url('/users/list') }}" class="text-[#566E71] text-lg font-light">
                        Users
                    </a>
                </li>
                <img src="{{ asset('images/images/arrow-right-dark.svg') }}" alt="arrow right">
                <li aria-current="page">
                    <div class="flex items-center">
                        <span class="text-main text-lg">Create </span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Edit Form -->
        <form action="{{ route('admin.consultant.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
            @csrf
            @method('post')
            <input type="hidden" name="user_type" value="{{ $user_type }}">
            <!-- Personal Information -->
            <div class="bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.048)] rounded-[1.375rem]">
                {{-- <div class="h-20 bg-gradient-to-b from-main from-40% to-main/25 flex justify-center">
                    <img class="h-20 w-20 object-cover rounded-full border-2 border-white top-10 relative"
                        src="{{asset('images/images/user.png') }}" alt="user image">
                </div> --}}
                <div class="pt-16 pb-4 px-5 flex justify-between items-center border-b border-light-blue-200">
                    <p class="text-darkBlue font-semibold text-[1.375rem]">Personal Information</p>
                </div>
                <div class="flex flex-col gap-4 p-6">
                    <div class="flex flex-col gap-4">
                        <div class="flex">
                            <label for="name" class="block w-1/4 text-[#808080]">Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full">
                        </div>
                        @error('name')
                            <span class="text-tiny+ text-error" style="color:red">{{ $message }}</span>
                        @enderror
						{{--
                        <div class="flex">
                            <label for="age" class="block w-1/4 text-[#808080]">Birth date</label>
                            <input type="date" id="age" name="date_birth" pattern="\d{4}-\d{2}-\d{2}" value="{{ old('date_birth') }}"
                                class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full">
                        </div>
                        @error('date_birth')
                            <span class="text-tiny+ text-error" style="color:red">{{ $message }}</span>
                        @enderror
						--}}
                        <div class="flex">
                            <label for="email" class="block w-1/4 text-[#808080]">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full">
                        </div>
                        @error('email')
                            <span class="text-tiny+ text-error" style="color:red">{{ $message }}</span>
                        @enderror
                        <div class="flex">
                            <label for="password" class="block w-1/4 text-[#808080]">password</label>
                            <input type="password" id="password" name="password" value="{{ old('password') }}"
                            class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full">
                        </div>
                        @error('password')
                            <span class="text-tiny+ text-error" style="color:red">{{ $message }}</span>
                        @enderror
                        <div class="flex">
                            <label for="mobile" class="block w-1/4 text-[#808080]">Phone</label>
                            <input type="mobile" id="mobile" name="mobile" value="+974{{ old('mobile') }}"
                                class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full">
                        </div>
                        @error('mobile')
                            <span class="text-tiny+ text-error" style="color:red">{{ $message }}</span>
                        @enderror
						
						<div class="flex">
                            <label for="fees" class="block w-1/4 text-[#808080]">Fees</label>
                            <input type="fees" id="fees" name="fees" value="{{ old('fees') }}"
                                class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full">
                        </div>
                        @error('fees')
                            <span class="text-tiny+ text-error" style="color:red">{{ $message }}</span>
                        @enderror

                        <div class="flex">
                            <label for="specialty" class="block w-1/4 text-[#808080]">{{ __('lang.specialty') }}</label>
                            <select id="specialty" name="specialty[]" multiple class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full">
                                <!-- Options for phone numbers -->
                               @php
                               $specialties = \App\Models\SpecialistsDepartment::all();
                               @endphp
                               @foreach ($specialties as $specialty)

                               <option value="{{ $specialty->id }}">{{ $specialty->title_en }}</option>

                               @endforeach
                                {{-- <option value="123">123</option>
                                <option value="456">456</option>
                                <option value="789">789</option> --}}
                                <!-- Add more options as needed -->
                            </select>
                        </div>
                        @error('specialty')
                            <span class="text-tiny+ text-error" style="color:red">{{ $message }}</span>
                        @enderror
                        {{-- <input type="file" name="test" > --}}
                        <div class="flex">
                            <label for="id-f" class="block w-1/4 text-[#808080]"> profile</label>
                            <input type="file" id="id-f" name="profile" class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full">
                        </div>
                        @error('profile')
                         <span class="text-tiny+ text-error" style="color:red">{{ $message }}</span>
                        @enderror

                        <div class="flex">
                            <label for="id-f" class="block w-1/4 text-[#808080]"> Front ID Card or Passport</label>
                            <input type="file" id="id-f" name="doctor_id_1" class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full">
                        </div>
                        @error('doctor_id_1')
                         <span class="text-tiny+ text-error" style="color:red">{{ $message }}</span>
                        @enderror

                        <div class="flex">
                            <label for="id-b" class="block w-1/4 text-[#808080]"> Back ID Card or Passport</label>
                            <input type="file" id="id-b" name="doctor_id_2" class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full">
                        </div>
                        @error('doctor_id_2')
                         <span class="text-tiny+ text-error" style="color:red">{{ $message }}</span>
                        @enderror


                        <div class="flex">
                            <label for="photo" class="block w-1/4 text-[#808080]">{{ __('lang.Personal Photo') }}</label>
                            <input type="file" id="photo" name="photo" class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full">
                        </div>
                        @error('photo')
                         <span class="text-tiny+ text-error" style="color:red">{{ $message }}</span>
                        @enderror

                        <div class="flex">
                            <label for="certificate" class="block w-1/4 text-[#808080]">{{ __('lang.certificate') }}</label>
                            <input type="file" id="certificate" name="certificate" class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full">
                        </div>
                        @error('certificate')
                         <span class="text-tiny+ text-error" style="color:red">{{ $message }}</span>
                        @enderror



                        <!-- Other fields can be added here -->
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save
                </button>
            </div>
        </form>
    </div>
@endsection
