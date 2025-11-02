<x-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    {{-- Title of the page Placeholder Fill --}}
    <x-slot:title>
        Dashboard- Unelma
    </x-slot:title>
    <!-- Navigation Bar -->
    <x-header />
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Welcome Message -->
        <h2>All Messages here</h2>
        @session('success')
            <p class="alert alert-success">{{ session('success') }}</p>
        @endsession
        <div class="container">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>S.N</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($messages as $message)
                        <tr>
                            <td>{{ $message->id }}
                                @if (!$message->is_read)
                                    <span class="badge rounded-pill text-bg-primary">new</span>
                                @endif
                            </td>
                            <td>{{ $message->name }}</td>
                            <td>{{ $message->email }}</td>
                            <td><button type="button" class="btn btn-primary  btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#viewModel" data-bs-id="{{ $message->id }}">
                                    View
                                </button>
                                <button type="button" class="btn btn-success  btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#replyModel" data-bs-id="{{ $message->id }}"> Reply </button>
                                <form action="{{ route('admin.contact-messages.destroy', $message->id) }}"
                                    method="POST" class='d-inline'>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- //Modael will shows here on view click  VIEW MODEL --}}
            <div class="modal fade" id="viewModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="viewModelLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="viewModelLabel">Check Message</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="">
                                <h4>From: <span class='from-model'></span></h4>
                                <strong class='name-sender'></strong>
                                <p class='message-sender'></p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success">Send</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Model for sending reply to Peoples --}}
            <div class="modal fade" id="replyModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="replyModelLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="replyModelLabel">Send Message</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="">
                                <h5>To: <span class='to-model'></span></h5>
                                <strong class='receiver-name'></strong>
                                <p class='message-sender'></p>
                                <form action="{{ route('admin.contact-messages.reply') }}" method="POST">
                                    @csrf
                                    <div class="form-floating">
                                        <input type="email" hidden class='email-receiver' name='email' />
                                        <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" name='reply'></textarea>
                                        <label for="floatingTextarea">Enter Your Reply</label>
                                    </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">reply</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
    <script>
        const viewModel = document.getElementById('viewModel')
        if (viewModel) {
            viewModel.addEventListener('show.bs.modal', event => {
                // Button that triggered the modal
                const button = event.relatedTarget
                const recipient = button.getAttribute('data-bs-id')
                // console.log('recipient', recipient);

                let message = document.getElementById('message');
                let messages = {{ Illuminate\Support\Js::from($messages) }};
                let ActualMessageWithId = messages.data.filter(data =>
                    data.id === Number(recipient)
                )
                // console.log(ActualMessageWithId[0])
                // Update the modal's content.
                const FromMail = viewModel.querySelector('.from-model')
                const Message = viewModel.querySelector('.message-sender')
                const SenderName = viewModel.querySelector('.name-sender')

                FromMail.textContent = `${ActualMessageWithId[0].email}`
                SenderName.textContent = ActualMessageWithId[0].name;
                Message.textContent = `${ActualMessageWithId[0].message}`;
            })
        }
        const replyModel = document.getElementById('replyModel')
        if (replyModel) {
            replyModel.addEventListener('show.bs.modal', event => {
                // Button that triggered the modal
                const button = event.relatedTarget
                const recipient = button.getAttribute('data-bs-id')
                // console.log('recipient', recipient);
                let messages = {{ Illuminate\Support\Js::from($messages) }};
                let ActualMessageWithId = messages.data.filter(data =>
                    data.id === Number(recipient)
                )
                console.log(ActualMessageWithId[0])
                const receiverMailDB = replyModel.querySelector('.email-receiver')
                const toMail = replyModel.querySelector('.to-model')
                const receiverName = replyModel.querySelector('.receiver-name')
                receiverMailDB.value = `${ActualMessageWithId[0].email}`
                toMail.textContent = `${ActualMessageWithId[0].email}`
                receiverName.textContent = ActualMessageWithId[0].name;
            })
        }
    </script>
</x-layout>
