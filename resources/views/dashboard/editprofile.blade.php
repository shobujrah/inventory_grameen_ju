            <div class="modal-body">
                <!-- Include your edit form here -->
                <form action="{{ route('user.profile.page.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="name">Name<span class="login-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth<span class="login-danger">*</span></label>
                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $user->date_of_birth) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="phone_number">Phone Number<span class="login-danger">*</span></label>
                        <input type="text" id="phone_number" name="phone_number" class="form-control" value="{{ old('phone_number', $user->phone_number) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" class="form-control" value="{{ old('address', $user->address) }}">
                    </div>

                    <div class="form-group">
                        <label for="avatar">Avatar<span class="login-danger">(.jpeg,.png,.jpg & max 2 mb)</label>
                        <!-- <input type="file" id="avatar" name="avatar" class="form-control"> -->
                        <input type="file" id="avatar" name="avatar" class="form-control" accept=".jpeg,.png,.jpg" max-file-size="2048">

                        @if($user->avatar)
                            <img src="{{ asset('images/' . $user->avatar) }}" alt="Avatar" class="img-thumbnail mt-2" width="100">
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>